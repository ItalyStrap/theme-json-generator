import {EventEmitter} from 'events';
//
import {Command} from 'commander';
//
import {CommandInterface} from './CommandInterface';
//
import {InitMessage} from '../InitMessage';
import {Bus} from '../../bus';
import {
    CreatingEntryPointFile,
    CreatingFile,
    EntryPointExists,
    EntryPointFileCreated,
    NoFileFound,
} from '../../Domain/Output/Events';

type InitOptions = {
    filename: string | undefined;
};

export class InitCommand extends Command implements CommandInterface {
    public constructor(
        private readonly eventEmitter: EventEmitter,
        private readonly bus: Bus<InitMessage, number>
    ) {
        super();
    }

    public configure(): void {
        this.name('init');
        this.description('Initialize theme.json file');
        this.option('-fn, --filename [filename]', 'Init JSON file with a specific filename');
    }

    public execute(): InitCommand {
        this.configure();
        this.action((options: InitOptions) => {
            const message: InitMessage = {
                rootFolder: process.cwd(),
                filename: options.filename || '',
            };

            this.eventEmitter.on(CreatingFile.name, (event: CreatingFile) => {
                console.log(`Creating ${event.file.getFileName()} file`);
            });

            this.eventEmitter.on(NoFileFound.name, (event: NoFileFound) => {
                console.log(`No ${event.filename} file found`);
            });

            this.eventEmitter.on(CreatingEntryPointFile.name, (event: CreatingEntryPointFile) => {
                console.log(`Creating entry point ${event.file.getFileName()} file`);
            });

            this.eventEmitter.on(EntryPointFileCreated.name, (event: EntryPointFileCreated) => {
                console.log(`${event.file.getFileName()} file created`);
            });

            this.eventEmitter.on(EntryPointExists.name, (event: EntryPointExists) => {
                console.log(`${event.file.getFileName()} file already exists`);
            });

            process.exitCode = this.bus.handle(message);
        });

        return this;
    }
}
