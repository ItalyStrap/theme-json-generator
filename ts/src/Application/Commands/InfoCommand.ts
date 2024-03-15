import {EventEmitter} from 'events';
//
import {Command} from 'commander';
//
import {Bus} from '../../../bus';
//
import {CommandType} from './CommandType';
import {InfoMessage} from '../InfoMessage';

export class InfoCommand extends Command implements CommandType {
    public constructor(
        private readonly eventEmitter: EventEmitter,
        private readonly bus: Bus<InfoMessage, number>
    ) {
        super();
    }

    public configure(): void {
        this.name('info');
        this.description('Show application information');
    }

    public execute(): InfoCommand {
        this.configure();
        this.action(() => {
            const message: InfoMessage = {
                rootFolder: process.cwd(),
            };

            this.bus.handle(message);
        });

        return this;
    }
}
