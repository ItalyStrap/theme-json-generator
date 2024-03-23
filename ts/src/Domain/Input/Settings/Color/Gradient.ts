import {Preset} from '../Preset';
import {PresetInterface} from '../PresetInterface';
import {GradientInterface} from './Utilities';

export class Gradient extends Preset implements PresetInterface {
    static readonly TYPE = 'gradient';

    public constructor(
        protected readonly slugName: string,
        private readonly name: string,
        private readonly gradient: GradientInterface
    ) {
        super(Gradient.TYPE, slugName);
    }

    toObject(): Record<string, string | string[]> {
        return {
            slug: this.slug(),
            name: this.name,
            gradient: `${this.gradient}`,
        };
    }
}
