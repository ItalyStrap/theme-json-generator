import {GradientInterface} from './GradientInterface';
import {Palette} from '../Palette';

export class LinearGradient implements GradientInterface {
    public constructor(
        private readonly direction: string,
        private readonly colors: Palette[]
    ) {}

    public toString(): string {
        return `linear-gradient(${this.direction === '' ? 'to bottom' : this.direction}, ${this.colors.map((color) => color.var()).join(', ')})`;
    }
}
