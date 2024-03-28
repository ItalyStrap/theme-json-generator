import {Preset} from '../Preset';
import {ColorInterface} from './Utilities';

export class Duotone extends Preset {
    static readonly TYPE = 'duotone';

    public constructor(
        protected readonly slugName: string,
        private readonly name: string,
        private readonly colors: ColorInterface[]
    ) {
        super(Duotone.TYPE, slugName);
    }

    toObject(): Record<string, string | string[]> {
        return {
            slug: this.slug(),
            name: this.name,
            colors: this.colors.map((color) => `${color}`),
        };
    }
}
