// @link https://github.com/nicojs/typed-inject
// @link https://itijs.org/

interface ContainerInterface<T> {
    get(id: string): T;
    has(id: string): boolean;
}

class NotFoundException implements Error {
    public name = 'NotFoundException';
    public message: string;

    constructor(message: string) {
        this.message = message;
    }
}

// eslint-disable-next-line @typescript-eslint/no-unused-vars
class Container<T> implements ContainerInterface<T> {
    private services: Record<string, T> = {} as Record<string, T>;

    public get(id: string): T {
        if (!this.has(id)) {
            throw new NotFoundException(`No service registered for ${id}`);
        }

        return this.services[id]!;
    }

    public has(id: string): boolean {
        return this.services[id] !== undefined;
    }

    public set(id: string, service: T): void {
        this.services[id] = service;
    }
}
