import {describe, expect, test} from '@jest/globals';
//
import {EventEmitter} from 'events';
//
import {FilesFinder} from '../../../../src/Infrastructure/Filesystem';
import {Dump} from '../../../../src/Domain/Output';
import {DumpMessage} from '../../../../src/Application';

describe('Dump class', () => {
    test('Dump message', () => {
        const message: DumpMessage = {
            rootFolder: 'rootFolder',
            dryRun: false,
        };
        expect(message.rootFolder).toBe('rootFolder');
        expect(message.dryRun).toBe(false);
    });

    test('Dump message dry run', () => {
        const message: DumpMessage = {
            rootFolder: 'rootFolder',
            dryRun: true,
        };
        expect(message.rootFolder).toBe('rootFolder');
        expect(message.dryRun).toBe(true);
    });

    test('Dump file', () => {
        const fileFinder = new FilesFinder();
        const eventEmitter = new EventEmitter();
        const dump = new Dump(eventEmitter, fileFinder);
        const rootFolder = `${process.cwd()}/tests/fixtures/with-js-entrypoint`;
        const message: DumpMessage = {
            rootFolder: rootFolder,
            dryRun: false,
        };
        const isHandled = dump.handle(message);
        // expect(isHandled).toBe(0);
    });

    test('Dump ts file', () => {
        const fileFinder = new FilesFinder();
        const eventEmitter = new EventEmitter();
        const dump = new Dump(eventEmitter, fileFinder);
        const rootFolder = `${process.cwd()}/tests/fixtures/type`;
        const message: DumpMessage = {
            rootFolder: rootFolder,
            dryRun: false,
            // ext: 'ts',
        };
        const isHandled = dump.handle(message);
        // expect(isHandled).toBe(0);
    });
});
