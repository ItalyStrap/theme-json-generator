import {describe, expect, test} from '@jest/globals';
//
import {Preset} from '../../../../../src/Domain/Input/Settings';
//

const makeInstance = (preset: string, slug: string) => {
    return new (class extends Preset {
        public constructor(type: string, slugName: string) {
            super(type, slugName);
        }

        toArray(): Record<string, string | Record<string, unknown>> {
            return {
                slug: this.slug(),
                name: 'name',
            };
        }
    })(preset, slug);
};

describe('Preset class', () => {
    test('Preset', () => {
        const sut = makeInstance('preset', 'slug');

        expect(sut.type()).toBe('preset');
        expect(sut.slug()).toBe('slug');
        expect(sut.ref()).toBe('{{preset.slug}}');
        expect(sut.prop()).toBe('--wp--preset--preset--slug');
        expect(sut.var()).toBe('var(--wp--preset--preset--slug)');
        expect(sut.toString()).toBe('var(--wp--preset--preset--slug)');
        expect(sut.toArray()).toEqual({slug: 'slug', name: 'name'});
    });

    test('camelToSnake', () => {
        const sut = makeInstance('preset', 'slug');
        expect(sut['camelToSnake']('camelToSnake')).toBe('camel-to-snake');
    });

    test('assertSlugIsWellFormed', () => {
        const sut = makeInstance('preset', 'slug');
        expect(() => sut['assertSlugIsWellFormed']('slug with space')).toThrowError(
            'Slug with spaces is not allowed, got slug with space'
        );
    });
});
