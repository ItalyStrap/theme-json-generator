import {describe, expect, test} from '@jest/globals';
//
import * as path from 'path';
//
import {FilesFinder} from '../../../../src/Infrastructure/Filesystem';
import {Validate} from '../../../../src/Domain/Output';
import {ValidateMessage} from '../../../../src/Application';

describe('Validate class', () => {
    test('Dump file', () => {
        const fileFinder = new FilesFinder();
        const dump = new Validate(fileFinder);
        const rootFolder = path.join(
            process.cwd(),
            'tests',
            'fixtures',
            'with-js-entrypoint'
        );
        const message = new ValidateMessage(rootFolder);
        const isHandled = dump.handle(message);
        expect(isHandled).toBe(0);
    });
});
