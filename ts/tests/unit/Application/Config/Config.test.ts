import {describe, expect, test} from '@jest/globals';
//
import {Blueprint, Config} from "../../../../src/Application/Config";
//

describe('File class', () => {
    test('Config class', () => {
        const config = new Config<string, number | string>();
        config.set('key', 'value');
        expect(config.get('key')).toBe('value');

        config.set('key2', 1);
        expect(config.get('key2')).toBe(1);
        expect(JSON.stringify(config, null, 2)).toBe('{\n  "key": "value",\n  "key2": 1\n}');
    });

    test('Config class merge', () => {
        const config = new Config<string, number | string>();
        config.merge({key: 'value', key2: 1});
        expect(config.get('key')).toBe('value');
        expect(config.get('key2')).toBe(1);
    });

    test('Config class has', () => {
        const config = new Config<string, number | string>();
        config.set('key', 'value');
        expect(config.has('key')).toBe(true);
        expect(config.has('key2')).toBe(false);
    });

    test('Config class update', () => {
        const config = new Config<string, number | string>();
        config.set('key', 'value');
        config.update('key', 'value');
        expect(config.get('key')).toBe('value');
    });

    test('Config class delete', () => {
        const config = new Config<string, number | string>();
        config.set('key', 'value');
        config.delete();
        expect(config.get('key')).toBe(null);
    });

    test('Config class toArray', () => {
        const config = new Config<string, number | string>();
        config.set('key', 'value');
        config.set('key2', 1);
        expect(config.toArray()).toStrictEqual([['key', 'value'], ['key2', 1]]);
    });

    test('Config class toJSON', () => {
        const config = new Config<string, number | string>();
        config.set('key', 'value');
        config.set('key2', 1);
        expect(config.toJSON()).toStrictEqual({key: 'value', key2: 1});
    });

    test('Set indentation', () => {
        const config = new Config<string, number | string | object>();
        config.set('key', {key: 'value'});
        expect(JSON.stringify(config, null, 2)).toBe('{\n  "key": {\n    "key": "value"\n  }\n}');

        config.set('key.key', 'value');
        expect(JSON.stringify(config, null, 2)).toBe('{\n  "key": {\n    "key": "value"\n  }\n}');

        config.set('key.key2.key3', 'value');
        expect(JSON.stringify(config, null, 2)).toBe('{\n  "key": {\n    "key": "value",\n    "key2": {\n      "key3": "value"\n    }\n  }\n}');
    } );
});