import {describe, expect, test} from '@jest/globals';

import {Color, ColorInterface} from '../../../../../../../src/Domain/Input/Settings/Color/Utilities';

const makeInstance = (color: string): Color => {
    return new Color(color);
};

describe('Color class', () => {
    test('Color instance of', () => {
        const color: ColorInterface = new Color('rgba(0,0,0,0)');
        expect(color).toBeInstanceOf(Color);
    });
});

function colorProvider() {
    return [
        {
            color: 'rgba(0,0,0,0)',
            expectedType: 'rgba',
            alpha: 0,
            red: 0,
            green: 0,
            blue: 0,
            hue: 0,
            isDark: true,
            isLight: false,
            lightness: 0,
            luminance: 0,
            saturation: 0,
            hex: '#000000',
            hsl: 'hsl(0,0%,0%)',
            hsla: 'hsla(0,0%,0%,0)',
            rgb: 'rgb(0,0,0)',
            rgba: 'rgba(0,0,0,0)',
        },
        {
            color: 'rgba(0,0,0,1)',
            expectedType: 'rgba',
            alpha: 1,
            red: 0,
            green: 0,
            blue: 0,
            hue: 0,
            isDark: true,
            isLight: false,
            lightness: 0,
            luminance: 0,
            saturation: 0,
            hex: '#000000',
            hsl: 'hsl(0,0%,0%)',
            hsla: 'hsla(0,0%,0%,1)',
            rgb: 'rgb(0,0,0)',
            rgba: 'rgba(0,0,0,1)',
        },
        // White
        {
            color: 'rgba(255,255,255,1)',
            expectedType: 'rgba',
            alpha: 1,
            red: 255,
            green: 255,
            blue: 255,
            hue: 0,
            isDark: false,
            isLight: true,
            lightness: 100,
            luminance: 1,
            saturation: 0,
            hex: '#ffffff',
            hsl: 'hsl(0,0%,100%)',
            hsla: 'hsla(0,0%,100%,1)',
            rgb: 'rgb(255,255,255)',
            rgba: 'rgba(255,255,255,1)',
        },
        // Light purple
        {
            color: 'rgba(220,198,224,1)',
            expectedType: 'rgba',
            alpha: 1,
            red: 220,
            green: 198,
            blue: 224,
            hue: 291,
            isDark: false,
            isLight: true,
            lightness: 83,
            luminance: 0.6098562910166592,
            saturation: 30,
            hex: '#dcc6e0',
            hsl: 'hsl(291,30%,83%)',
            hsla: 'hsla(291,30%,83%,1)',
            rgb: 'rgb(220,198,224)',
            rgba: 'rgba(220,198,224,1)',
        },
        // Dark cyan opaque
        {
            color: 'rgba(0,139,139,0.6)',
            expectedType: 'rgba',
            alpha: 0.6,
            red: 0,
            green: 139,
            blue: 139,
            hue: 180,
            isDark: true,
            isLight: false,
            lightness: 27,
            luminance: 0.2032931783904645,
            saturation: 100,
            hex: '#008b8b',
            hsl: 'hsl(180,100%,27%)',
            hsla: 'hsla(180,100%,27%,0.6)',
            rgb: 'rgb(0,139,139)',
            rgba: 'rgba(0,139,139,0.6)',
        },
    ];
}

type ColorProvider = {
    color: string;
    [key: string]: string | number | boolean;
};

describe.each(colorProvider())('Color class', (colorProvider: ColorProvider) => {
    test('Color', () => {
        const sut = makeInstance(colorProvider.color);
        expect(sut.type()).toBe(colorProvider.expectedType);
        expect(sut.toString()).toBe(colorProvider.color);

        expect(sut.alpha()).toBe(colorProvider.alpha);
        expect(sut.red()).toBe(colorProvider.red);
        expect(sut.green()).toBe(colorProvider.green);
        expect(sut.blue()).toBe(colorProvider.blue);
        expect(sut.hue()).toBe(colorProvider.hue);
        expect(sut.isDark()).toBe(colorProvider.isDark);
        expect(sut.isLight()).toBe(colorProvider.isLight);
        expect(sut.lightness()).toBe(colorProvider.lightness);
        expect(sut.luminance()).toBe(colorProvider.luminance);
        expect(sut.saturation()).toBe(colorProvider.saturation);

        expect(sut.toHex()).toBeInstanceOf(Color);
        expect(`${sut.toHex()}`).toBe(colorProvider.hex);

        expect(sut.toHsl()).toBeInstanceOf(Color);
        expect(sut.toHsl().toString()).toBe(colorProvider.hsl);

        expect(sut.toHsla(sut.alpha())).toBeInstanceOf(Color);
        expect(sut.toHsla(sut.alpha()).toString()).toBe(colorProvider.hsla);

        expect(sut.toRgb()).toBeInstanceOf(Color);
        expect(sut.toRgb().toString()).toBe(colorProvider.rgb);

        expect(sut.toRgba(sut.alpha())).toBeInstanceOf(Color);
        expect(sut.toRgba(sut.alpha()).toString()).toBe(colorProvider.rgba);
    });
});

describe('Color class', () => {
    test('Color change', () => {
        const sut = makeInstance('#000000');
        expect(sut.toString()).toBe('#000000');
        expect(sut.toHex().toString()).toBe('#000000');
        expect(sut.toHsl().toString()).toBe('hsl(0,0%,0%)');
        expect(sut.toHsla().toString()).toBe('hsla(0,0%,0%,1)');
        expect(sut.toHsla(0).toString()).toBe('hsla(0,0%,0%,0)');
        expect(sut.toRgb().toString()).toBe('rgb(0,0,0)');
        expect(sut.toRgba().toString()).toBe('rgba(0,0,0,1)');
        expect(sut.toRgba(0).toString()).toBe('rgba(0,0,0,0)');
    });

    test('Color change multiple', () => {
        const sut = makeInstance('#000000');
        expect(sut.toString()).toBe('#000000');
        expect(sut.toHex().toHsl().toRgb().toRgba().toHsla().toString()).toBe('hsla(0,0%,0%,1)');
    });
});
