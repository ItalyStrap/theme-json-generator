import {PresetInterface, PresetsInterface} from '.';
import {Config} from '../../../Infrastructure/Config';
import {Custom} from './Custom';

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

        const replace = matches[0] ?? '';
        const key = matches[1] ?? '';
        const preset = this.get(key, this.get(`${Custom.TYPE}.${key}`));

        if (preset === null) {
            return content;
        }

        return content.replace(replace, preset.var());
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
