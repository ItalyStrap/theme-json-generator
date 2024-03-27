import {GeneratorAbstract, GeneratorInterface} from './';
import {ColorInterface, ColorModifierInterface} from '../';

export class LightenColorsExperimental extends GeneratorAbstract implements GeneratorInterface {
    static readonly MIN = 10;
    static readonly MAX = 100;
    static readonly INCREMENT_BY = 10;

    public constructor(
        private readonly colorModifier: ColorModifierInterface,
        private readonly min: number = LightenColorsExperimental.MIN,
        private readonly max: number = LightenColorsExperimental.MAX,
        private readonly incrementBy: number = LightenColorsExperimental.INCREMENT_BY,
        private readonly reverse: boolean = false
    ) {
        super();
    }

    public *generate(): Generator<ColorInterface> {
        if (this.reverse) {
            for (let i = this.max; i >= this.min; i -= this.incrementBy) {
                yield this.invoke(i);
            }

            return;
        }

        for (let i = this.min; i <= this.max; i += this.incrementBy) {
            yield this.invoke(i);
        }
    }

    protected invoke(i: number): ColorInterface {
        return this.colorModifier.lighten(i);
    }
}
