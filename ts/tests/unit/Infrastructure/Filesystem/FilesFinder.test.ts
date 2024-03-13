import {describe, expect, test} from '@jest/globals';
import * as fs from 'fs';
import * as path from 'path';
//
import {FilesFinder} from '../../../../src/Infrastructure/Filesystem';

describe('FilesFinder class', () => {
    test('Find files', () => {
        const fileFinder = new FilesFinder();
        const rootFolder = path.join(
            process.cwd(),
            'tests',
            'fixtures',
            'compound'
        );
        const files = fileFinder.find(rootFolder, 'json.js');
        const actual = Array.from(files).map((file) => file.toString());
        const expected = [
            path.join(rootFolder, 'theme.json.js'),
            path.join(rootFolder, 'styles', 'style01.json.js'),
        ];
        expect(actual).toEqual(expected);
    });
});
