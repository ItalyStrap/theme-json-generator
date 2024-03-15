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
import {
    ValidatingFile,
    ValidatedFails,
    ValidFile,
} from '../../Domain/Output/Events';

type ValidateOptions = {
    force: boolean | undefined;
};

export class ValidateCommand extends Command implements CommandType {
    public constructor(
        private readonly eventEmitter: EventEmitter,
        private readonly bus: Bus<ValidateMessage, number>
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

            this.eventEmitter.on(
                ValidatingFile.name,
                (event: ValidatingFile) => {
                    console.log(`Validating ${event.file.getFileName()}`);
                }
            );

            this.eventEmitter.on(
                ValidatedFails.name,
                (event: ValidatedFails) => {
                    console.log(`Fail to validate ${event.file.getFileName()}`);
                    if (!event.errors || event.errors.length === 0) {
                        console.log('No errors found');
                        return;
                    }

                    console.log('Errors found:');
                    for (const error of event.errors) {
                        const instancePath =
                            error.instancePath.replace(/\//g, '.') || '';
                        console.log(`- ${instancePath} ${error.message}`);
                    }
                }
            );

            this.eventEmitter.on(ValidFile.name, (event: ValidFile) => {
                console.log(`Valid ${event.file.getFileName()}`);
            });

            const message: ValidateMessage = {
                rootFolder,
                fileSchema,
                force: options.force || false,
            };

            this.bus.handle(message);
        });

        return this;
    }
}
