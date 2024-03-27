import {ColorInterface, ColorModifierInterface} from '../';
import {GeneratorInterface} from './';

export class MonochromaticColorsExperimental implements GeneratorInterface {
    public constructor(
        private readonly colorModifier: ColorModifierInterface,
        private readonly steps: number[] = []
    ) {}

    public *generate(): Generator<ColorInterface> {
        let steps = this.steps.sort((a, b) => b - a);
        for (const weight of steps) {
            yield this.colorModifier.tint(weight);
        }

        yield this.colorModifier.color();

        steps = this.steps.sort((a, b) => a - b);
        for (const weight of steps) {
            yield this.colorModifier.shade(weight);
        }
    }

    public toArray(): ColorInterface[] {
        return Array.from(this.generate());
    }
}
