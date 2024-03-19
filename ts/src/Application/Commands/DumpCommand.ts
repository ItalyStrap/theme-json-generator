import {EventEmitter} from 'events';
//
import {Command} from 'commander';
//
import {CommandInterface} from './CommandInterface';
import {DumpMessage} from '../DumpMessage';
import {Bus} from '../../bus';
import {
    DryRunMode,
    GeneratedFile,
    GeneratingFails,
    GeneratingFile,
    NoFileFound,
    ValidationSubProcess,
} from '../../Domain/Output/Events';
//

type DumpOptions = {
    dryRun: boolean | undefined;
    validate: boolean | undefined;
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
        this.option('--validate', 'Validate the configuration file');
        this.option('--ext <ext>', 'File extension to dump', 'js');
    }

    public execute(): DumpCommand {
        this.configure();
        this.action((options: DumpOptions) => {
            const message: DumpMessage = {
                rootFolder: process.cwd(),
                dryRun: options.dryRun ?? false,
                validate: options.validate ?? false,
                ext: options.ext ?? 'js',
            };

            this.eventEmitter.on(DryRunMode.name, () => {
                console.log('Dry run mode');
            });

            this.eventEmitter.on(GeneratingFile.name, (event: GeneratingFile) => {
                console.log(`Generating file: ${event.file.getFileName()}`);
            });

            this.eventEmitter.on(GeneratedFile.name, (event: GeneratedFile) => {
                console.log(`Generated file: ${event.file.getFileName()}`);
            });

            this.eventEmitter.on(GeneratingFails.name, (event: GeneratingFails) => {
                console.error(`${event.message}`);
            });

            this.eventEmitter.on(NoFileFound.name, (event: NoFileFound) => {
                console.error(`${event.filenameOrMessage}`);
            });

            this.eventEmitter.on(ValidationSubProcess.name, (event: ValidationSubProcess) => {
                console.log(event.message);
            });

            process.exitCode = this.bus.handle(message);
        });

        return this;
    }
}
