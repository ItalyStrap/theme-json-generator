import {exec} from 'child_process';
import {EventEmitter} from 'events';
//
import {HandlerInterface, MiddlewareInterface} from '../../../bus';
//
import {DumpMessage} from '../../';
import {ValidationSubProcess} from '../../../Domain/Output/Events';
import path from 'path';

export class ValidateHandlerSubProcessMiddleware implements MiddlewareInterface<DumpMessage, number> {
    public constructor(private readonly eventEmitter: EventEmitter) {}

    public process(message: DumpMessage, handler: HandlerInterface<DumpMessage, number>): number {
        // This is the main handler and must be called before the subprocess, we save the result to return it later
        const commandResult = handler.handle(message);

        // This is the subprocess to call conditionally
        if (message.validate) {
            const themeJsonPath = path.join(process.cwd(), 'node_modules', 'theme-json-generator', 'bin', 'theme-json');
            const command = `node ${themeJsonPath} validate`;
            exec(command, (error, stdout, stderr) => {
                if (error) {
                    console.error(`error: ${error.message}`);
                    return;
                }
                if (stderr) {
                    console.error(`stderr: ${stderr}`);
                    return;
                }

                this.eventEmitter.emit(ValidationSubProcess.name, new ValidationSubProcess(stdout));
            });
        }

        // Now we return the result of the main handler
        return commandResult;
    }
}
