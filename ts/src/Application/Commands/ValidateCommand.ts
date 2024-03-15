import {EventEmitter} from 'events';
import path from 'path';
//
import {Command} from 'commander';
import {ErrorObject} from 'ajv-draft-04';
//
import {Bus} from '../../../bus';
//
import {CommandType} from './CommandType';
//
import {ValidateMessage} from '../ValidateMessage';
import {File} from '../../Infrastructure/Filesystem';
import {ValidatedFails, ValidatingFile} from '../../Domain/Output/Events';
import {ValidFile} from '../../Domain/Output/Events';

type ValidateOptions = {
    force: boolean | undefined;
};

type ValidateType<T> = {
    rootFolder: string;
    fileSchema: File;
    options: T;
};

export class ValidateCommand extends Command implements CommandType {
    public constructor(
        private eventEmitter: EventEmitter,
        private bus: Bus<ValidateMessage, number>
    ) {
        super();
    }

    public configure(): void {
        this.name('validate');
        this.description('Validate the application state');
        this.option(
            '-f, --force',
            'Force to regenerate the theme.schema.json file'
        );
    }

    public execute(): ValidateCommand {
        this.configure();
        this.action((options: ValidateOptions) => {
            const rootFolder = process.cwd();
            const fileSchema = new File(
                path.join(rootFolder, 'theme.schema.json')
            );

            this.eventEmitter.on(ValidatingFile.name, (file: File) => {
                console.log(`Validating ${file.getFileName()}`);
            });

            this.eventEmitter.on(ValidFile.name, (file: File) => {
                console.log(`Valid ${file.getFileName()}`);
            });

            this.eventEmitter.on(
                ValidatedFails.name,
                (file: File, errors: ErrorObject[] | null | undefined) => {
                    console.log(`Fail to validate ${file.getFileName()}`);
                    if (!errors || errors.length === 0) {
                        console.log('No errors found');
                        return;
                    }

                    console.log('Errors found:');
                    for (const error of errors) {
                        const instancePath =
                            error.instancePath.replace(/\//g, '.') || '';
                        console.log(`- ${instancePath} ${error.message}`);
                    }
                }
            );

            const testValidate: ValidateType<ValidateOptions> = {
                rootFolder,
                fileSchema,
                options,
            };

            const message = new ValidateMessage(
                rootFolder,
                fileSchema,
                options.force || false
            );
            this.bus.handle(message);
        });

        return this;
    }
}
