import {Common} from './Common';

export class Outline extends Common<typeof Outline> {
    static readonly COLOR = 'color';
    static readonly OFFSET = 'offset';
    static readonly STYLE = 'style';
    static readonly WIDTH = 'width';

    public color(value: string): this {
        return this.setProperty(Outline.COLOR, value);
    }

    public offset(value: string): this {
        return this.setProperty(Outline.OFFSET, value);
    }

    public style(value: string): this {
        return this.setProperty(Outline.STYLE, value);
    }

    public width(value: string): this {
        return this.setProperty(Outline.WIDTH, value);
    }
}
