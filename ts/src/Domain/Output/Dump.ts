const interpret = require('interpret');
//
import * as fs from 'fs';
//
import {HandlerInterface} from '../../bus';
//
import {File, FilesFinder} from '../../Infrastructure/Filesystem';
import {DumpMessage} from '../../Application';
import {CommandCode} from '../../Application/Commands';
import {Blueprint, Config} from '../../Application/Config';
import {InfoMessage} from '../../Application/InfoMessage';

export class Dump implements HandlerInterface<InfoMessage, number> {
    private fileFinder: FilesFinder;

    public constructor(fileFinder: FilesFinder) {
        this.fileFinder = fileFinder;
    }

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
                    // console.log('done')
                    // console.log(r)
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

            const filePath = file.getFilePath(); // Ottieni il percorso del file
            let module;

            if (filePath.endsWith('.ts')) {
                require('ts-node').register();
                module = await import(filePath);
            } else {
                module = require(filePath);
            }

            module = module.default || module;
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
