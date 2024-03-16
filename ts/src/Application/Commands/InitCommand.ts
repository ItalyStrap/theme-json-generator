import {EventEmitter} from 'events';
//
import {Command} from 'commander';
//
import {CommandInterface} from './CommandInterface';
//
import {InitMessage} from '../InitMessage';
import {Init} from '../../Domain/Output';

export class InitCommand extends Command implements CommandInterface {
    public constructor(
        private readonly eventEmitter: EventEmitter,
        private readonly init: Init
    ) {
        super();
    }

    public configure(): void {
        this.name('init');
        this.description('Initialize theme.json file');
        this.option(
            '-s, --styles [styles]',
            'Init JSON file inside styles folder'
        );
    }

    public execute(): InitCommand {
        this.configure();
        this.action((options: InitMessage) => {
            const message: InitMessage = {
                rootFolder: process.cwd(),
                stylesOption: options.stylesOption || '',
            };

            process.exitCode = this.init.handle(message);
        });

        return this;
    }
}
