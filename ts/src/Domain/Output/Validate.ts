import * as fs from 'fs';
import {EventEmitter} from 'node:events';
//
import {HandlerInterface} from '../../bus';
//
import {Validator} from '../../Infrastructure/JsonSchema';
import {File, FilesFinder} from '../../Infrastructure/Filesystem';
//
import {ValidateMessage} from '../../Application';
import {CommandCode} from '../../Application/Commands';
//
import {ValidatingFile, ValidatedFails, ValidFile} from './Events';

export class Validate implements HandlerInterface<ValidateMessage, number> {
    public constructor(
        private eventEmitter: EventEmitter,
        private fileFinder: FilesFinder,
        private validator: Validator
    ) {}

    public handle(message: ValidateMessage): number {
        const files = this.fileFinder.find(message.rootFolder, 'json');

        for (const file of files) {
            this.eventEmitter.emit(ValidatingFile.name, new ValidatingFile(file));
            this.validateJsonFile(file, message.fileSchema);
            this.validator.reset();
            /**
             * @todo Implementing scss validation
             */
        }

        return CommandCode.SUCCESS;
    }

    private validateJsonFile(file: File, schema: File): void {
        const data = fs.readFileSync(file.getFilePath(), 'utf8');
        const jsonSchema = fs.readFileSync(schema.getFilePath(), 'utf8');

        this.validator.validate(JSON.parse(data), JSON.parse(jsonSchema));

        if (!this.validator.isValid()) {
            this.eventEmitter.emit(ValidatedFails.name, new ValidatedFails(file, this.validator.getErrors()));
            return;
        }

        this.eventEmitter.emit(ValidFile.name, new ValidFile(file));
    }
}
