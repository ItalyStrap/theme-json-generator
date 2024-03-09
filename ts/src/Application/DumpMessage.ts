export class DumpMessage {
    private readonly rootFolder: string;

    constructor(rootFolder: string) {
        this.rootFolder = rootFolder;
    }

    getRootFolder(): string {
        return this.rootFolder;
    }
}