const defaultConfig = require( './node_modules/@wordpress/scripts/config/webpack.config.js' );
const path = require('path')

const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const IgnoreEmitPlugin = require( 'ignore-emit-webpack-plugin' );
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');

/*const production = process.env.NODE_ENV === '';*/

module.exports = {
    ...defaultConfig,
    entry: {
        app: path.resolve( process.cwd(), 'src', 'index.js' )
    },

    optimization: {
        ...defaultConfig.optimization,
    },

    module: {
        ...defaultConfig.module,
        rules: [
            {
                test: /\.(sc|sa|c)ss$/,
                exclude: /node_modules/,
                use: [MiniCssExtractPlugin.loader, 'css-loader'],
            },
            {
                test: /\.(png|jpeg|jpg|webp|gif|svg)$/,
                loader: 'file-loader',
                options: {
                    name: 'images/[name].[ext]'
                }
            }
        ],
    },
    plugins: [
        ...defaultConfig.plugins,
        new MiniCssExtractPlugin( {
            filename: '[name].css',
        } ),
        new IgnoreEmitPlugin( [ 'editor.js', 'style.js' ] ),
        new OptimizeCssAssetsPlugin({
            cssProcessorPluginOptions: {
                preset: ['default', { discardComments: { removeAll: true } }],
            },
            canPrint: true
        })
    ],
    output: {
        filename: 'bundle.js',
        path: path.resolve(__dirname, 'build'),
    }
}