export class DumpMessage {
    private readonly rootFolder: string;
    private readonly dryRun: boolean;

    constructor(rootFolder: string, dryRun: boolean) {
        this.rootFolder = rootFolder;
        this.dryRun = dryRun;
    }

    getRootFolder(): string {
        return this.rootFolder;
    }

    isDryRun(): boolean {
        return this.dryRun;
    }
}
