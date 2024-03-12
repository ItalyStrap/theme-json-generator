import {describe, expect, test} from '@jest/globals';
//
import {Blueprint, Config} from "../../../../src/Application/Config";
import exp = require("constants");
//

describe('Config class', () => {
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

    test('Config class update', () => {
        const config = new Config<string, number | string>();
        config.set('key', 'value');
        config.update('key', 'value');
        expect(config.get('key')).toBe('value');
    });

    test('Config class delete', () => {
        const config = new Config<string, number | string>();
        config.set('key', 'value');
        config.delete('key');
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
        expect(config.toJSON()).toStrictEqual({key: {key: 'value'}});

        config.set('key.key', 'new value');
        expect(config.toJSON()).toStrictEqual({key: {key: 'new value'}});

        config.set('key.key2.key4', 'value');
        expect(config.toJSON()).toStrictEqual({key: {key: 'new value', key2: {key4: 'value'}}});

        config.set('key.key2.key3.0', {key: 'value'});
        expect(config.toJSON()).toStrictEqual({key: {key: 'new value', key2: {key4: 'value', key3: [{key: 'value'}]}}});

        config.set('key.key2.key5', [{key: 'value'}, {key: 'value'}]);
        expect(config.toJSON()).toStrictEqual({key: {key: 'new value', key2: {key4: 'value', key3: [{key: 'value'}], key5: [{key: 'value'}, {key: 'value'}]}}});
    });
});

describe('Config class Has', () => {
    test('Config class has', () => {
        const config = new Config<string, number | string>();
        config.set('key', 'value');
        expect(config.has('key')).toBe(true);
        expect(config.has('key2')).toBe(false);

        expect(config.get('key')).toBe('value');
    });

    test('Config class has nested', () => {
        const config = new Config<string, number | string | object>();
        config.set('key', {key: 'value'});
        expect(config.has('key.key')).toBe(true);
        expect(config.has('key.key2')).toBe(false);

        expect(config.get('key.key')).toBe('value');
    });

    test('Config class has nested array', () => {
        const config = new Config<string, number | string | object>();
        config.set('key', {key: ['value', 'value2']});
        expect(config.has('key.key.0')).toBe(true);
        expect(config.has('key.key.2')).toBe(false);

        expect(config.get('key.key.0')).toBe('value');
    });

    // test('Config class has array', () => {
    //     const config = new Config<string, number | string | object>();
    //     config.set('0', 'value');
    //     expect(config.has('0')).toBe(true);
    //     expect(config.has('1')).toBe(false);
    //
    //     expect(config.get('0')).toBe('value');
    //     expect(config.toJSON()).toBe(['value']);
    // } );
});