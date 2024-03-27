import {describe, expect, test} from '@jest/globals';
//
import {Color, ColorModifier} from '../../../../../../../../src/Domain/Input/Settings/Color/Utilities';
import {DarkenColorsExperimental} from '../../../../../../../../src/Domain/Input/Settings/Color/Utilities/Generators';

const makeInstance = (color: ColorModifier) => {
    return new DarkenColorsExperimental(color);
};

describe('DarkenColors class', () => {
    test('DarkenColors instance of', () => {
        const color = new Color('rgba(0,0,0,0)');
        const colorModifier = new ColorModifier(color);
        const darkenColors = makeInstance(colorModifier);
        expect(darkenColors).toBeInstanceOf(DarkenColorsExperimental);
    });

    test('DarkenColors map', () => {
        const color = new Color('rgba(255,255,255,1)');
        const colorModifier = new ColorModifier(color.toHsla());

        const darkenColors = makeInstance(colorModifier);

        expect(darkenColors.toArray()).toEqual([
            new Color('hsla(0,0%,90%,1)'),
            new Color('hsla(0,0%,80%,1)'),
            new Color('hsla(0,0%,70%,1)'),
            new Color('hsla(0,0%,60%,1)'),
            new Color('hsla(0,0%,50%,1)'),
            new Color('hsla(0,0%,40%,1)'),
            new Color('hsla(0,0%,30%,1)'),
            new Color('hsla(0,0%,20%,1)'),
            new Color('hsla(0,0%,10%,1)'),
            new Color('hsla(0,0%,0%,1)'),
        ]);
    });
});
