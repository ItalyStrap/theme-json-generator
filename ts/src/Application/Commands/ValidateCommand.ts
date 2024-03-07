import {Command} from "commander";
//
import {CommandType} from "./CommandType";
//

export class ValidateCommand extends Command implements CommandType {
    public configure(): void {
        this.name('validate');
        this.description('Validate the application state');
    }

    public execute(): ValidateCommand {
        this.configure();
        this.action(() => {
            console.log('Validation logic goes here');
        });

        return this;
    }
}