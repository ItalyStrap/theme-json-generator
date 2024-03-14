import * as fs from 'fs';
//
import {HandlerInterface, MiddlewareInterface} from '../../../../bus';
//
import {ValidateMessage} from '../../ValidateMessage';

export class DeleteSchemaJsonMiddleware
    implements MiddlewareInterface<ValidateMessage, number>
{
    public process(
        message: ValidateMessage,
        handler: HandlerInterface<ValidateMessage, number>
    ): number {
        const fileSchema = message.getFileSchema();
        if (message.isForce() && fileSchema.exists()) {
            fs.unlinkSync(fileSchema.getFilePath());
        }

        return handler.handle(message);
    }
}
