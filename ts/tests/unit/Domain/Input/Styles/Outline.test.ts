import {describe, expect, test} from '@jest/globals';
//
import {Outline, CommonPresets, CommonProperties} from '../../../../../src/Domain/Input/Styles';

const makeInstance = (presets: CommonPresets = null, properties: CommonProperties = {}) => {
    return new Outline(presets, properties);
};

describe('Outline class', () => {
    test('Outline', () => {
        const sut = makeInstance().color('valueColor').offset('valueOffset').style('valueStyle').width('valueWidth');

        expect(sut).toBeInstanceOf(Outline);

        expect(sut.toJson()).toEqual({
            color: 'valueColor',
            offset: 'valueOffset',
            style: 'valueStyle',
            width: 'valueWidth',
        });
    });
});
