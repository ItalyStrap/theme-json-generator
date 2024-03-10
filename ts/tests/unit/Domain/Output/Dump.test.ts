import {describe, expect, test} from '@jest/globals';
//
import {FilesFinder} from '../../../../src/Infrastructure/Filesystem';
import {Dump} from '../../../../src/Domain/Output';
import {DumpMessage} from "../../../../src/Application";
import {Blueprint, Config} from "../../../../src/Application/Config";

describe('Dump class', () => {
    test('Dump message', () => {
        const message = new DumpMessage('rootFolder', false);
        expect(message.getRootFolder()).toBe('rootFolder');
        expect(message.isDryRun()).toBe(false);
    });

    test('Dump message dry run', () => {
        const message = new DumpMessage('rootFolder', true);
        expect(message.isDryRun()).toBe(true);
    });

    test('Dump file', () => {
        const fileFinder = new FilesFinder();
        const dump = new Dump(fileFinder);
        const rootFolder = `${process.cwd()}/tests/fixtures/simple`;
        const message = new DumpMessage(rootFolder, false);
        dump.handle(message);
    });
});