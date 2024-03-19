type arrayKey = string | number;

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

    private findValue(object: Record<K, V>, keys: Array<arrayKey>, defaultValue: V | null = null): V | null {
        let current: Record<K, V> | V = object;

        for (const key of keys) {
            if (this.currentIsNotValid(current, key)) {
                return defaultValue;
            }

            current = (current as Record<K, V>)[key as K];
        }

        return current as V;
    }

    private insertValue(object: Record<K, V>, keys: Array<arrayKey>, value: V): boolean {
        let current: Record<K, V> | V = object;

        if (keys.length === 0) {
            return false;
        }

        for (let i = 0; i < keys.length - 1; i++) {
            const key = keys[i];

            if (key === undefined) {
                return false;
            }

            if (current === null) {
                return false;
            }

            const nextKey = keys[i + 1];
            const shouldCreateArray = !isNaN(Number(nextKey));

            // @ts-expect-error - We know that current is not null
            if (current[key] === undefined) {
                // @ts-expect-error - At this point, current is undefined but not null
                current[key] = shouldCreateArray ? [] : {};
            }

            // @ts-expect-error - Reassigning current
            current = current[key];
        }

        const lastKey = keys[keys.length - 1];
        if (!isNaN(Number(lastKey)) && Array.isArray(current)) {
            current[Number(lastKey)] = value;
        } else if (typeof lastKey === 'string') {
            // @ts-expect-error - Reassigning current
            current[lastKey] = value;
        } else {
            console.error(`Invalid last key: ${lastKey}`);
            return false;
        }

        return true;
    }

    private deleteValue(object: Record<K, V>, keys: Array<arrayKey>): boolean {
        let current: Record<K, V> | V = object;

        for (let i = 0; i < keys.length - 1; i++) {
            const key = keys[i];

            if (key === undefined) {
                return false;
            }

            if (this.currentIsNotValid(current, key)) {
                return false;
            }

            current = (current as Record<K, V>)[key as K];
        }

        const lastKey = keys[keys.length - 1];

        if (lastKey === undefined) {
            return false;
        }

        if (this.currentIsNotValid(current, lastKey)) {
            return false;
        }

        delete (current as Record<K, V>)[lastKey as K];
        return true;
    }

    private currentIsValid(current: Record<K, V> | V, key: arrayKey) {
        return !this.currentIsNotValid(current, key);
    }

    private currentIsNotValid(current: Record<K, V> | V, key: arrayKey) {
        return current === null || typeof current !== 'object' || !(key in current) || !(current instanceof Object);
    }
}
