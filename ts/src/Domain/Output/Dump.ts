import {File, FilesFinder} from "../../Infrastructure/Filesystem";
import {DumpMessage} from "../../Application";
import {Config} from "../../Application/Config";

export class Dump {
    private fileFinder: FilesFinder;

    public constructor(fileFinder: FilesFinder) {
        this.fileFinder = fileFinder;
    }

    public handle(message: DumpMessage): void {
        const files = this.fileFinder.find(message.getRootFolder(), 'json.js');

        for (const file of files) {
            console.log(`Dumping file: ${file.getFileName()}`);

            if (message.isDryRun()) {
                console.log('Dry run mode');
                continue;
            }

            this.dumpFile(file)
                .then(r => {
                    // console.log('done')
                    // console.log(r)
                } )
                .catch(e => { console.error(e) } );
        }
    }

    private async dumpFile(file: File): Promise<number> {
        try {
            console.log(`Async Dump file: ${file.getFileName()}`);
            console.log(file.getFilePath());
            // const module = await import(file.getFilePath());
            // console.log(module.default);
            const module = require(file.getFilePath());

            const config = new Config();
            module(config);
            console.log(JSON.stringify(config, null, 2));

            // console.log('blueprint');
            // console.log(blueprint.version);
            // console.log(JSON.stringify(blueprint, null, 2));

            // console.log(file.getFilePath());
            // // const module = await import(file.getFilePath());
            // const module = await import(`${file.getFilePath()}.mjs`);
            // // const module = require(`${file.getFilePath()}.js`);
            // // check if is callable
            // console.log(module.default);
            // if (typeof module === 'function') {
            //     console.log('is callable');
            //     console.log(module('ciao'));
            // }
            // // console.log(module.default);
            // console.log(module.default('ciao'));

            return 0;
        } catch (e) {
            console.error(e);
            return 1;
        }
    }
}