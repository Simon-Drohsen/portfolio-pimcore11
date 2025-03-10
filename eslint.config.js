import js from '@eslint/js';
import prettierRecommended from 'eslint-plugin-prettier/recommended';
import globals from 'globals';

export default [
    js.configs.recommended,
    prettierRecommended,
    {
        ignores: ['public/**/*', 'var/**/*', 'vendor/**/*', '.pnp*'],
    },
    {
        files: ['**/*.js'],
        languageOptions: {
            ecmaVersion: 'latest',
            sourceType: 'module',
            parserOptions: {
                requireConfigFile: false,
            },
            globals: {
                ...globals.browser,
                ...globals.node,
            },
        },
        rules: {
            'no-console':
                process.env.NODE_ENV === 'production' ? 'error' : 'off',
            'no-debugger':
                process.env.NODE_ENV === 'production' ? 'error' : 'off',
            quotes: ['error', 'single', { avoidEscape: true }],
        },
    },
];
