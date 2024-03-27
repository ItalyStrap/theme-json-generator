import {describe, expect, test} from '@jest/globals';
//
import {FontSize} from '../../../../../../src/Domain/Input/Settings/Typography';
import {Fluid} from '../../../../../../src/Domain/Input/Settings/Typography/Utilities';

const makeInstance = (slugName: string, name: string, size: string, fluid: Fluid | null = null): FontSize => {
    return new FontSize(slugName, name, size, fluid);
};

describe('FontSize class', () => {
    test('FontSize instance of', () => {
        const fontSize = makeInstance('slug', 'name', 'size');
        expect(fontSize).toBeInstanceOf(FontSize);
    });

    test('FontSize toObject', () => {
        const fontSize = makeInstance('slug', 'name', 'size');
        expect(fontSize.toObject()).toEqual({
            slug: 'slug',
            name: 'name',
            size: 'size',
        });
    });

    test('FontSize toObject with fluid', () => {
        const fontSize = makeInstance('slug', 'name', 'size', new Fluid('2rem', '3rem'));
        expect(fontSize.toObject()).toEqual({
            slug: 'slug',
            name: 'name',
            size: 'size',
            fluid: {
                min: '2rem',
                max: '3rem',
            },
        });
    });

    test('FontSize toObject without fluid', () => {
        const fontSize = makeInstance('slug', 'name', 'size');
        expect(fontSize.toObject()).toEqual({
            slug: 'slug',
            name: 'name',
            size: 'size',
        });
    });
});
