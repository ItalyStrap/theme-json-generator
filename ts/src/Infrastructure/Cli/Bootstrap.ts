import {Command} from 'commander';
//
import {DumpCommand, InfoCommand, InitCommand, ValidateCommand} from "../../Application/Commands";
import {Init} from "../../Domain/Output";
import {FilesFinder} from "../Filesystem";

export class Bootstrap {
    run(): void {
        const program = new Command();
        const initCommand = new InitCommand(new Init(new FilesFinder()));
        const dumpCommand = new DumpCommand();
        const validateCommand = new ValidateCommand();
        const infoCommand = new InfoCommand();

        // https://github.com/tj/commander.js/blob/83c3f4e391754d2f80b179acc4bccc2d4d0c863d/examples/nestedCommands.js
        program.addCommand(initCommand.execute());
        program.addCommand(dumpCommand.execute());
        program.addCommand(validateCommand.execute());
        program.addCommand(infoCommand.execute());

        program.parse(process.argv);
    }
}