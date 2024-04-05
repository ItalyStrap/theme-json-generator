import {describe, expect, test} from '@jest/globals';
//
import {Css} from '../../../../../src/Domain/Input/Styles';
//
const fixtures = require('../../../../fixtures/css/cssStyleStringProvider');

describe('Css class', () => {
    test('Css', () => {
        const css = new Css();
        expect(css).toBeInstanceOf(Css);
    });

    test('Css with presets', () => {
        const css = new Css();
        expect(css.parseString('some css')).toBe('some css');
    });
});

type Fixture = {
    selector: string;
    actual: string;
    expected: string;
};

describe.each(Object.entries<Fixture>(fixtures))('Css class', (key, {selector, actual, expected}) => {
    test(`Parse string with selector: ${key}`, () => {
        const cssClass = new Css();
        expect(cssClass.parseString(actual, selector)).toBe(expected);
    });
});
