import {Preset, PresetsInterface, NullPresets} from '../Settings';
import {JsonSerializable} from '../../../Infrastructure/Json';

export type CommonPresets = PresetsInterface | null;
export type CommonProperties = Record<string, string>;

export class Common<T extends typeof Common> implements JsonSerializable<CommonProperties> {
    public constructor(
        protected readonly presets: CommonPresets = null,
        private properties: CommonProperties = {}
    ) {
        if (this.presets === null) {
            this.presets = new NullPresets();
        }
    }

    public property(property: string, value: string): this {
        return this.setProperty(property, value);
    }

    protected setProperty(key: string, value: string): this {
        /**
         * PresetInterface | string
         */
        let presetOrValue = this.presets!.get(value, value);

        if (presetOrValue instanceof Preset) {
            presetOrValue = presetOrValue.var();
        }

        if (typeof presetOrValue === 'string') {
            this.properties[key] = this.presets!.parse(presetOrValue);
        }

        const result = this.properties;
        this.properties = {};
        return new (this.constructor as T)(this.presets, result) as this;
    }

    public toJSON(): CommonProperties {
        const result = Object.entries(this.properties).reduce((acc, [key, value]) => {
            if (value !== '') {
                acc[key] = value;
            }

            return acc;
        }, {} as CommonProperties);

        this.properties = {};
        return result;
    }
}
