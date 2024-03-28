import {ColorInterface} from './Utilities';
import {Preset} from '../Preset';

export class Palette extends Preset {
    static readonly TYPE = 'color';

    public constructor(
        protected readonly slugName: string,
        private readonly name: string,
        private readonly initialColor: ColorInterface
    ) {
        super(Palette.TYPE, slugName);
    }

    public color(): ColorInterface {
        return this.initialColor;
    }

    public toObject(): Record<string, string> {
        return {
            slug: this.slug(),
            name: this.name,
            color: `${this.initialColor}`,
        };
    }
}
