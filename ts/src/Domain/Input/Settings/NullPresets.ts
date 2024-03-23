import {PresetInterface, Presets, PresetsInterface} from '.';

export class NullPresets extends Presets {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    public add(preset: PresetInterface): PresetsInterface {
        return this;
    }

    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    public addMultiple(presets: PresetInterface[]): PresetsInterface {
        return this;
    }

    public get(key: string, defaultValue: PresetInterface | null = null): PresetInterface | null {
        return defaultValue;
    }

    public parse(content: string): string {
        return content;
    }

    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    public findAllByType(type: string) {
        return {};
    }
}
