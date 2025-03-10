export default {
    plugins: [
        // import.meta.resolve("@zackad/prettier-plugin-twig"),
        import.meta.resolve('prettier-plugin-tailwindcss'),
    ],
    singleQuote: true,
    tailwindStylesheet: './assets/app.css',
    overrides: [
        {
            files: ['*.html.twig'],
            options: {
                printWidth: 120,
                tabWidth: 4,
            },
        },
    ],
};
