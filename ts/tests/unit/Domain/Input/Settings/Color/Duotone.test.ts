import {describe, expect, test} from '@jest/globals';
//
import {Duotone} from '../../../../../../src/Domain/Input/Settings/Color';
import {Color} from '../../../../../../src/Domain/Input/Settings/Color/Utilities';

const makeInstance = (preset: string, slug: string) => {
    return new Duotone(slug, 'name', [new Color('rgba(0,0,0,1)'), new Color('rgba(0,0,0,1)')]);
};

describe('Duotone class', () => {
    test('Duotone', () => {
        const sut = makeInstance('color', 'slug');
        expect(sut.toObject()).toEqual({slug: 'slug', name: 'name', colors: ['rgba(0,0,0,1)', 'rgba(0,0,0,1)']});
    });
});
