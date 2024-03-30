import {Custom} from '.';
import {PresetsInterface} from '../PresetsInterface';
import {Preset} from '../Preset';

export class CustomToPresets {
    public constructor(
        private readonly presets: PresetsInterface,
        private readonly customs: Record<string, unknown>
    ) {}

    public process(): void {
        this.recursiveProcessCustom(this.customs);
    }

    private recursiveProcessCustom(presets: Record<string, unknown>, prefix: string = '') {
        for (const [key, value] of Object.entries(presets)) {
            const fullKey = prefix === '' ? key : `${prefix}.${key}`;

            if (value instanceof Preset) {
                this.presets.add(new Custom(fullKey, `${value}`));
                continue;
            }

            if (typeof value === 'object') {
                this.recursiveProcessCustom(value as Record<string, unknown>, fullKey);
                continue;
            }

            // @todo mirror this to the PHP version
            if (typeof value === 'string' && value === '') {
                continue;
            }

            // At this point, value should be a string, the value is cast to a string to avoid TS errors
            // Maybe to avoid this try to pass a better type to Record<string, unknown>
            this.presets.add(new Custom(fullKey, `${value}`));
        }
    }
}
