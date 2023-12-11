import type { CodegenConfig } from '@graphql-codegen/cli';


const config: CodegenConfig = {
    schema: './graphql/*.graphql',
    documents: ['resources/js/**/*.vue'],
    ignoreNoDocuments: true, // for better experience with the watcher
    generates: {
        './resources/js/gql/': {
            preset: 'client',
            config: {
                useTypeImports: true,
            },
            plugins: [],
        },
    },
};

export default config;
