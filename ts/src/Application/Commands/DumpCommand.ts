import {EventEmitter} from 'events';
//
import {Command} from 'commander';
//
import {CommandType} from './CommandType';
import {Dump} from '../../Domain/Output';
import {DumpMessage} from '../DumpMessage';
//

export class DumpCommand extends Command implements CommandType {
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
        this.action(() => {
            const rootFolder = process.cwd();

            // const message = new DumpMessage(rootFolder, this.opts()?.dryRun);
            const message: DumpMessage = {
                rootFolder: rootFolder,
                dryRun: this.opts()?.dryRun,
            };

            this.dump.handle(message);
        });

        return this;
    }
}
