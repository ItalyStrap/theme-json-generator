import {PresetsInterface} from '../Settings';
import {NullPresets} from '../Settings/NullPresets';

export type CommonPresets = PresetsInterface | null;
export type CommonProperties = Record<string, string>;

export class Common<T extends typeof Common> {
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
         * string | PresetInterface | null
         */
        let newValue = this.presets!.get(value, value);

        if (
            newValue !== null &&
            typeof newValue === 'object' &&
            'var' in newValue &&
            typeof newValue.var === 'function'
        ) {
            newValue = newValue.var() as string;
        }

        this.properties[key] = this.presets!.parse(newValue as string);
        const result = this.properties;
        this.properties = {};
        return new (this.constructor as T)(this.presets, result) as this;
    }

    public toJson(): CommonProperties {
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
