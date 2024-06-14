const path = require( 'path' );
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const devMode = process.env.NODE_ENV !== 'production';

const entryPoint = {
    admin   : './src/admin/main.js',
    frontend: [
        './src/frontend/storekit-avatar-upload.js',
        './src/frontend/storekit-terms-conditions.js',
        './src/styles/frontend/main.js'
    ]
}

const storekitConfig = {
    entry   : entryPoint,
    mode    : devMode ? 'development' : 'production',
    output  : {
        path    : path.resolve( __dirname, './assets/js' ),
        filename: devMode ? '[name].js': '[name].min.js',
        clean   : true
    },
    module  : {
        rules   : [
            {
                test: /\.(js|jsx)$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: [ '@babel/preset-env', '@babel/preset-react' ]
                    }
                }
            },
            {
                test: /\.css$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader'
                ]
            },
            {
                test: /\.scss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'sass-loader'
                ]
            },
            {
                test: /\.svg$/,
                use: [ '@svgr/webpack', 'file-loader' ]
            }
        ]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: devMode ? '../css/[name].css' : '../css/[name].min.css', // Ensure CSS files are placed in the correct directory
        })
    ]
}

module.exports = storekitConfig;