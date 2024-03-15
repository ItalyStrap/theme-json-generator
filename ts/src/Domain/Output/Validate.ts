import * as fs from 'fs';
import {EventEmitter} from 'node:events';
//
import {HandlerInterface} from '../../../bus';
//
import {Validator} from '../../Infrastructure/JsonSchema';
import {File, FilesFinder} from '../../Infrastructure/Filesystem';
//
import {ValidateMessage} from '../../Application';
//
import {VALID_FILE, VALIDATED_FAILS, VALIDATING_FILE} from './Events';

export class Validate implements HandlerInterface<ValidateMessage, number> {
    public constructor(
        private eventEmitter: EventEmitter,
        private fileFinder: FilesFinder,
        private validator: Validator
    ) {}

    public handle(message: ValidateMessage): number {
        const files = this.fileFinder.find(message.rootFolder, 'json');

        for (const file of files) {
            this.eventEmitter.emit(VALIDATING_FILE, file);
            this.validateJsonFile(file, message.fileSchema);
            this.validator.reset();
            /**
             * @todo Implementing scss validation
             */
        }

        return 0;
    }

    private validateJsonFile(file: File, schema: File): void {
        let data = fs.readFileSync(file.getFilePath(), 'utf8');
        data = JSON.parse(data);

        let jsonSchema = fs.readFileSync(schema.getFilePath(), 'utf8');
        jsonSchema = JSON.parse(jsonSchema);

        this.validator.validate(data, jsonSchema);

        if (!this.validator.isValid()) {
            this.eventEmitter.emit(
                VALIDATED_FAILS,
                file,
                this.validator.getErrors()
            );
            return;
        }

        this.eventEmitter.emit(VALID_FILE, file);
    }
}
