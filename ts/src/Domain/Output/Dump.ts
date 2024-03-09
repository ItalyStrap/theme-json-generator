import {File, FilesFinder} from "../../Infrastructure/Filesystem";
import {DumpMessage} from "../../Application";

export class Dump {
    private fileFinder: FilesFinder;

    public constructor(fileFinder: FilesFinder) {
        this.fileFinder = fileFinder;
    }

    public handle(message: DumpMessage): void {
        console.log('Dump logic goes here');
        const files = this.fileFinder.find(message.getRootFolder(), 'js');

        for (const file of files) {
            if (message.isDryRun()) {
                console.log('Dry run mode');
                continue;
            }

            console.log(`Dumping file: ${file.getFileName()}`);

            this.dumpFile(file).then(r => { console.log('done') } );
        }
    }

    private async dumpFile(file: File): Promise<void> {
        try {
            console.log(`Dumping file: ${file.getFileName()}`);



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

        } catch (e) {
            console.error(e);
        }
    }
}