export class Config<K extends string, V> implements Iterable<[K, V]> {
    private storage: Record<K, V> = {} as Record<K, V>;
    private temp = null as V | null;
    private default = null as V | null;

    constructor(initialConfig: Record<K, V> = {} as Record<K, V>) {
        this.merge(initialConfig);
    }

    public get(key: K, defaultValue: V | null = null): V | null {
        this.default = defaultValue;
        if (!this.has(key)) {
            return defaultValue;
        }
        return this.temp as V;
    }

    public has(key: K): boolean {
        this.temp = this.findValue(this.storage, key.split('.'), this.default);
        return this.temp !== undefined && this.temp !== this.default;
    }

    public set(key: K, value: V): boolean {
        const keys = key.split('.');
        return this.insertValue(this.storage, keys, value);
    }

    public update(key: K, value: V): void {
        this.set(key, value);
    }

    public delete(key: K): boolean {
        return this.deleteValue(this.storage, key.split('.'));
    }

    public merge(newConfig: Record<K, V>): void {
        this.storage = {...this.storage, ...newConfig};
    }

    public toArray(): Array<[K, V]> {
        return Object.entries(this.storage) as Array<[K, V]>;
    }

    public toJSON(): Record<K, V> {
        return this.storage;
    }

    public [Symbol.iterator](): IterableIterator<[K, V]> {
        const entries = this.toArray();
        let index = 0;
        return {
            next: () => {
                if (index < entries.length) {
                    const result = {value: entries[index], done: false};
                    index++;
                    return result;
                }
                return {value: undefined, done: true};
            },
        } as IterableIterator<[K, V]>;
    }

    private findValue(
        object: any,
        keys: Array<string | number>,
        defaultValue: any = null
    ): any {
        let current = object;

        for (const key of keys) {
            if (current[key] === undefined) {
                return defaultValue;
            }
            current = current[key];
        }

        return current;
    }

    private insertValue(
        object: any,
        keys: Array<string | number>,
        value: any
    ): boolean {
        let current = object;

        if (keys.length === 0) {
            return false;
        }

        for (let i = 0; i < keys.length - 1; i++) {
            const key = keys[i];

            if (key === undefined) {
                return false;
            }

            const nextKey = keys[i + 1];
            const shouldCreateArray = !isNaN(Number(nextKey));

            if (current[key] === undefined) {
                current[key] = shouldCreateArray ? [] : {};
            } else if (
                typeof current[key] !== 'object' ||
                (shouldCreateArray && !Array.isArray(current[key]))
            ) {
                throw new Error(
                    `Expected an object or array at key "${key}", found: ${typeof current[key]}`
                );
            }

            current = current[key];
        }

        const lastKey = keys[keys.length - 1];
        if (!isNaN(Number(lastKey)) && Array.isArray(current)) {
            current[Number(lastKey)] = value;
        } else if (typeof lastKey === 'string') {
            current[lastKey] = value;
        } else {
            console.error(`Invalid last key: ${lastKey}`);
            return false;
        }

        return true;
    }

    private deleteValue(object: any, keys: Array<string | number>): boolean {
        let current = object;

        for (let i = 0; i < keys.length - 1; i++) {
            const key = keys[i];

            if (key === undefined) {
                return false;
            }

            if (current[key] === undefined) {
                return false;
            }
            current = current[key];
        }

        const lastKey = keys[keys.length - 1];

        if (lastKey === undefined) {
            return false;
        }

        if (current[lastKey] !== undefined) {
            delete current[lastKey];
            return true;
        }

        return false;
    }
}