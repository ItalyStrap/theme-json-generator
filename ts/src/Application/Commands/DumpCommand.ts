import {EventEmitter} from 'events';
//
import {Command} from 'commander';
//
import {CommandInterface} from './CommandInterface';
import {Dump} from '../../Domain/Output';
import {DumpMessage} from '../DumpMessage';
//

export class DumpCommand extends Command implements CommandInterface {
    public constructor(
        private readonly eventEmitter: EventEmitter,
        private readonly dump: Dump
    ) {
        super();
    }

    public configure(): void {
        this.name('dump');
        this.description('Dump the application state');
        this.option('-d, --dry-run', 'Dry run mode');
    }

    public execute(): DumpCommand {
        this.configure();
        this.action((options: DumpMessage) => {
            const message: DumpMessage = {
                rootFolder: process.cwd(),
                dryRun: options.dryRun || false,
            };

            process.exitCode = this.dump.handle(message);
        });

        return this;
    }
}
