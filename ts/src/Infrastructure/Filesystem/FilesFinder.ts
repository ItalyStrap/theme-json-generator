import * as fs from 'fs';
import * as path from 'path';
//
import {File} from '.';

export class FilesFinder {
    public *find(rootFolder: string, extension: string): IterableIterator<File> {
        const rootFile = path.join(rootFolder, `theme.${extension}`);
        const rootFileInfo = new File(rootFile);
        if (rootFileInfo.exists()) {
            yield rootFileInfo;
        }

        const stylesFolder = path.join(rootFolder, 'styles');

        if (!fs.existsSync(stylesFolder)) {
            return;
        }

        const files = fs.readdirSync(stylesFolder);
        for (const file of files) {
            if (!file.endsWith(`.${extension}`)) {
                continue;
            }

            const filePath = path.join(stylesFolder, file);
            const fileInfo = new File(filePath);
            if (fileInfo.exists()) {
                yield fileInfo;
            }
        }
    }
}
