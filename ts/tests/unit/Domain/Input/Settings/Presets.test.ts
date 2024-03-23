import {describe, expect, test} from '@jest/globals';
//
import {Presets, Preset, PresetsInterface} from '../../../../../src/Domain/Input/Settings';
//

const makePreset = (type: string, slug: string) => {
    return new (class extends Preset {
        static readonly TYPE = 'color';

        public constructor(type: string, slugName: string) {
            super(type, slugName);
        }

        toObject(): Record<string, string | Record<string, unknown>> {
            return {
                slug: this.slug(),
                name: 'name',
            };
        }
    })(type, slug);
};

const makeInstance = () => {
    return new Presets();
};

describe('Presets class', () => {
    test('Presets', () => {
        const sut = makeInstance();
        sut.add(makePreset('color', 'base'));
        sut.add(makePreset('color', 'accent'));

        expect(sut.get('color.base')).toEqual({TYPE: 'color', slugName: 'base'});
        expect(sut.get('color.accent')).toEqual({TYPE: 'color', slugName: 'accent'});
    });

    test('Presets findAllByType', () => {
        const sut = makeInstance();
        sut.add(makePreset('color', 'base'));
        sut.add(makePreset('color', 'accent'));
        sut.add(makePreset('font', 'base'));
        sut.add(makePreset('font', 'accent'));
        const presetsFound = sut.findAllByType('color');

        expect('length' in presetsFound && presetsFound.length).toEqual(2);
    });

    test('Presets parse', () => {
        const sut = makeInstance();
        sut.add(makePreset('color', 'base'));
        sut.add(makePreset('color', 'accent'));
        sut.add(makePreset('font', 'base'));
        sut.add(makePreset('font', 'accent'));

        expect(sut.parse('{{color.base}}')).toEqual(sut.get('color.base')?.var());
        expect(sut.parse('{{color.base}}')).toEqual('var(--wp--preset--color--base)');
        expect(sut.parse('{{color.accent}}')).toEqual('var(--wp--preset--color--accent)');

        expect(sut.parse('{{type.notPresent}}')).toEqual('{{type.notPresent}}');
    });
});
