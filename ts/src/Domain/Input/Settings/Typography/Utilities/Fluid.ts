export class Fluid {
    static readonly MIN = 'min';
    static readonly MAX = 'max';

    public constructor(
        private readonly min: string,
        private readonly max: string
    ) {}

    public toObject(): Record<string, string> {
        return {
            [Fluid.MIN]: this.min,
            [Fluid.MAX]: this.max,
        };
    }
}
