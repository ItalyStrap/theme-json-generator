import {EventEmitter} from 'node:events';
//
const interpret = require('interpret');
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
        const files = this.fileFinder.find(message.rootFolder, 'json.js');

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
            // console.log(interpret.jsVariants['.ts'][0]);
            // const obj = require(interpret.jsVariants['.ts'][0])
            // console.log(typeof obj);

            // const filePath = file.getFilePath(); // Ottieni il percorso del file
            let module;

            // if (filePath.endsWith('.ts')) {
            //     require('ts-node').register();
                // check if ts-node is installed
                // module = require(file.getFilePath());
            //     module = await import(filePath);
            // } else {
            //     module = require(filePath);
            // }

            console.log(interpret.jsVariants['.ts']);
            console.log(interpret.jsVariants[file.getExtension()]);
            if (file.getExtension() === '.js') {
                module = require(file.getFilePath());
            }

            module = module.default ?? module;
            if (typeof module !== 'function') {
                console.error('Module is not callable');
                return CommandCode.FAILURE;
            }

            const blueprint = new Blueprint();
            module({blueprint});

            const generatedContent = JSON.stringify(blueprint, null, 2);

            const generatedFilePath = file.getFilePath().replace(/.js$/, '');
            fs.writeFileSync(generatedFilePath, generatedContent);

            return CommandCode.SUCCESS;
        } catch (e) {
            console.error(e);
            return CommandCode.FAILURE;
        }
    }
}
