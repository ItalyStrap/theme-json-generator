import {EventEmitter} from 'events';
//
import {HandlerInterface} from '../../../bus';
//
import {ValidateMessage} from '../../Application';
import {File, FilesFinder} from '../../Infrastructure/Filesystem';
//
import {ValidatedFails, ValidatingFile, ValidFile} from './Events';

export class Validate implements HandlerInterface<ValidateMessage, number> {
    public constructor(
        private eventEmitter: EventEmitter,
        private fileFinder: FilesFinder
    ) {}

    public handle(message: ValidateMessage): number {
        const files = this.fileFinder.find(message.getRootFolder(), 'json');

        for (const file of files) {
            this.eventEmitter.emit(ValidatingFile.name, file);
            this.validateJsonFile(file, message.getFileSchema());
            /**
             * @todo Implementing scss validation
             */
        }

        return 0;
    }

    private validateJsonFile(file: File, schema: File): void {
        // @todo do some validation here

        if (true) {
            this.eventEmitter.emit(ValidatedFails.name, file);
        }

        this.eventEmitter.emit(ValidFile.name, file);
    }
}
