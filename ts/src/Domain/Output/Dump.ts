import {FilesFinder} from "../../Infrastructure/Filesystem";
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
            console.log(`Dumping file: ${file.getFileName()}`);
        }
    }
}