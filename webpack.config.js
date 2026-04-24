const path = require('path');

module.exports = {
    entry: {
        'resource.bundle': './src/Frontend/ts/resource.ts'
    },
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                use: {
                    loader: 'ts-loader',
                    options: {
                        transpileOnly: true
                    }
                },
                exclude: /node_modules/,
            }
        ],
    },
    resolve: {
        extensions: ['.tsx', '.ts', '.js'],
    },
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, 'public/js'),
        library: 'AlxarafeResource',
        libraryTarget: 'umd',
    },
    mode: 'production',
    watchOptions: {
        poll: 1000,
        ignored: /node_modules/,
    },
};
