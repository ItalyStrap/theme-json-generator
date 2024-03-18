import {EventEmitter} from 'node:events';
//
import {Command} from 'commander';
import Ajv from 'ajv-draft-04';
//
import {Bus} from '../../bus';
//
import {Validator} from '../JsonSchema';
import {FilesFinder} from '../Filesystem';
//
import {DumpCommand, InfoCommand, InitCommand, ValidateCommand} from '../../Application/Commands';
import {DeleteSchemaJsonMiddleware, CreateSchemaJsonMiddleware} from '../../Application/Commands/Middleware';
import {Info, Init, Validate, Dump} from '../../Domain/Output';

export class Bootstrap {
    run(): void {
        const eventEmitter = new EventEmitter();
        const finder = new FilesFinder();

        const application = new Command();

        const initBus = new Bus(new Init(eventEmitter, finder));
        const initCommand = new InitCommand(eventEmitter, initBus);

        const dumpBus = new Bus(new Dump(eventEmitter, finder));
        const dumpCommand = new DumpCommand(eventEmitter, dumpBus);

        const validateBus = new Bus(
            new Validate(
                eventEmitter,
                finder,
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

        // The 'Delete' middleware must be called before the 'Create' middleware
        // This way if you use the --force option, the schema.json file will be deleted and recreated
        // before the validation process.
        validateBus.addMiddleware(new DeleteSchemaJsonMiddleware());
        validateBus.addMiddleware(new CreateSchemaJsonMiddleware());

        const validateCommand = new ValidateCommand(eventEmitter, validateBus);

        const infoBus = new Bus(new Info(eventEmitter, finder));
        const infoCommand = new InfoCommand(eventEmitter, infoBus);

        // https://github.com/tj/commander.js/blob/83c3f4e391754d2f80b179acc4bccc2d4d0c863d/examples/nestedCommands.js
        application.addCommand(initCommand.execute());
        application.addCommand(dumpCommand.execute());
        application.addCommand(validateCommand.execute());
        application.addCommand(infoCommand.execute());

        application.parse(process.argv);
    }
}
