import {describe, expect, test} from '@jest/globals';
//
import {Spacing, CommonPresets, CommonProperties} from '../../../../../src/Domain/Input/Styles';

const makeInstance = (presets: CommonPresets = null, properties: CommonProperties = {}) => {
    return new Spacing(presets, properties);
};

describe('Spacing class', () => {
    test('Spacing', () => {
        const sut = makeInstance().top('valueTop').right('valueRight').bottom('valueBottom').left('valueLeft');

        expect(sut).toBeInstanceOf(Spacing);

        expect(sut.toJson()).toEqual({
            top: 'valueTop',
            right: 'valueRight',
            bottom: 'valueBottom',
            left: 'valueLeft',
        });
    });
});
