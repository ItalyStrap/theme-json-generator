import {GradientInterface} from './GradientInterface';
import {Palette} from '../Palette';

export class LinearGradient implements GradientInterface {
    private colors: Palette[] = [];
    public constructor(
        private readonly direction: string,
        ...colors: Palette[]
    ) {
        this.colors = colors;
    }

    public toString(): string {
        return `linear-gradient(${this.direction === '' ? 'to bottom' : this.direction}, ${this.colors.map((color) => color.var()).join(', ')})`;
    }
}
