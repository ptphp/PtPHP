var webpack = require('webpack');
var path = require('path');
var autoprefixer = require('autoprefixer');
var ExtractTextPlugin = require("extract-text-webpack-plugin");
var AssetsWebpackPlugin = require('assets-webpack-plugin');

var HtmlWebpackPlugin = require('html-webpack-plugin');
var OpenBrowserPlugin = require('open-browser-webpack-plugin');

var config = require('./app/config/variables');
var IS_PRODUCTION =  process.env.NODE_ENV === "production";

webpackConfig = {
    entry: Object.assign(config.webpack.entry, {
        vendor: ['react', 'classnames', 'react-router', 'react-dom', 'react-addons-css-transition-group'],
        antd:['antd']
    }),
    output: {
        filename: config.webpack.outputFilename, // Bundle filename pattern
        path: config.paths.build  // Put bundle files in this directory (Note: dev server does not generate bundle files)
    },
    module: {
        loaders:[
            {
                test: /\.js[x]?$/,
                exclude: /node_modules/,
                loader: 'babel'
            }, {
                test: /\.(png|jpg|svg)$/,
                loader: config.webpack.imgLoader
            }
        ]
    },
    resolve: {
        extensions: ['', '.js', '.jsx',".css",".less"]
    },
    postcss: [autoprefixer],
    plugins: []
};

Array.prototype.push.apply(webpackConfig.plugins, [
    new webpack.DefinePlugin({DEBUG: process.env.NODE_ENV !== 'production'}),
    //https://github.com/webpack/webpack/tree/master/examples/multiple-commons-chunks
    new webpack.optimize.CommonsChunkPlugin({
        names: ['antd', 'vendor'],
        minChunks: Infinity,
    })
]);

if(IS_PRODUCTION){ //生产环境
    Array.prototype.push.apply(webpackConfig.module.loaders, [

        {
            test: /\.css/,
            //loader: ExtractTextPlugin.extract('style', 'css?minimize', 'postcss')
            loader: ExtractTextPlugin.extract('style', 'css', 'postcss')
        },
        {
            test: /\.less$/,
            //loader:"style!css!postcss!less",
            loader:ExtractTextPlugin.extract('style', 'css-loader!less-loader')
        },
    ]);


    Array.prototype.push.apply(webpackConfig.plugins, [
        new ExtractTextPlugin( config.webpack.cssPath, {
            // 当allChunks指定为false时，css loader必须指定怎么处理
            // additional chunk所依赖的css，即指定`ExtractTextPlugin.extract()`
            // 第一个参数`notExtractLoader`，一般是使用style-loader
            // @see https://github.com/webpack/extract-text-webpack-plugin
            allChunks: false
        }),
        new webpack.optimize.UglifyJsPlugin({
            sourceMap: false,
            mangle: false,
            compressor: {
                warnings: false // Don't complain about things like removing unreachable code
            }
        }),
        new AssetsWebpackPlugin({
            filename: config.webpack.assetsFilename,
            path: config.webpack.assetsPath,
            prettyPrint: true
        })
    ]);

    //for(var entry in entrys){
    //    if(entry == "vendor") continue;
    //    webpackConfig.plugins.push(new HtmlWebpackPlugin({
    //        title:"test",
    //        filename:"./"+entry+".html",
    //        template: path.join(__dirname, 'app/index.ejs'),
    //        chunks : ['vendor', entry],
    //        inject:"head",
    //        setting:{},
    //        //cache: true,
    //        //hash:true,
    //    }));
    //}
}else{
    Array.prototype.push.apply(webpackConfig.module.loaders, [
        {
            test: /\.less$/,
            loader: 'style!css!postcss!less'
        },
        {
            test: /\.css/,
            loader:'style!css!postcss',
            include: [
                config.paths.source,
                config.paths.antdStyle,
                config.paths.simditorStyle
            ]
        }
    ]);
    //webpackConfig.plugins.push(new OpenBrowserPlugin({ url: 'http://localhost:8080' }));
}

module.exports = webpackConfig;
