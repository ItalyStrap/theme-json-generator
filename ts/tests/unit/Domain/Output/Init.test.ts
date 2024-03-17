import {beforeEach, describe, expect, jest, test} from '@jest/globals';
//
import * as fs from 'fs';
import * as path from 'path';
import {EventEmitter} from 'events';
//
import {FilesFinder} from '../../../../src/Infrastructure/Filesystem';
import {Init} from '../../../../src/Domain/Output';
import {InitMessage} from '../../../../src/Application';
//
// Mock the EventEmitter class
jest.mock('events');

beforeEach(() => {
    jest.resetModules();
});

const makeInstance = () => {
    const fileFinder = new FilesFinder();
    // const eventEmitter = new EventEmitter();

    class MyEmitter extends EventEmitter {
        constructor() {
            super();
        }

        public on(event: string | symbol, listener: (...args: any[]) => void): this {
            return this;
        }
    }

    const eventEmitter = new MyEmitter();

    return new Init(eventEmitter, fileFinder);
};

describe('Init class', () => {
    test('Init empty', () => {
        const rootFolder = path.join(process.cwd(), 'tests', 'fixtures', 'init', '01-empty');

        const sut = makeInstance();

        const message: InitMessage = {
            rootFolder: rootFolder,
        };

        const isHandled = sut.handle(message);
        expect(isHandled).toBe(0);
        expect(fs.existsSync(path.join(rootFolder, 'theme.json'))).toBe(true);
        fs.unlinkSync(path.join(rootFolder, 'theme.json'));
        fs.unlinkSync(path.join(rootFolder, 'theme.json.js'));
    });

    test('Init only json', () => {
        const rootFolder = path.join(process.cwd(), 'tests', 'fixtures', 'init', '02-only-json');

        const sut = makeInstance();

        const message: InitMessage = {
            rootFolder: rootFolder,
        };

        const isHandled = sut.handle(message);
        expect(isHandled).toBe(0);
        expect(fs.existsSync(path.join(rootFolder, 'theme.json.js'))).toBe(true);
        fs.unlinkSync(path.join(rootFolder, 'theme.json.js'));
    });

    test('Init both json and js', () => {
        const rootFolder = path.join(process.cwd(), 'tests', 'fixtures', 'init', '03-json-with-entrypoint');

        const sut = makeInstance();

        const message: InitMessage = {
            rootFolder: rootFolder,
        };

        const isHandled = sut.handle(message);
        expect(isHandled).toBe(0);
    });

    test('Init new file', () => {
        const rootFolder = path.join(process.cwd(), 'tests', 'fixtures', 'init', '04-styles');

        const sut = makeInstance();

        const message: InitMessage = {
            rootFolder: rootFolder,
            filename: 'new-file',
        };

        const isHandled = sut.handle(message);
        expect(isHandled).toBe(0);
        expect(fs.existsSync(path.join(rootFolder, 'styles', 'new-file.json'))).toBe(true);
        expect(fs.existsSync(path.join(rootFolder, 'styles', 'new-file.json.js'))).toBe(true);
        fs.unlinkSync(path.join(rootFolder, 'styles', 'new-file.json'));
        fs.unlinkSync(path.join(rootFolder, 'styles', 'new-file.json.js'));
    });
});
