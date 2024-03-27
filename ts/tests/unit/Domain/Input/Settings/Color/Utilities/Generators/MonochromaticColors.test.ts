import {describe, expect, test} from '@jest/globals';
//
import {Color, ColorInterface, ColorModifier} from '../../../../../../../../src/Domain/Input/Settings/Color/Utilities';
import {MonochromaticColorsExperimental} from '../../../../../../../../src/Domain/Input/Settings/Color/Utilities/Generators';
import {Palette} from '../../../../../../../../src/Domain/Input/Settings/Color';

const makeInstance = (color: ColorModifier, steps: number[]): MonochromaticColorsExperimental => {
    return new MonochromaticColorsExperimental(color, steps);
};

describe('MonochromaticColors class', () => {
    test('MonochromaticColors instance of', () => {
        const color = new Color('rgba(0,0,0,0)');
        const colorModifier = new ColorModifier(color);
        const shadesColorGenerator = makeInstance(colorModifier, [10, 20, 30, 40, 50]);
        expect(shadesColorGenerator).toBeInstanceOf(MonochromaticColorsExperimental);
    });

    test('MonochromaticColors map', () => {
        const color = new Color('rgba(127,127,127,1)');
        const colorModifier = new ColorModifier(color.toHsla());
        const stepsFunc = (index: number) => {
            const arr: number[] = [];
            for (let i = 1; i < index + 1; i++) {
                arr.push(i * 20);
            }
            return arr;
        };

        expect(stepsFunc(5)).toEqual([20, 40, 60, 80, 100]);

        const shadesColorGenerator = makeInstance(colorModifier, stepsFunc(5));

        expect(shadesColorGenerator.toArray()).toEqual([
            new Color('hsla(0,0%,100%,1)'),
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
