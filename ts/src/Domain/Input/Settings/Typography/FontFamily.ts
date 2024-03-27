import {Preset} from '../Preset';
import {PresetInterface} from '../PresetInterface';

export class FontFamily extends Preset implements PresetInterface {
    public static readonly TYPE = 'fontFamily';

    public constructor(
        protected readonly slugName: string,
        private readonly name: string,
        private readonly family: string
    ) {
        super(FontFamily.TYPE, slugName);
    }

    public toObject(): Record<string, string> {
        return {
            slug: this.slug(),
            name: this.name,
            family: this.family,
        };
    }
}
