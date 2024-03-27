export class FontFace {
    public constructor(
        private readonly fontFamily: string,
        private readonly fontWeight: string,
        private readonly fontStyle: string,
        private readonly fontStretch: string,
        private readonly src: string[]
    ) {}

    public toObject(): Record<string, string | string[]> {
        return {
            fontFamily: this.fontFamily,
            fontWeight: this.fontWeight,
            fontStyle: this.fontStyle,
            fontStretch: this.fontStretch,
            src: this.src,
        };
    }
}
