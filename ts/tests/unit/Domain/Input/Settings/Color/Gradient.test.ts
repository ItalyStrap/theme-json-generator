import {describe, expect, test} from '@jest/globals';
//
import {Gradient} from '../../../../../../src/Domain/Input/Settings/Color';
import {GradientInterface} from '../../../../../../src/Domain/Input/Settings/Color/Utilities';

const makeInstance = (preset: string, slug: string) => {
    return new Gradient(
        slug,
        'name',
        new (class implements GradientInterface {
            toString(): string {
                return 'linear-gradient(0deg, rgba(0,0,0,1), rgba(0,0,0,1))';
            }
        })()
    );
};

describe('Gradient class', () => {
    test('Gradient', () => {
        const sut = makeInstance('color', 'slug');
        expect(sut.toObject()).toEqual({
            slug: 'slug',
            name: 'name',
            gradient: 'linear-gradient(0deg, rgba(0,0,0,1), rgba(0,0,0,1))',
        });
    });
});
