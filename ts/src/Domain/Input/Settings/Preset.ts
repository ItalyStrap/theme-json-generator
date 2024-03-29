import {PresetInterface} from '.';

export abstract class Preset implements PresetInterface {
    static readonly TYPE: string;
    protected readonly slugName: string;

    protected constructor(
        protected readonly TYPE: string,
        slugName: string
    ) {
        this.assertSlugIsWellFormed(slugName);
        this.slugName = slugName;
    }

    public type(): string {
        return this.TYPE;
    }

    public slug(): string {
        return this.slugName;
    }

    public ref(): string {
        return `{{${this.type()}.${this.slug()}}}`;
    }

    public prop(): string {
        return this.camelToSnake(`--wp--preset--${this.type()}--${this.slug()}`);
    }

    public var(fallback = ''): string {
        return `var(${this.prop()}${fallback === '' ? '' : ',' + fallback})`;
    }

    public toString(): string {
        return this.var();
    }

    public toJSON() {
        return this.var();
    }

    public abstract toObject(): Record<string, string | string[] | Record<string, unknown>>;

    protected assertSlugIsWellFormed(slug: string): void {
        if (slug.match(/\s/) || slug === '') {
            throw new Error(`Slug with spaces is not allowed, got ${slug}`);
        }
    }

    protected camelToSnake(string: string, us = '-'): string {
        return string.replace(/(?<=\d)(?=[A-Za-z])|(?<=[A-Za-z])(?=\d)|(?<=[a-z])(?=[A-Z])/g, us).toLowerCase();
    }
}
