import {describe, expect, test} from '@jest/globals';
//
import {Palette} from '../../../../../../src/Domain/Input/Settings/Color';
import {Color} from '../../../../../../src/Domain/Input/Settings/Color/Utilities';
//

const makeInstance = (preset: string, slug: string) => {
    return new Palette(slug, 'name', new Color('rgba(0,0,0,1)'));
};

describe('Palette class', () => {
    test('Palette', () => {
        const sut = makeInstance('color', 'slug');
        expect(sut.toObject()).toEqual({slug: 'slug', name: 'name', color: 'rgba(0,0,0,1)'});
    });
});
