import {describe, expect, test} from '@jest/globals';
//
import {CustomToPresets} from '../../../../../../src/Domain/Input/Settings/Custom';
import {Presets, PresetsInterface} from '../../../../../../src/Domain/Input/Settings';
import {Palette} from '../../../../../../src/Domain/Input/Settings/Color';
import {Color} from '../../../../../../src/Domain/Input/Settings/Color/Utilities';

const makePresets = () => {
    return new Presets();
};

const makeInstance = (presets: PresetsInterface, customs: Record<string, unknown>) => {
    return new CustomToPresets(presets, customs);
};

describe('CustomToPresets class', () => {
    test('CustomToPresets', () => {
        const sut = makeInstance(makePresets(), {});

        expect(sut).toBeInstanceOf(CustomToPresets);
    });

    test('CustomToPresets process', () => {
        const presets = makePresets();
        const sut = makeInstance(presets, {
            slug: 'value',
            slug2: 'value2',
        });

        sut.process();

        const customSlug = presets.get('custom.slug');
        expect(customSlug?.slug()).toBe('slug');
        expect(`${customSlug}`).toBe('value');

        const customSlug2 = presets.get('custom.slug2');
        expect(customSlug2?.slug()).toBe('slug2');
        expect(`${customSlug2}`).toBe('value2');
    });

    test('CustomToPresets process with nested', () => {
        const presets = makePresets();
        const sut = makeInstance(presets, {
            slug: 'value',
            slug2: 'value2',
            nested: {
                slug3: 'value3',
                nested2: {
                    slug4: 'value4',
                    // slug5: presets.get('custom.slug'),
                },
            },
        });

        sut.process();

        const customSlug = presets.get('custom.slug');
        expect(customSlug?.slug()).toBe('slug');
        expect(`${customSlug}`).toBe('value');

        const customSlug2 = presets.get('custom.slug2');
        expect(customSlug2?.slug()).toBe('slug2');
        expect(`${customSlug2}`).toBe('value2');

        const customSlug3 = presets.get('custom.nested.slug3');
        expect(customSlug3?.slug()).toBe('nested.slug3');
        expect(`${customSlug3}`).toBe('value3');

        const customSlug4 = presets.get('custom.nested.nested2.slug4');
        expect(customSlug4?.slug()).toBe('nested.nested2.slug4');
        expect(`${customSlug4}`).toBe('value4');
    });

    test('CustomToPresets process with nested get from preregister preset', () => {
        const presets = makePresets();
        presets.add(new Palette('base', 'Base', new Color('hsla(212,73%,55%,1)')));
        const sut = makeInstance(presets, {
            slug: 'value',
            slug2: 'value2',
            nested: {
                slug3: 'value3',
                nested2: {
                    slug4: 'value4',
                },
                nestedWithColor: {
                    color: presets.get('color.base'),
                },
            },
        });

        sut.process();

        const customSlug = presets.get('custom.slug');
        expect(customSlug?.slug()).toBe('slug');
        expect(`${customSlug}`).toBe('value');

        const customSlug2 = presets.get('custom.slug2');
        expect(customSlug2?.slug()).toBe('slug2');
        expect(`${customSlug2}`).toBe('value2');

        const customSlug3 = presets.get('custom.nested.slug3');
        expect(customSlug3?.slug()).toBe('nested.slug3');
        expect(`${customSlug3}`).toBe('value3');

        const customSlug4 = presets.get('custom.nested.nested2.slug4');
        expect(customSlug4?.slug()).toBe('nested.nested2.slug4');
        expect(`${customSlug4}`).toBe('value4');

        const customColor = presets.get('custom.nested.nestedWithColor.color');
        expect(customColor?.slug()).toBe('nested.nestedWithColor.color');
        expect(`${customColor}`).toBe('var(--wp--preset--color--base)');
    });
});
