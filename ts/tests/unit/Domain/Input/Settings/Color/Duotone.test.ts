import {describe, expect, test} from '@jest/globals';
//
import {Duotone, Palette} from '../../../../../../src/Domain/Input/Settings/Color';
import {Color} from '../../../../../../src/Domain/Input/Settings/Color/Utilities';

const makeInstance = (preset: string, slug: string) => {
    const color = new Palette(preset, 'name', new Color('rgba(0,0,0,1)'));
    const color2 = new Palette(preset, 'name', new Color('rgba(0,0,0,1)'));
    return new Duotone(slug, 'name', color, color2);
};

describe('Duotone class', () => {
    test('Duotone', () => {
        const sut = makeInstance('color', 'slug');
        expect(sut.toObject()).toEqual({slug: 'slug', name: 'name', colors: ['rgba(0,0,0,1)', 'rgba(0,0,0,1)']});
    });
});
