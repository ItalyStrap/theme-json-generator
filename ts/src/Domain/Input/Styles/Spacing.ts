import {Common} from './Common';

export class Spacing extends Common<typeof Spacing> {
    static readonly TOP = 'top';
    static readonly RIGHT = 'right';
    static readonly BOTTOM = 'bottom';
    static readonly LEFT = 'left';

    public top(value: string): this {
        return this.setProperty(Spacing.TOP, value);
    }

    public right(value: string): this {
        return this.setProperty(Spacing.RIGHT, value);
    }

    public bottom(value: string): this {
        return this.setProperty(Spacing.BOTTOM, value);
    }

    public left(value: string): this {
        return this.setProperty(Spacing.LEFT, value);
    }

    public shorthand(values: string[]): this {
        switch (values.length) {
            case 1:
                return this.setProperty(Spacing.TOP, values[0]!)
                    .setProperty(Spacing.RIGHT, values[0]!)
                    .setProperty(Spacing.BOTTOM, values[0]!)
                    .setProperty(Spacing.LEFT, values[0]!);
            case 2:
                return this.setProperty(Spacing.TOP, values[0]!)
                    .setProperty(Spacing.RIGHT, values[1]!)
                    .setProperty(Spacing.BOTTOM, values[0]!)
                    .setProperty(Spacing.LEFT, values[1]!);
            case 3:
                return this.setProperty(Spacing.TOP, values[0]!)
                    .setProperty(Spacing.RIGHT, values[1]!)
                    .setProperty(Spacing.BOTTOM, values[2]!)
                    .setProperty(Spacing.LEFT, values[1]!);
            case 4:
                return this.setProperty(Spacing.TOP, values[0]!)
                    .setProperty(Spacing.RIGHT, values[1]!)
                    .setProperty(Spacing.BOTTOM, values[2]!)
                    .setProperty(Spacing.LEFT, values[3]!);
            default:
                throw new Error(`The shorthand method accept only 1, 2, 3 or 4 values, ${values.length} given`);
        }
    }

    public vertical(value: string): this {
        return this.setProperty(Spacing.TOP, value).setProperty(Spacing.BOTTOM, value);
    }

    public horizontal(value: string): this {
        return this.setProperty(Spacing.RIGHT, value).setProperty(Spacing.LEFT, value);
    }

    public verticalAsync(top: string, bottom: string): this {
        return this.setProperty(Spacing.TOP, top).setProperty(Spacing.BOTTOM, bottom);
    }
}
