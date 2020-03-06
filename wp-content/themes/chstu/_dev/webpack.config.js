const
    Webpack = require('webpack'),
    WebAppWebpackPlugin = require('webapp-webpack-plugin'),
    MiniCssExtractPlugin = require('mini-css-extract-plugin'),
    UglifyJsPlugin = require('uglifyjs-webpack-plugin'),
    CleanWebpackPlugin = require('clean-webpack-plugin'),
    production = process.argv.indexOf('-p') !== -1,
    path = require('path'),
    bundleDirName = 'bundle',
    bundlePath = `../${bundleDirName}`;

console.log(`${production ? '[PRODUCTION' : '[DEVELOPMENT'} BUILD]`);

module.exports = {
    entry: {
        'index': path.resolve(__dirname, 'templates/index/index'),
        // 'category': path.resolve(__dirname, 'templates/category/category'),
        // 'single': path.resolve(__dirname, 'templates/single/single'),
        // 'office': path.resolve(__dirname, 'templates/pages/office/office'),
        // 'our-team': path.resolve(__dirname, 'templates/pages/our-team/our-team')
    },
    output: {
        publicPath: `${bundlePath}/`,
        path: path.resolve(__dirname, bundlePath),
        filename: '[name].js'
    },
    devtool: production ? false : 'inline-cheap-module-source-map',
    module: {
        rules: [

            // JS
            {
                test: /\.js$/,
                use: [
                    {
                        loader: 'babel-loader',
                        query: {
                            presets: ['@babel/preset-env']
                        }
                    }
                ],
            },

            // SCSS
            {
                test: /\.(sa|sc|c)ss$/,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                        options: {
                            publicPath: ''
                        }
                    },
                    {
                        loader: 'css-loader'
                    },
                    {
                        loader: 'postcss-loader'
                    },
                    {
                        loader: 'sass-loader'
                    }
                ]
            },

            // Resources
            {
                test: /.(jpe?g|png|svg|gif|woff(2)?|eot|ttf)(\?[a-z0-9=\.]+)?$/,
                oneOf: [
                    {
                        resourceQuery: /inline-css/,
                        use: 'url-loader'
                    },
                    {
                        resourceQuery: /inline-js/,
                        use: 'svg-inline-loader'
                    },
                    {
                        resourceQuery: /external/,
                        use: 'file-loader?name=./[name].[ext]'
                    }
                ]
            },

            // Localization
            {
                test: /\.po$/,
                loader: 'file-loader?name=./[name].mo!po2mo-loader'
            },

            // PUG to HTML
            /*{
                test: /\.pug$/,
                exclude: /(node_modules)/,
                use: [
                    {loader: 'file-loader?name=../../[name].html'},
                    {loader: 'extract-loader'},
                    {loader: 'html-loader?attrs=img:src!false'},
                    {loader: 'pug-html-loader'}
                ]
            }*/

        ]
    },

    plugins: [
        new Webpack.DefinePlugin({'process.env':{'NODE_ENV' : production ? `"productionuction"` : `""`}}),
        new MiniCssExtractPlugin({
            filename: '[name].css',
            chunkFilename: '[id].css'
        }),
        new WebAppWebpackPlugin({
            logo: './resources/images/favicon.svg',
            prefix: '../bundle',
            cache: true,
            inject: false,
            favicons: {
                appName: 'CHSTU',
                // appDescription: null,
                // developerName: null,
                // developerURL: null, // prevent retrieving from the nearest package.json
                background: '#ffffff',
                theme_color: '#0f5091',
                icons: {
                    coast: false,
                    yandex: false
                }
            }
        }),
        new CleanWebpackPlugin([bundleDirName],{
            root: path.resolve(__dirname, `..`),
            verbose: true
        })
    ],
    optimization: {
        minimizer: [
            new UglifyJsPlugin({
                parallel: true,
                sourceMap: false,
                // extractComments: /@(?:license)/i
                extractComments: (node, comment) => {
                    console.log('comment', String(comment).search(/@(?:license)/i));
                    /*console.log(comment); */
                    return String(comment).search(/@(?:license)/i) !== -1;
                }
            })
        ]
    }
};