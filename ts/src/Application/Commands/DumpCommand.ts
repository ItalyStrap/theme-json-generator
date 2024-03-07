import {Command} from "commander";
//
import {CommandType} from "./CommandType";
//

export class DumpCommand extends Command implements CommandType{
    public configure(): void {
        this.name('dump');
        this.description('Dump the application state');
    }

    public execute(): DumpCommand {
        this.configure();
        this.action(() => {
            console.log('Dump logic goes here');
        });

        return this;
    }
}