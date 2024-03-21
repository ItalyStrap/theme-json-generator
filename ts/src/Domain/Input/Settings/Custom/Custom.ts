import {Preset, PresetInterface} from '../';

export class Custom extends Preset implements PresetInterface {
    static readonly TYPE = 'custom';

    private readonly name: string;

    public constructor(
        protected readonly key: string,
        private readonly value: string
    ) {
        super(Custom.TYPE, key);
        this.name = this.key
            .split('.')
            .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
    }

    public slug(): string {
        return this.key;
    }

    public prop(): string {
        return `--wp--${this.type()}--${this.camelToSnake(this.key.replace('.', '--'))}`;
    }

    public toObject(): Record<string, string | Record<string, unknown>> {
        return {
            key: this.key,
            name: this.name,
            value: this.value,
        };
    }

    public toString(): string {
        return this.value;
    }
}
