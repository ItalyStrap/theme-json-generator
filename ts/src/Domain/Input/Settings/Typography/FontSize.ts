import {Preset, PresetInterface} from '../';
import {Fluid} from './Utilities';

export class FontSize extends Preset implements PresetInterface {
    public static readonly TYPE = 'fontSize';

    public constructor(
        protected readonly slugName: string,
        private readonly name: string,
        private readonly size: string,
        private readonly fluid: Fluid | null = null
    ) {
        super(FontSize.TYPE, slugName);
    }

    public toObject(): Record<string, string | Record<string, string>> {
        const obj = {
            slug: this.slug(),
            name: this.name,
            size: this.size,
        };

        if (this.fluid) {
            Object.assign(obj, {fluid: this.fluid.toObject()});
        }

        return obj;
    }
}
