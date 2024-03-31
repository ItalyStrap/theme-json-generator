import {describe, expect, test} from '@jest/globals';
//
import {Border, CommonPresets, CommonProperties} from '../../../../../src/Domain/Input/Styles';

const makeInstance = (presets: CommonPresets = null, properties: CommonProperties = {}) => {
    return new Border(presets, properties);
};

describe('Border class', () => {
    test('Border', () => {
        const sut = makeInstance().radius('valueRadius').width('valueWidth').style('valueStyle').color('valueColor');

        expect(sut).toBeInstanceOf(Border);

        expect(sut.toJSON()).toEqual({
            radius: 'valueRadius',
            width: 'valueWidth',
            style: 'valueStyle',
            color: 'valueColor',
        });
    });
});
