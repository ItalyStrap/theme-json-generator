import {describe, expect, test} from '@jest/globals';
import * as fs from "fs";
//
import {File} from '../../../../src/Infrastructure/Filesystem';

describe('File class', () => {
    test('File does not exist', () => {
        let file = new File('does-not-exist.json');
        expect(file.exists()).toBe(false);
    });

    test('File exists', () => {
        const path = `${process.cwd()}/tests/fixtures/simple/theme.json`;
        const file = new File(path);
        expect(file.exists()).toBe(true);
    });

    test('Is file', () => {
        const path = `${process.cwd()}/tests/fixtures/simple/theme.json`;
        const file = new File(path);
        expect(file.isFile()).toBe(true);
        expect(file.isDir()).toBe(false);
    });

    test('Is stringable', () => {
        const path = `${process.cwd()}/tests/fixtures/simple/theme.json`;
        const file = new File(path);
        const actual = `${file}`;
        expect(actual).toBe(path);
    });

    test('File content', () => {
        const path = `${process.cwd()}/tests/fixtures/simple/theme.json`;
        const file = new File(path);
        expect(file.toString()).toBe(path);
        const content = fs.readFileSync(path, 'utf-8');
        expect(content).toBe('{"version": 2}');
    });

    test('Get Short Name', () => {
        const file = new File(`${process.cwd()}/tests/fixtures/simple/theme.json`);
        expect(file.getShortName()).toBe('theme');
    });

    test('Get Base Name', () => {
        const file = new File(`${process.cwd()}/tests/fixtures/simple/theme.json`);
        expect(file.getBaseName()).toBe('theme.json');
        expect(file.getBaseName('.json')).toBe('theme');
    });

    test('Get file name', () => {
        const file = new File('folder/theme.json');
        expect(file.getFileName()).toBe('theme.json');
    });

    test('Get extension', () => {
        const file = new File('theme.json');
        expect(file.getExtension()).toBe('.json');
    });
});
