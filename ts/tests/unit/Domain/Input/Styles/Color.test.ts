import {describe, expect, test} from '@jest/globals';
//
import {Color, CommonPresets, CommonProperties} from '../../../../../src/Domain/Input/Styles';

const makeInstance = (presets: CommonPresets = null, properties: CommonProperties = {}) => {
    return new Color(presets, properties);
};

describe('Color class', () => {
    test('Color', () => {
        const sut = makeInstance().background('valueBg').gradient('valueGradient').text('valueText').link('valueLink');

        expect(sut).toBeInstanceOf(Color);

        expect(sut.toJson()).toEqual({
            background: 'valueBg',
            gradient: 'valueGradient',
            text: 'valueText',
            link: 'valueLink',
        });
    });
});
