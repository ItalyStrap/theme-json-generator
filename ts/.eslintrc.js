module.exports = {
    root: true,
    extends: ['eslint:recommended', 'plugin:@typescript-eslint/recommended'],
    parser: '@typescript-eslint/parser',
    parserOptions: {project: ['./tsconfig.json']},
    plugins: ['@typescript-eslint'],
    rules: {
        '@typescript-eslint/strict-boolean-expressions': [
            2,
            {
                allowString: false,
                allowNumber: false,
            },
        ],
        // 'sort-imports': [
        //     'error',
        //     {
        //         ignoreDeclarationSort: false,
        //         allowSeparatedGroups: true,
        //     },
        // ],
    },
    ignorePatterns: ['src/**/*.test.ts'],
};
