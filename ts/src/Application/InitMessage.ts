export class InitMessage {
    private readonly rootFolder: string;
    private readonly stylesOption: string;

    constructor(rootFolder: string, stylesOption: string) {
        this.rootFolder = rootFolder;
        this.stylesOption = stylesOption;
    }

    getRootFolder(): string {
        return this.rootFolder;
    }

    getStylesOption(): string {
        return this.stylesOption;
    }
}