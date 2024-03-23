import {describe, expect, test} from '@jest/globals';
//
import {Custom} from '../../../../../../src/Domain/Input/Settings/Custom';

const makeInstance = (slug: string, value: string) => {
    return new Custom(slug, value);
};

describe('Custom class', () => {
    test('Custom', () => {
        const sut = makeInstance('slug', 'value');

        expect(sut.toObject()).toEqual({key: 'slug', name: 'Slug', value: 'value'});
        expect(sut.toString()).toEqual('value');
        expect(sut.slug()).toEqual('slug');
        expect(sut.prop()).toEqual('--wp--custom--slug');
    });
});
