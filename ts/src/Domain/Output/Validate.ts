import {HandlerInterface} from "@italystrap/bus";
//
import {ValidateMessage} from '../../Application';
import {FilesFinder} from '../../Infrastructure/Filesystem';

export class Validate {
    private fileFinder: FilesFinder;

    public constructor(fileFinder: FilesFinder) {
        this.fileFinder = fileFinder;
    }

    public handle(message: ValidateMessage): number {
        const files = this.fileFinder.find(message.getRootFolder(), 'json');

        for (const file of files) {
            console.log(`Validating file: ${file.getFileName()}`);
        }

        return 0;
    }
}