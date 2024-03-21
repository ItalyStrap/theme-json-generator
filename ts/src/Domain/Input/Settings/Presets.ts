import {PresetInterface, PresetsInterface} from '.';
import {Config} from '../../../Infrastructure/Config';

export class Presets extends Config<string, PresetInterface> implements PresetsInterface {
    public add(preset: PresetInterface): PresetsInterface {
        const key = `${preset.type()}.${preset.slug()}`;
        this.assertIsUnique(key, preset);
        super.set(key, preset);

        return this;
    }

    public addMultiple(presets: PresetInterface[]): PresetsInterface {
        presets.forEach((preset) => this.add(preset));
        return this;
    }

    public parse(content: string): string {
        return content;
    }

    public findAllByType(type: string): PresetInterface[] | [] | {} {
        const presetsByType = this.toObject()[type];
        if (presetsByType === undefined) {
            return [];
        }

        if (type === 'custom') {
            console.log(presetsByType);
            return presetsByType;
        }

        return Object.values(presetsByType);
    }

    private assertIsUnique(key: string, preset: PresetInterface): boolean {
        if (this.has(key)) {
            throw new Error(`Preset with key ${key} already exists`);
        }

        return true;
    }
}
