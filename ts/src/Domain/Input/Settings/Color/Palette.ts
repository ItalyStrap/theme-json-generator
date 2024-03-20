import {PresetInterface} from '../PresetInterface';
import {ColorInterface} from './Utilities';
import {Preset} from '../Preset';

export class Palette extends Preset implements PresetInterface {
    static readonly TYPE = 'color';

    public constructor(
        protected readonly slugName: string,
        private readonly name: string,
        private readonly color: ColorInterface
    ) {
        super(Palette.TYPE, slugName);
    }

    public toArray(): Record<string, string> {
        return {
            slug: this.slug(),
            name: this.name,
            color: this.color.toString(),
        };
    }
}
