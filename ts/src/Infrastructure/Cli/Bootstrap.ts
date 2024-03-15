import {EventEmitter} from 'events';
//
import {Command} from 'commander';
import Ajv from 'ajv-draft-04';
//
import {Bus} from '../../../bus';
//
import {Validator} from '../JsonSchema';
import {FilesFinder} from '../Filesystem';
//
import {
    DumpCommand,
    InfoCommand,
    InitCommand,
    ValidateCommand,
} from '../../Application/Commands';
import {
    DeleteSchemaJsonMiddleware,
    SchemaJsonMiddleware,
} from '../../Application/Commands/Middleware';
import {Init, Validate, Dump} from '../../Domain/Output';

export class Bootstrap {
    run(): void {
        const eventEmitter = new EventEmitter();

        const validateBus = new Bus(
            new Validate(
                eventEmitter,
                new FilesFinder(),
                new Validator(
                    new Ajv({
                        allErrors: true,
                        strict: true,
                        verbose: true,
                        allowMatchingProperties: true,
                        allowUnionTypes: true,
                    })
                )
            )
        );
        validateBus.addMiddleware(new SchemaJsonMiddleware());
        validateBus.addMiddleware(new DeleteSchemaJsonMiddleware());

        const application = new Command();
        const initCommand = new InitCommand(new Init(new FilesFinder()));
        const dumpCommand = new DumpCommand(new Dump(new FilesFinder()));
        const validateCommand = new ValidateCommand(eventEmitter, validateBus);
        const infoCommand = new InfoCommand();

        // https://github.com/tj/commander.js/blob/83c3f4e391754d2f80b179acc4bccc2d4d0c863d/examples/nestedCommands.js
        application.addCommand(initCommand.execute());
        application.addCommand(dumpCommand.execute());
        application.addCommand(validateCommand.execute());
        application.addCommand(infoCommand.execute());

        application.parse(process.argv);
    }
}
