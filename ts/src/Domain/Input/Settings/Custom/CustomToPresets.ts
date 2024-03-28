import {Custom} from './Custom';
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

            this.presets.add(new Custom(fullKey, value as string));
        }
    }
}
