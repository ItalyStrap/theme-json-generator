import {jest, describe, expect, test} from '@jest/globals';
//
import * as path from 'path';
import {EventEmitter} from 'events';
import Ajv from 'ajv-draft-04';
//
import {File, FilesFinder} from '../../../../src/Infrastructure/Filesystem';
import {Validator} from '../../../../src/Infrastructure/JsonSchema';
//
import {Validate} from '../../../../src/Domain/Output';
import {ValidateMessage} from '../../../../src/Application';

jest.mock('ajv-draft-04', () => {
    return jest.fn().mockImplementation(() => {
        return {
            compile: jest.fn().mockReturnValue(() => {
                return true;
            }),
            errors: [],
        };
    });
});

describe('Validate class', () => {
    test('Dump file', () => {
        const eventEmitter = new EventEmitter();
        const fileFinder = new FilesFinder();
        const validator = new Validator(new Ajv());
        const dump = new Validate(eventEmitter, fileFinder, validator);
        const rootFolder = path.join(process.cwd(), 'tests', 'fixtures', 'with-js-entrypoint');
        const message: ValidateMessage = {
            rootFolder: rootFolder,
            fileSchema: new File(path.join(rootFolder, 'theme.schema.json')),
            force: false,
        };
        const isHandled = dump.handle(message);
        expect(isHandled).toBe(0);
    });
});
