export class Blueprint extends Map<string, any> {
    public set(key: string, value: any): this {
        return super.set(key, value);
    }

    public get(key: string): any {
        return super.get(key);
    }

    public has(key: string): boolean {
        return super.has(key);
    }

    public remove(key: string): void {
        super.delete(key);
    }

    // public toArray(): Array<any> {
    //     return Array.from(this);
    // }
    //
    // public toJson() {
    //     return Array.from(this.entries())
    //     // return 'json';
    // }
}