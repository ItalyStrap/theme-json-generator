import {EventEmitter} from 'events';
//
import {Command} from 'commander';
//
import {CommandInterface} from './CommandInterface';
import {DumpMessage} from '../DumpMessage';
import {Bus} from '../../bus';
//

type DumpOptions = {
    dryRun: boolean | undefined;
    ext: string | undefined;
};

export class DumpCommand extends Command implements CommandInterface {
    public constructor(
        private readonly eventEmitter: EventEmitter,
        private readonly bus: Bus<DumpMessage, number>
    ) {
        super();
    }

    public configure(): void {
        this.name('dump');
        this.description('Dump the application state');
        this.option('-d, --dry-run', 'Dry run mode');
        this.option('--ext <ext>', 'File extension to dump', 'js');
    }

    public execute(): DumpCommand {
        this.configure();
        this.action((options: DumpOptions) => {
            const message: DumpMessage = {
                rootFolder: process.cwd(),
                dryRun: options.dryRun ?? false,
                ext: options.ext ?? 'js',
            };

            process.exitCode = this.bus.handle(message);
        });

        return this;
    }
}
