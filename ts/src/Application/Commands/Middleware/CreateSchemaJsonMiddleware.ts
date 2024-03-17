import * as fs from 'fs';
//
import {HandlerInterface, MiddlewareInterface} from '../../../bus';
//
import {ValidateMessage} from '../../ValidateMessage';
import {File} from '../../../Infrastructure/Filesystem';

export class CreateSchemaJsonMiddleware implements MiddlewareInterface<ValidateMessage, number> {
    public process(message: ValidateMessage, handler: HandlerInterface<ValidateMessage, number>): number {
        const fileSchema = message.fileSchema;
        if (!fileSchema.exists() || this.isFileSchemaOlderThanOneWeek(fileSchema)) {
            this.createFileSchema(fileSchema)
                .then(() => {
                    console.log('File schema created');
                })
                .catch((error) => {
                    console.error('Error writing file:', error);
                });
        }

        return handler.handle(message);
    }

    private async createFileSchema(fileSchema: File) {
        try {
            const response = await fetch(
                'https://raw.githubusercontent.com/WordPress/gutenberg/trunk/schemas/json/theme.json'
            );

            if (!response.ok) {
                throw new Error(`Errore HTTP: ${response.status}`);
            }

            const schemaContent = await response.text();

            fs.writeFileSync(fileSchema.getFilePath(), schemaContent, 'utf8');
        } catch (error) {
            console.error('Error:', error);
        }
    }

    private isFileSchemaOlderThanOneWeek(fileSchema: File): boolean {
        return fileSchema.getMTime() < Date.now() - 7 * 24 * 60 * 60 * 1000;
    }
}
