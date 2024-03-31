import {describe, expect, test} from '@jest/globals';
//
import {Common, CommonPresets, CommonProperties} from '../../../../../src/Domain/Input/Styles';
import {Presets} from '../../../../../src/Domain/Input/Settings';
import {Palette} from '../../../../../src/Domain/Input/Settings/Color';
import {Color} from '../../../../../src/Domain/Input/Settings/Color/Utilities';
//

const makeInstance = (presets: CommonPresets = null, properties: CommonProperties = {}) => {
    return new (class extends Common<any> {
        public constructor(presets: CommonPresets, properties: CommonProperties) {
            super(presets, properties);
        }

        public testProperty(property: string, value: string): this {
            return this.setProperty(property, value);
        }
    })(presets, properties);
};

describe('Common class', () => {
    test('Common', () => {
        const sut = makeInstance();
        const returned = sut.testProperty('property', 'value');

        expect(sut['properties']).toEqual({});
        expect(returned).toBeInstanceOf(Common);

        expect(returned['properties']).toEqual({property: 'value'});
    });

    test('Common immutability', () => {
        const sut = makeInstance();
        const returned = sut.testProperty('property', 'value');

        expect(sut['properties']).toEqual({});
        expect(returned['properties']).toEqual({property: 'value'});

        const returned2 = returned.property('property2', 'value2');
        expect(sut['properties']).toEqual({});
        expect(returned2['properties']).toEqual({property: 'value', property2: 'value2'});
    });

    test('Common with duplicate properties', () => {
        const sut = makeInstance()
            .testProperty('background', 'valueBg')
            .testProperty('background', 'valueBg2')
            .testProperty('gradient', 'valueGradient')
            .testProperty('gradient', 'valueGradient2')
            .testProperty('text', 'valueText')
            .testProperty('text', 'valueText2')
            .testProperty('link', 'valueLink')
            .testProperty('link', 'valueLink2');

        expect(sut['properties']).toEqual({
            background: 'valueBg2',
            gradient: 'valueGradient2',
            text: 'valueText2',
            link: 'valueLink2',
        });

        expect(sut.toJSON()).toEqual({
            background: 'valueBg2',
            gradient: 'valueGradient2',
            text: 'valueText2',
            link: 'valueLink2',
        });
    });

    test('Common  with some empty properties', () => {
        const sut = makeInstance()
            .testProperty('background', 'valueBg')
            .testProperty('gradient', '')
            .testProperty('link', 'valueLink')
            .testProperty('text', '');

        expect(sut.toJSON()).toEqual({
            background: 'valueBg',
            link: 'valueLink',
        });
    });
});

describe('Common class with presets', () => {
    test('Common with presets', () => {
        const presets = new Presets();
        presets.add(new Palette('base', 'Base', new Color('hsla(212,73%,55%,1)')));
        presets.add(new Palette('accent', 'Accent', new Color('hsla(0,0%,100%,1)')));

        const sut = makeInstance(presets).testProperty('background', 'color.base').testProperty('text', 'color.accent');

        expect(sut.toJSON()).toEqual({
            background: 'var(--wp--preset--color--base)',
            text: 'var(--wp--preset--color--accent)',
        });
    });
});
