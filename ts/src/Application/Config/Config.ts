export class Config<K extends string | number, V> {
    private storage: Record<K, V> = {} as Record<K, V>;

    constructor(initialConfig: Record<K, V> = {} as Record<K, V>) {
        this.merge(initialConfig)
    }

    get(key: K, defaultValue: V | null = null): V | null {
        return this.storage.hasOwnProperty(key) ? this.storage[key] : defaultValue;
    }

    set(key: K, value: V): void {
        if (typeof key === 'string' && key.includes('.')) {
            const parts = key.split('.');
            const lastKey = parts.pop()!;
            const lastObj = parts.reduce((acc, part) => {
                if (acc[part] === undefined) acc[part] = {};
                return acc[part];
            }, this.storage as any);
            lastObj[lastKey] = value;
            return;
        }

        this.storage[key] = value;
    }

    has(key: K): boolean {
        return this.storage.hasOwnProperty(key);
    }

    update(key: K, value: V): void {
        if (this.has(key)) {
            this.set(key, value);
        }
    }

    delete(): void {
        this.storage = {} as Record<K, V>;
    }

    merge(newConfig: Record<K, V>): void {
        this.storage = {...this.storage, ...newConfig};
    }

    toArray(): Array<[K, V]> {
        return Object.entries(this.storage) as Array<[K, V]>;
    }

    toJSON(): Record<K, V> {
        return this.storage;
    }
}