import {describe, expect, test} from '@jest/globals';
//
import {Blueprint} from '../../../../src/Application/Config';
import {Presets} from '../../../../src/Domain/Input/Settings';
import {Palette} from '../../../../src/Domain/Input/Settings/Color';
import {Color} from '../../../../src/Domain/Input/Settings/Color/Utilities';
import {Custom} from '../../../../src/Domain/Input/Settings/Custom';

describe('Blueprint class', () => {
    test('Blueprint class', () => {
        const initialConfig = {
            init: 'init value',
        };

        const blueprint = new Blueprint(initialConfig);
        expect(blueprint.get('init')).toBe('init value');

        blueprint.set('key', 'value');
        expect(blueprint.get('key')).toBe('value');

        blueprint.set('key2', 1);
        expect(blueprint.get('key2')).toBe(1);
    });

    test('Blueprint implementation', () => {
        const base = new Palette('base', 'Brand base color', new Color('hsla(212,73%,55%,1)'));
        const light = new Palette('light', 'Lighter color', new Color('hsla(0,0%,100%,1)'));
        const dark = new Palette('dark', 'Darker color', new Color('hsla(0,0%,0%,1)'));

        const contentSize = new Custom('contentSize', 'clamp(16rem, 60vw, 60rem)');
        const wideSize = new Custom('wideSize', 'clamp(16rem, 85vw, 70rem)');
        const spacerBase = new Custom('spacer.base', '1rem');
        const test = new Custom('one.two.three', '1rem');
        const test2 = new Custom('for.five.six', '1rem');
        const test3 = new Custom('seven.eight.nine', '1rem');

        const presets = new Presets();
        presets.add(base);
        presets.add(light);
        presets.add(dark);
        presets.add(contentSize);
        presets.add(wideSize);
        presets.add(spacerBase);
        presets.addMultiple([test, test2, test3]);

        const blueprint = new Blueprint();
        blueprint.setPresets(presets);

        expect(blueprint.get('settings.color.palette').length).toBe(3);

        expect(blueprint.get('settings.color.palette.0.slug')).toBe('base');
        expect(blueprint.get('settings.color.palette.0.name')).toBe('Brand base color');
        expect(blueprint.get('settings.color.palette.0.color')).toBe('hsla(212,73%,55%,1)');

        expect(blueprint.get('settings.custom.contentSize')).toBe('clamp(16rem, 60vw, 60rem)');
        expect(blueprint.get('settings.custom.wideSize')).toBe('clamp(16rem, 85vw, 70rem)');
        expect(blueprint.get('settings.custom.spacer.base')).toBe('1rem');
        expect(blueprint.get('settings.custom.one.two.three')).toBe('1rem');
        expect(blueprint.get('settings.custom.for.five.six')).toBe('1rem');
        expect(blueprint.get('settings.custom.seven.eight.nine')).toBe('1rem');
    });
});
