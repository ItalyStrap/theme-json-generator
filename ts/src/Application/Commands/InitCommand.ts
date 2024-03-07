import {Command} from 'commander';
//
import {CommandType} from "./CommandType";
//
import {InitMessage} from '../InitMessage';
import {Init} from "../../Domain/Output";

export class InitCommand extends Command implements CommandType {
    private init: Init;

    public constructor(init: Init) {
        super();
        this.init = init;
    }

    public configure(): void {
        this.name('init');
        this.description('Initialize theme.json file');
        this.option('-s, --styles [styles]', 'Init JSON file inside styles folder');
    }

    public execute(): InitCommand {
        this.configure();
        this.action(() => {
            const rootFolder = process.cwd();
            const stylesOption = this.opts().styles || '';

            const message = new InitMessage(rootFolder, stylesOption);
            this.init.handle(message);
        });

        return this;
    }
}