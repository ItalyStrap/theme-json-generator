import {describe, expect, test} from '@jest/globals';
//
import {FilesFinder} from '../../../../src/Infrastructure/Filesystem';
import {Dump} from '../../../../src/Domain/Output';

describe('Dump class', () => {
    test('Dump file', () => {
        const fileFinder = new FilesFinder();
        const dump = new Dump(fileFinder);
        const message = {
            getRootFolder: () => `${process.cwd()}/tests/fixtures/simple`,
            isDryRun: () => false
        };
        // dump.handle(message);
    });
});