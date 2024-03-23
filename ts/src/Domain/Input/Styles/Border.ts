import {Common} from './Common';

export class Border extends Common<typeof Border> {
    static readonly COLOR = 'color';
    static readonly RADIUS = 'radius';
    static readonly STYLE = 'style';
    static readonly WIDTH = 'width';

    public color(value: string): this {
        return this.setProperty(Border.COLOR, value);
    }

    public radius(value: string): this {
        return this.setProperty(Border.RADIUS, value);
    }

    public style(value: string): this {
        return this.setProperty(Border.STYLE, value);
    }

    public width(value: string): this {
        return this.setProperty(Border.WIDTH, value);
    }
}
