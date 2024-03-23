import {Common} from '.';

export class Color extends Common<typeof Color> {
    static readonly BACKGROUND = 'background';
    static readonly GRADIENT = 'gradient';
    static readonly TEXT = 'text';
    static readonly LINK = 'link';

    public background(value: string): this {
        return this.setProperty(Color.BACKGROUND, value);
    }

    public gradient(value: string): this {
        return this.setProperty(Color.GRADIENT, value);
    }

    public text(value: string): this {
        return this.setProperty(Color.TEXT, value);
    }

    public link(value: string): this {
        return this.setProperty(Color.LINK, value);
    }
}
