import {Config} from '../../Infrastructure/Config';
import {PresetInterface, PresetsInterface} from '../../Domain/Input/Settings';

export class Blueprint extends Config<string, any> {
    public setPresets(presets: PresetsInterface): boolean {
        const keys = {
            'settings.color.palette': 'color',
            'settings.color.gradients': 'gradient',
            'settings.color.duotone': 'duotone',
            'settings.typography.fontSizes': 'fontSize',
            'settings.typography.fontFamilies': 'fontFamily',
            'settings.custom': 'custom',
        };

        const filterDuplicates = (v: any, i: number, a: any[]) => a.findIndex((t) => t.slug === v.slug) === i;

        for (const [key, type] of Object.entries(keys)) {
            const foundPresets = presets.findAllByType(type);
            if (
                ('length' in foundPresets && foundPresets.length === 0) ||
                (Object.keys(foundPresets).length === 0 && foundPresets.constructor === Object)
            ) {
                continue;
            }

            const initialPresets = this.get(key, []);

            if (type === 'custom') {
                this.processCustoms(initialPresets, foundPresets, presets);
                this.set(key, initialPresets);
                continue;
            }

            this.set(
                key,
                [...initialPresets, ...this.processPresets(foundPresets as PresetInterface[], presets)].filter(
                    filterDuplicates
                )
            );
        }

        return true;
    }

    private processPresets(foundPresets: PresetInterface[], presets: PresetsInterface) {
        const newPresets: Record<string, string>[] = [];
        for (const preset of foundPresets) {
            const newItems: Record<string, string> = {};
            for (let [key, value] of Object.entries(preset.toObject())) {
                if (typeof value !== 'string') {
                    throw new Error('Preset value must be a string');
                }

                value = presets.parse(value);
                newItems[key] = value;
            }

            newPresets.push(newItems);
        }

        return newPresets;
    }

    private processCustoms(original: any, updates: any, presets: PresetsInterface) {
        Object.keys(updates).forEach((key) => {
            const value = updates[key];

            if ('toObject' in value && typeof value.toObject === 'function') {
                original[key] = presets.parse(`${value}`);
                return;
            }

            if (typeof value === 'object') {
                if (!original[key]) {
                    original[key] = {};
                }

                this.processCustoms(original[key], value, presets);
                return;
            }
        });
    }
}
