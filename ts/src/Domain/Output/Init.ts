import * as fs from 'fs';
import {EventEmitter} from 'node:events';
//
import {HandlerInterface} from '../../bus';
//
import {InitMessage} from '../../Application';
import {InfoMessage} from '../../Application/InfoMessage';
//
import {File, FilesFinder} from '../../Infrastructure/Filesystem';
import path from 'path';
import {CommandCode} from '../../Application/Commands';
import {CreatingEntryPointFile, CreatingFile, EntryPointExists, EntryPointFileCreated, NoFileFound} from './Events';

export class Init implements HandlerInterface<InfoMessage, number> {
    public constructor(
        private readonly eventEmitter: EventEmitter,
        private readonly fileFinder: FilesFinder
    ) {}

    public handle(message: InitMessage): number {
        const messageFilename = message.filename ?? '';
        if (messageFilename !== '') {
            const filename = messageFilename.replace('.json', '');
            this.generateJsonFileWithEntryPoint(message, path.join('styles', `${filename}.json`));
            return CommandCode.SUCCESS;
        }

        const files = this.fileFinder.find(message.rootFolder, 'json');

        let filesFound = 0;
        for (const file of files) {
            filesFound++;
            this.generateEntryPointDataFile(file);
        }

        if (filesFound === 0) {
            this.eventEmitter.emit(NoFileFound.name, new NoFileFound('theme.json'));
            this.generateJsonFileWithEntryPoint(message, 'theme.json');
        }

        return CommandCode.SUCCESS;
    }

    private generateJsonFileWithEntryPoint(message: InitMessage, fileName: string): void {
        const file = new File(path.join(message.rootFolder, fileName));
        this.eventEmitter.emit(CreatingFile.name, new CreatingFile(file));
        fs.writeFileSync(file.toString(), '{}');
        this.generateEntryPointDataFile(file);
    }

    private generateEntryPointDataFile(file: File): void {
        const entryPointFile = new File(file.toString() + '.js');
        this.eventEmitter.emit(CreatingEntryPointFile.name, new CreatingEntryPointFile(entryPointFile));
        if (!entryPointFile.exists()) {
            const data = fs.readFileSync(file.getFilePath(), 'utf8');
            fs.writeFileSync(entryPointFile.toString(), 'module.exports = ' + data);
            this.eventEmitter.emit(EntryPointFileCreated.name, new EntryPointFileCreated(entryPointFile));
            return;
        }

        this.eventEmitter.emit(EntryPointExists.name, new EntryPointExists(entryPointFile));
    }
}
