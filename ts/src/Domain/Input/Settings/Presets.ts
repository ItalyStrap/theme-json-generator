import {PresetInterface, PresetsInterface} from '.';
import {Config} from '../../../Infrastructure/Config';

export type PresetRecord = Record<string, PresetInterface>;

export class Presets extends Config<string, PresetInterface> implements PresetsInterface {
    public add(preset: PresetInterface): PresetsInterface {
        const key = `${preset.type()}.${preset.slug()}`;
        this.assertIsUnique(key);
        super.set(key, preset);

        return this;
    }

    public addMultiple(presets: PresetInterface[]): PresetsInterface {
        presets.forEach((preset) => this.add(preset));
        return this;
    }

    public parse(content: string): string {
        const pattern = /{{([\w.]+)}}/;
        const matches = content.match(pattern);
        if (matches === null) {
            return content;
        }

        matches.forEach((match) => {
            const key = match.replace('{{', '').replace('}}', '');
            const preset = this.get(key, null);
            if (preset === null) {
                return;
            }

            content = content.replace(match, preset.toString());
        });

        return content;
    }

    public findAllByType(type: string): PresetRecord | PresetInterface[] {
        const presetsByType = this.toObject()[type] as PresetRecord;

        if (presetsByType === undefined) {
            return {};
        }

        if (type === 'custom') {
            return presetsByType;
        }

        return Object.values(presetsByType);
    }

    private assertIsUnique(key: string): boolean {
        if (this.has(key)) {
            throw new Error(`Preset with key ${key} already exists`);
        }

        return true;
    }
}
