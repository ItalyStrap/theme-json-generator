import {EventEmitter} from 'events';
//
import {HandlerInterface} from '../../bus';
//
import {File, FilesFinder} from '../../Infrastructure/Filesystem';
//
import {InfoMessage} from '../../Application/InfoMessage';

export class Info implements HandlerInterface<InfoMessage, number> {
    public constructor(
        private eventEmitter: EventEmitter,
        private fileFinder: FilesFinder
    ) {}

    public handle(message: InfoMessage): number {
        const files = this.fileFinder.find(message.rootFolder, 'json');

        for (const file of files) {
            console.log(file.getFilePath());
        }

        return 0;
    }
}
