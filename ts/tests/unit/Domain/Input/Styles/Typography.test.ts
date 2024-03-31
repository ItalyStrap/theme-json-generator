import {describe, expect, test} from '@jest/globals';
//
import {Typography, CommonPresets, CommonProperties} from '../../../../../src/Domain/Input/Styles';

const makeInstance = (presets: CommonPresets = null, properties: CommonProperties = {}) => {
    return new Typography(presets, properties);
};

describe('Typography class', () => {
    test('Typography', () => {
        const sut = makeInstance()
            .fontFamily('valueFontFamily')
            .fontSize('valueFontSize')
            .fontStyle('valueFontStyle')
            .fontWeight('valueFontWeight')
            .letterSpacing('valueLetterSpacing')
            .lineHeight('valueLineHeight')
            .textDecoration('valueTextDecoration')
            .textTransform('valueTextTransform');

        expect(sut).toBeInstanceOf(Typography);

        expect(sut.toJSON()).toEqual({
            fontFamily: 'valueFontFamily',
            fontSize: 'valueFontSize',
            fontStyle: 'valueFontStyle',
            fontWeight: 'valueFontWeight',
            letterSpacing: 'valueLetterSpacing',
            lineHeight: 'valueLineHeight',
            textDecoration: 'valueTextDecoration',
            textTransform: 'valueTextTransform',
        });
    });
});
