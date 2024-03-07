import * as fs from 'fs';

export class File {
    // Create method like the \SplFileInfo php class
    public static create(filePath: string): File {
        return new File(filePath);
    }

    private readonly filePath: string;

    public constructor(filePath: string) {
        this.filePath = filePath;
    }

    public getExtension(): string {
        return this.filePath.split('.').pop() || '';
    }

    public getFileName(): string {
        return this.filePath.split('/').pop() || '';
    }

    public getShortName(): string {
        return this.getBaseName().split('.').shift() || '';
    }

    public getBaseName(omitExtension: string | null | undefined = null): string {
        return this.filePath.split('/').pop() || '';
    }

    public getFilePath(): string {
        return this.filePath;
    }

    public exists(): boolean {
        return fs.existsSync(this.filePath);
    }

    public isFile(): boolean {
        return fs.lstatSync(this.filePath).isFile();
    }

    public isDir(): boolean {
        return fs.lstatSync(this.filePath).isDirectory();
    }

    public toString(): string {
        return this.filePath;
    }
}