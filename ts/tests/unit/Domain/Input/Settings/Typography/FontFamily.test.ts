import {describe, expect, test} from '@jest/globals';
//
import {FontFamily} from '../../../../../../src/Domain/Input/Settings/Typography';
import {FontFace} from '../../../../../../src/Domain/Input/Settings/Typography/Utilities';

const makeInstance = (slugName: string, name: string, family: string, faces?: FontFace[]): FontFamily => {
    return new FontFamily(slugName, name, family);
};

describe('FontFamily class', () => {
    test('FontFamily instance of', () => {
        const fontFamily = makeInstance('slug', 'name', 'family');
        expect(fontFamily).toBeInstanceOf(FontFamily);
    });

    test('FontFamily toObject', () => {
        const fontFamily = makeInstance('slug', 'name', 'family');
        expect(fontFamily.toObject()).toEqual({
            slug: 'slug',
            name: 'name',
            family: 'family',
        });
    });
});