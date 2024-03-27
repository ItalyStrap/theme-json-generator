import {describe, expect, test} from '@jest/globals';

import {Color, ColorModifier} from '../../../../../../../src/Domain/Input/Settings/Color/Utilities';

const makeInstance = (color: string): ColorModifier => {
    return new ColorModifier(new Color(color));
};

describe('ColorModifier class', () => {
    test('ColorModifier instance of', () => {
        const colorModifier = makeInstance('rgba(0,0,0,0)');
        expect(colorModifier).toBeInstanceOf(ColorModifier);
        expect(colorModifier.color()).toBeInstanceOf(Color);
    });
});

function colorProvider() {
    return [
        {
            color: 'rgba(0,0,0,0)',
            modifier: 50,

            expectedTint: 'rgba(128,128,128,0)',
            expectedShade: 'rgba(0,0,0,0)',
            expectedTone: 'rgba(64,64,64,0)',

            opacityModifier: 0.5,
            expectedOpacity: 'rgba(0,0,0,0.5)',

            expectedDarken: 'rgba(0,0,0,0)',
            expectedLighten: 'rgba(128,128,128,0)',
            expectedSaturate: 'rgba(0,0,0,0)',
            expectedContrast: 'rgba(191,64,64,0)', // @todo: Check if contrast is ok for black

            complementary: 'rgba(0,0,0,0)',
            invert: 'rgba(255,255,255,0)',
            hueRotateModifier: 25,
            expectedHueRotate: 'rgba(0,0,0,0)',
            expectedGreyScale: 'rgba(0,0,0,0)',
        },
        {
            color: 'rgba(0,0,0,1)',
            modifier: 25,

            expectedTint: 'rgba(64,64,64,1)',
            expectedShade: 'rgba(0,0,0,1)',
            expectedTone: 'rgba(32,32,32,1)',

            opacityModifier: 0.5,
            expectedOpacity: 'rgba(0,0,0,0.5)',

            expectedDarken: 'rgba(0,0,0,1)',
            expectedLighten: 'rgba(64,64,64,1)',
            expectedSaturate: 'rgba(0,0,0,1)',
            expectedContrast: 'rgba(80,48,48,1)', // @todo: Check if contrast is ok for black

            complementary: 'rgba(0,0,0,1)',
            invert: 'rgba(255,255,255,1)',
            hueRotateModifier: 25,
            expectedHueRotate: 'rgba(0,0,0,1)',
            expectedGreyScale: 'rgba(0,0,0,1)',
        },
        {
            color: 'rgba(255,255,255,1)',
            modifier: 75,

            expectedTint: 'rgba(255,255,255,1)',
            expectedShade: 'rgba(64,64,64,1)',
            expectedTone: 'rgba(160,160,160,1)',

            opacityModifier: 0.5,
            expectedOpacity: 'rgba(255,255,255,0.5)',

            expectedDarken: 'rgba(64,64,64,1)',
            expectedLighten: 'rgba(255,255,255,1)',
            expectedSaturate: 'rgba(255,255,255,1)',
            expectedContrast: 'rgba(255,255,255,1)', // @todo: Check if contrast is ok for white

            complementary: 'rgba(255,255,255,1)',
            invert: 'rgba(0,0,0,1)',
            hueRotateModifier: 25,
            expectedHueRotate: 'rgba(255,255,255,1)',
            expectedGreyScale: 'rgba(255,255,255,1)',
        },
        {
            color: 'rgba(179,139,32,1)',
            modifier: 85,

            expectedTint: 'rgba(244,238,222,1)',
            expectedShade: 'rgba(27,21,5,1)',
            expectedTone: 'rgba(136,130,114,1)',

            opacityModifier: 0.5,
            expectedOpacity: 'rgba(178,139,31,0.5)', // @todo: This should be 179,139,32,0.5

            expectedDarken: 'rgba(0,0,0,1)',
            expectedLighten: 'rgba(255,255,255,1)',
            expectedSaturate: 'rgba(209,153,0,1)',
            expectedContrast: 'rgba(255,255,255,1)',

            complementary: 'rgba(100,31,178,1)',
            invert: 'rgba(224,185,77,1)',
            hueRotateModifier: 25,
            expectedHueRotate: 'rgba(156,178,31,1)',
            expectedGreyScale: 'rgba(139,139,139,1)',
        },
    ];
}

describe.each(colorProvider())('ColorModifier class', (data) => {
    test('ColorModifier', () => {
        const colorModifier = makeInstance(data.color);
        expect(colorModifier.tint(data.modifier).toString()).toBe(data.expectedTint);
        expect(colorModifier.shade(data.modifier).toString()).toBe(data.expectedShade);
        expect(colorModifier.tone(data.modifier).toString()).toBe(data.expectedTone);
        expect(colorModifier.opacity(data.opacityModifier).toString()).toBe(data.expectedOpacity);
        expect(colorModifier.darken(data.modifier).toString()).toBe(data.expectedDarken);
        expect(colorModifier.lighten(data.modifier).toString()).toBe(data.expectedLighten);
        expect(colorModifier.saturate(data.modifier).toString()).toBe(data.expectedSaturate);
        expect(colorModifier.contrast(data.modifier).toString()).toBe(data.expectedContrast);
        expect(colorModifier.complementary().toString()).toBe(data.complementary);
        expect(colorModifier.invert().toString()).toBe(data.invert);
        expect(colorModifier.hueRotate(data.hueRotateModifier).toString()).toBe(data.expectedHueRotate);
        expect(colorModifier.greyScale().toString()).toBe(data.expectedGreyScale);
    });
});
