import {describe, expect, test} from '@jest/globals';
//
import {Color, ColorModifier} from '../../../../../../../../src/Domain/Input/Settings/Color/Utilities';
import {LightenColorsExperimental} from '../../../../../../../../src/Domain/Input/Settings/Color/Utilities/Generators';

const makeInstance = (color: ColorModifier) => {
    return new LightenColorsExperimental(color);
};

describe('LightenColors class', () => {
    test('LightenColors instance of', () => {
        const color = new Color('rgba(0,0,0,0)');
        const colorModifier = new ColorModifier(color);
        const lightenColors = makeInstance(colorModifier);
        expect(lightenColors).toBeInstanceOf(LightenColorsExperimental);
    });

    test('LightenColors map', () => {
        const color = new Color('rgba(0,0,0,1)');
        const colorModifier = new ColorModifier(color.toHsla());

        const lightenColors = makeInstance(colorModifier);

        expect(lightenColors.toArray()).toEqual([
            new Color('hsla(0,0%,10%,1)'),
            new Color('hsla(0,0%,20%,1)'),
            new Color('hsla(0,0%,30%,1)'),
            new Color('hsla(0,0%,40%,1)'),
            new Color('hsla(0,0%,50%,1)'),
            new Color('hsla(0,0%,60%,1)'),
            new Color('hsla(0,0%,70%,1)'),
            new Color('hsla(0,0%,80%,1)'),
            new Color('hsla(0,0%,90%,1)'),
            new Color('hsla(0,0%,100%,1)'),
        ]);
    });
});
