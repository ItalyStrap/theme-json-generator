import {Command} from 'commander';

export const CommandCode = {
    // see https://tldp.org/LDP/abs/html/exitcodes.html
    SUCCESS: 0,
    FAILURE: 1,
    INVALID: 2,
};

export interface CommandInterface {
    configure(): void;
    execute(): Command;
}
