import {File} from '../Infrastructure/Filesystem';

export class ValidateMessage {
    constructor(
        private readonly rootFolder: string,
        private readonly fileSchema: File,
        private readonly force: boolean = false
    ) {}

    public getRootFolder(): string {
        return this.rootFolder;
    }

    public getFileSchema(): File {
        return this.fileSchema;
    }

    public isForce(): boolean {
        return this.force;
    }
}
