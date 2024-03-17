import {HandlerInterface, MiddlewareInterface} from '.';

export class DecorateBus<T extends object, R> implements HandlerInterface<T, R> {
    public constructor(
        private middleware: MiddlewareInterface<T, R>,
        private nextHandler: HandlerInterface<T, R>
    ) {}

    public handle(message: T): R {
        return this.middleware.process(message, this.nextHandler);
    }
}
