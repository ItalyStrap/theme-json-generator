import {describe, expect, test} from '@jest/globals';
//
import {Blueprint, Config} from '../../../../src/Application/Config';
//

describe('Config class', () => {
    test('Config class', () => {
        const config = new Config<string, number | string>();
        config.set('key', 'value');
        expect(config.get('key')).toBe('value');

        config.set('key2', 1);
        expect(config.get('key2')).toBe(1);
        expect(JSON.stringify(config, null, 2)).toBe(
            '{\n  "key": "value",\n  "key2": 1\n}'
        );
    });

    test('Config class merge', () => {
        const config = new Config<string, number | string | object>({
            key: 'old value',
            key2: 0,
        });

        config.merge({key: 'value', key2: 1});
        expect(config.get('key')).toBe('value');
        expect(config.get('key2')).toBe(1);

        // Merge with new key
        config.merge({key: 'new value', key2: 2});
        expect(config.get('key')).toBe('new value');
        expect(config.get('key2')).toBe(2);

        // Merge nested key
        config.merge({key: {key: 'value'}});
        expect(config.get('key.key')).toBe('value');
        expect(config.get('key2')).toBe(2);
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
        expect(config.toArray()).toStrictEqual([
            ['key', 'value'],
            ['key2', 1],
        ]);
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
        expect(config.toJSON()).toStrictEqual({
            key: {key: 'new value', key2: {key4: 'value'}},
        });

        config.set('key.key2.key3.0', {key: 'value'});
        expect(config.toJSON()).toStrictEqual({
            key: {
                key: 'new value',
                key2: {key4: 'value', key3: [{key: 'value'}]},
            },
        });

        config.set('key.key2.key5', [{key: 'value'}, {key: 'value'}]);
        expect(config.toJSON()).toStrictEqual({
            key: {
                key: 'new value',
                key2: {
                    key4: 'value',
                    key3: [{key: 'value'}],
                    key5: [{key: 'value'}, {key: 'value'}],
                },
            },
        });
    });

    test('Config iteration', () => {
        const config = new Config<string, string>({
            key1: 'value1',
            key2: 'value2',
            key3: 'value3',
            key4: 'value4',
        }) as Config<string, string>;

        // @ts-ignore
        for (const [key, value] of config) {
            expect(config.get(key)).toBe(value);
        }
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

describe('Blueprint class', () => {
    test('Blueprint class', () => {
        const initialConfig = {
            init: 'init value',
        };

        const blueprint = new Blueprint<string, number | string>(initialConfig);
        expect(blueprint.get('init')).toBe('init value');

        blueprint.set('key', 'value');
        expect(blueprint.get('key')).toBe('value');

        blueprint.set('key2', 1);
        expect(blueprint.get('key2')).toBe(1);
    });
});
