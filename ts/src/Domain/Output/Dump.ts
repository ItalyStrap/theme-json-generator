const interpret = require('interpret');
import * as fs from 'fs';
//
import {File, FilesFinder} from '../../Infrastructure/Filesystem';
import {DumpMessage} from '../../Application';
import {Config} from '../../Application/Config';

export class Dump {
    private fileFinder: FilesFinder;

    public constructor(fileFinder: FilesFinder) {
        this.fileFinder = fileFinder;
    }

    public handle(message: DumpMessage): number {
        const files = this.fileFinder.find(message.getRootFolder(), 'json.js');

        for (const file of files) {
            console.log(`Dumping file: ${file.getFileName()}`);

            if (message.isDryRun()) {
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

        return 0;
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

            const config = new Config();
            if (typeof module !== 'function') {
                console.error('Module is not callable');
                return 1;
            }

            module(config);

            const generatedContent = JSON.stringify(config, null, 2);

            const generatedFilePath = file.getFilePath().replace(/.js$/, '');
            fs.writeFileSync(generatedFilePath, generatedContent);

            return 0;
        } catch (e) {
            console.error(e);
            return 1;
        }
    }
}
