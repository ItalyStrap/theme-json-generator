import {Command} from "commander";
//
import {CommandType} from "./CommandType";
//
import {ValidateMessage} from "../ValidateMessage";
import {Validate} from "../../Domain/Output";

export class ValidateCommand extends Command implements CommandType {
    private validate: Validate;

    public constructor(validate: Validate) {
        super();
        this.validate = validate;
    }

    public configure(): void {
        this.name('validate');
        this.description('Validate the application state');
    }

    public execute(): ValidateCommand {
        this.configure();
        this.action(() => {
            const rootFolder = process.cwd();

            const message = new ValidateMessage(rootFolder);
            this.validate.handle(message);
        });

        return this;
    }
}