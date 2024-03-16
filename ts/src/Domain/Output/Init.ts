import {InitMessage} from '../../Application';
import {File, FilesFinder} from '../../Infrastructure/Filesystem';

export class Init {
    private fileFinder: FilesFinder;

    public constructor(fileFinder: FilesFinder) {
        this.fileFinder = fileFinder;
    }

    public handle(message: InitMessage): number {
        console.log('Initialization logic goes here');
        const files = this.fileFinder.find(message.rootFolder, 'json');

        for (const file of files) {
            this.generateEntryPointDataFile(file);
        }

        return 0;
    }

    private generateEntryPointDataFile(file: File): void {
        console.log('Generating entry point data file');
    }
}
