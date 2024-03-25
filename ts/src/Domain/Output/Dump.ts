import {EventEmitter} from 'node:events';
//
import interpret from 'interpret';
import rechoir from 'rechoir';
//
import * as fs from 'fs';
//
import {HandlerInterface} from '../../bus';
//
import {File, FilesFinder} from '../../Infrastructure/Filesystem';
import {DumpMessage} from '../../Application';
import {CommandCode} from '../../Application/Commands';
import {Blueprint} from '../../Application/Config';
import {InfoMessage} from '../../Application/InfoMessage';
import {DryRunMode, GeneratedFile, GeneratingFile, GeneratingFails, NoFileFound} from './Events';
import {Presets} from '../Input/Settings';
import {Border, Color, Outline, Spacing, Typography} from '../Input/Styles';

export class Dump implements HandlerInterface<InfoMessage, number> {
    public constructor(
        private readonly eventEmitter: EventEmitter,
        private readonly fileFinder: FilesFinder
    ) {}

    public handle(message: DumpMessage): number {
        const files = this.fileFinder.find(message.rootFolder, `json.${message.ext}`);

        let count = 0;
        for (const file of files) {
            count++;
            if (message.dryRun === true) {
                this.eventEmitter.emit(DryRunMode.name);
                continue;
            }

            this.dumpFile(file)
                // eslint-disable-next-line @typescript-eslint/no-unused-vars
                .then((r) => {
                    // @todo: think about if this is necessary
                    // console.log('done');
                    // console.log(r);
                })
                // eslint-disable-next-line @typescript-eslint/no-unused-vars
                .catch((e) => {
                    // console.error(e);
                });
        }

        if (count === 0) {
            this.eventEmitter.emit(
                NoFileFound.name,
                new NoFileFound('No files found for dumping, try to run `init` command first.')
            );
            return CommandCode.FAILURE;
        }

        return CommandCode.SUCCESS;
    }

    private async dumpFile(file: File): Promise<number> {
        try {
            this.eventEmitter.emit(GeneratingFile.name, new GeneratingFile(file));

            let module;
            const autoloads = rechoir.prepare(interpret.jsVariants, file.getFilePath());

            if (autoloads !== true && autoloads.length === 0) {
                console.error('No autoloads found');
            }

            if (file.getExtension() === '.js') {
                module = require(file.getFilePath());
            }

            if (file.getExtension() === '.ts') {
                // console.log('Is TS file');
                // eslint-disable-next-line @typescript-eslint/ban-ts-comment
                // @ts-ignore
                // const attempt = autoloads[autoloads.length - 1];
                // console.log('attempt', attempt.moduleName);
                // require(attempt.moduleName);
                // require('ts-node').register();
                module = await import(file.getFilePath());
            }

            module = module.default ?? module;
            if (typeof module !== 'function') {
                this.eventEmitter.emit(
                    GeneratingFails.name,
                    new GeneratingFails('Module is not callable: got ' + typeof module)
                );
                return CommandCode.FAILURE;
            }

            const blueprint = new Blueprint();
            const presets = new Presets();

            const container = new (class<T extends object> {
                private readonly services: Record<string, T> = {};

                set(id: string, service: T): void {
                    this.services[id] = service;
                }

                has(id: string): boolean {
                    return this.services[id] !== undefined;
                }

                get(id: string): T | null {
                    if (!this.has(id)) {
                        return null;
                    }

                    return this.services[id] as T;
                }
            })();

            container.set('presets', presets);
            container.set('blueprint', blueprint);
            const border = new Border(presets);
            container.set('border', border);
            const color = new Color(presets);
            container.set(Color.name, color);
            const outline = new Outline(presets);
            container.set('outline', outline);
            const spacing = new Spacing(presets);
            container.set('spacing', spacing);
            const typography = new Typography(presets);
            container.set('typography', typography);

            module({
                blueprint,
                presets,
                border,
                color,
                outline,
                spacing,
                typography,
                container,
            });

            blueprint.setPresets(presets);

            const generatedContent = JSON.stringify(blueprint, null, 4);

            const generatedFilePath = file.getFilePath().replace(/.js$/, '');
            fs.writeFileSync(generatedFilePath, generatedContent);
            this.eventEmitter.emit(GeneratedFile.name, new GeneratedFile(new File(generatedFilePath)));

            return CommandCode.SUCCESS;
        } catch (e) {
            console.error(e);
            return CommandCode.FAILURE;
        }
    }
}
