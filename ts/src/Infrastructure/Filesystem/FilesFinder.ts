import {File} from '.';

export class FilesFinder {
    public * find(rootFolder: string, extension: string): IterableIterator<File> {
        const rootFile = `${rootFolder}/theme.${extension}`;
        const rootFileInfo = new File(rootFile);
        if (rootFileInfo.exists()) {
            yield rootFileInfo;
        }
    }
}

