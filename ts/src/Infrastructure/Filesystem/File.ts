import * as fs from 'fs';
import * as path from 'path';

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
        return path.extname(this.filePath);
    }

    public getFileName(): string {
        return path.basename(this.filePath);
    }

    public getShortName(): string {
        return this.getBaseName().split('.').shift() ?? '';
    }

    public getBaseName(omitExtension: string | undefined = undefined): string {
        return path.basename(this.filePath, omitExtension);
    }

    public getFilePath(): string {
        return this.filePath;
    }

    public getMTime(): number {
        return fs.statSync(this.filePath).mtimeMs;
    }

    public exists(): boolean {
        return fs.existsSync(this.filePath);
    }

    public isFile(): boolean {
        return fs.statSync(this.filePath).isFile();
    }

    public isDir(): boolean {
        return fs.statSync(this.filePath).isDirectory();
    }

    public toString(): string {
        return this.filePath;
    }
}
