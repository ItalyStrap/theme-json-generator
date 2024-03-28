import {Config} from '../../Infrastructure/Config';
import {Preset, PresetInterface, PresetRecord, PresetsInterface} from '../../Domain/Input/Settings';

// eslint-disable-next-line @typescript-eslint/no-explicit-any
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

        const filterDuplicates = function (v: {slug: string}, i: number, a: {slug: string}[]) {
            return a.findIndex((t) => t.slug === v.slug) === i;
        };

        for (const [key, type] of Object.entries(keys)) {
            const foundPresets = presets.findAllByType(type);
            if (
                ('length' in foundPresets && foundPresets.length === 0) ||
                (Object.keys(foundPresets).length === 0 && foundPresets.constructor === Object)
            ) {
                continue;
            }

            let initialPresets;

            if (type === 'custom') {
                // Default value for custom presets must be an object
                initialPresets = this.get(key, {});
                this.processCustoms(initialPresets, foundPresets as PresetRecord, presets);
                this.set(key, initialPresets);
                continue;
            }

            // Default value for other presets must be an array
            initialPresets = this.get(key, []);
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
            // eslint-disable-next-line prefer-const
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

    /**
     * @todo Remove any
     */
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    private processCustoms(initialPresets: any, foundPresets: any, presets: PresetsInterface) {
        Object.keys(foundPresets).forEach((key) => {
            const value = foundPresets[key];

            if (value === undefined) {
                return;
            }

            if (value instanceof Preset) {
                initialPresets[key] = presets.parse(`${value}`);
                return;
            }

            if (typeof value === 'object') {
                if (initialPresets[key] === undefined) {
                    initialPresets[key] = {};
                }

                this.processCustoms(initialPresets[key], value, presets);
                return;
            }
        });
    }
}
