import {Preset} from '../Preset';
import {Palette} from './Palette';

export class Duotone extends Preset {
    static readonly TYPE = 'duotone';

    private readonly colors: Palette[] = [];

    public constructor(
        protected readonly slugName: string,
        private readonly name: string,
        ...colors: Palette[]
    ) {
        super(Duotone.TYPE, slugName);
        this.colors = colors;
    }

    toObject(): Record<string, string | string[]> {
        return {
            slug: this.slug(),
            name: this.name,
            colors: this.colors.map((color) => `${color.color().toRgba()}`),
        };
    }
}
