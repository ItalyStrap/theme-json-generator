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

export class Dump implements HandlerInterface<InfoMessage, number> {
    public constructor(
        private readonly eventEmitter: EventEmitter,
        private readonly fileFinder: FilesFinder
    ) {}

    public handle(message: DumpMessage): number {
        const files = this.fileFinder.find(message.rootFolder, `json.${message.ext}`);

        for (const file of files) {
            console.log(`Dumping file: ${file.getFileName()}`);

            if (message.dryRun) {
                console.log('Dry run mode');
                continue;
            }

            this.dumpFile(file)
                .then((r) => {
                    console.log('done');
                    console.log(r);
                })
                .catch((e) => {
                    console.error(e);
                });
        }

        return CommandCode.SUCCESS;
    }

    private async dumpFile(file: File): Promise<number> {
        try {
            let module;
            console.log('file', file);
            const autoloads = rechoir.prepare(interpret.jsVariants, file.getFilePath());
            console.log('rechoir', autoloads);

            if (file.getExtension() === '.js') {
                module = require(file.getFilePath());
            }

            if (file.getExtension() === '.ts') {
                console.log('Is TS file');
                // @ts-ignore
                // const attempt = autoloads[autoloads.length - 1];
                // console.log('attempt', attempt.moduleName);
                // require(attempt.moduleName);
                // require('ts-node').register();
                module = await import(file.getFilePath());
            }

            // console.log(module);

            module = module.default ?? module;
            if (typeof module !== 'function') {
                console.error('Module is not callable');
                return CommandCode.FAILURE;
            }

            const blueprint = new Blueprint();
            module({blueprint});

            const generatedContent = JSON.stringify(blueprint, null, 2);

            const generatedFilePath = file.getFilePath().replace(/.js$/, '');
            // fs.writeFileSync(generatedFilePath, generatedContent);

            return CommandCode.SUCCESS;
        } catch (e) {
            console.error(e);
            return CommandCode.FAILURE;
        }
    }
}
