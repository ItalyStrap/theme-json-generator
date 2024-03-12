import {Command} from 'commander';
//
import {CommandType} from './CommandType';

export class InfoCommand extends Command implements CommandType {
    public configure(): void {
        this.name('info');
        this.description('Show application information');
    }

    public execute(): InfoCommand {
        this.configure();
        this.action(() => {
            console.log('Info logic goes here');
        });

        return this;
    }
}
