import {describe, expect, test} from '@jest/globals';
import {LinearGradient} from '../../../../../../../src/Domain/Input/Settings/Color/Utilities';
import {Palette} from '../../../../../../../src/Domain/Input/Settings/Color';
import {Color} from '../../../../../../../src/Domain/Input/Settings/Color/Utilities';

const makeInstance = (direction: string, colors: Palette[]) => {
    return new LinearGradient(direction, colors);
};

describe('LinearGradient class', () => {
    test('LinearGradient', () => {
        const sut = makeInstance('to right', [
            new Palette('base', 'Brand base color', new Color('hsla(212,73%,55%,1)')),
        ]);
        expect(sut).toBeInstanceOf(LinearGradient);
    });

    test('LinearGradient render', () => {
        const sut = makeInstance('to right', [
            new Palette('base', 'Brand base color', new Color('hsla(212,73%,55%,1)')),
            new Palette('light', 'Lighter color', new Color('hsla(0,0%,100%,1)')),
        ]);

        expect(sut.toString()).toBe(
            'linear-gradient(to right, var(--wp--preset--color--base), var(--wp--preset--color--light))'
        );
    });
});
