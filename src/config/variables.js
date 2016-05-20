'use strict';
var app = require("./app.json");
var path = require('path');
var chalk = require('chalk');

// This and anything in config.paths must be absolute.
var ROOT_PATH = path.resolve(__dirname, '../..');

var NODE_MODULES_DIRNAME = 'node_modules';
var SOURCE_DIRNAME = 'src';
var WEB_ROOT_DIRNAME = 'webroot';
var ASSETS_DIRNAME = 'mission/static';
var BUILD_DIRNAME = '/mission/static/build';

var WEBPACK_PROXY = 'webpack';
var IS_PRODUCTION =  process.env.NODE_ENV === "production";
var _app = {};
for(var app_name in app){
    if(app[app_name]['status'] == 1){
        _app[app_name] = {};
    }
}
//console.log(app)
var config = {
    app:_app,
    publicPaths: {
        assets: '/' + ASSETS_DIRNAME + '/',
    },
    paths: {
        root: ROOT_PATH,
        webRoot: path.join(ROOT_PATH, WEB_ROOT_DIRNAME),
        assets: path.join(ROOT_PATH, WEB_ROOT_DIRNAME, ASSETS_DIRNAME),
        build: path.join(ROOT_PATH, WEB_ROOT_DIRNAME, BUILD_DIRNAME), // Do not keep any non-generated files here.
        source: path.join(ROOT_PATH, SOURCE_DIRNAME),
        components: path.join(ROOT_PATH, SOURCE_DIRNAME, 'components'),

        antdStyle:path.join(ROOT_PATH, NODE_MODULES_DIRNAME,'antd', 'lib'),
        simditorStyle:path.join(ROOT_PATH, NODE_MODULES_DIRNAME,'simditor', 'styles'),
    },
    webpack: {
        entry:{},

        // Webpack bundle filename
        outputFilename: IS_PRODUCTION ? 'js/[chunkhash:8].[name].min.js' : WEBPACK_PROXY + "/[name].js",
        imgLoader: IS_PRODUCTION ? 'url?limit=25000&name=img/[hash:8].[name].[ext]' : 'url?limit=25000&name='+WEBPACK_PROXY+'/img/[hash:8].[name].[ext]',
        cssPath: IS_PRODUCTION ? 'css/[contenthash:8].[name].min.css' : 'css/[contenthash:8].[name].min.css',

        assetsFilename: path.join(BUILD_DIRNAME, 'webpack-assets.json'),
        assetsPath: path.join(ROOT_PATH, WEB_ROOT_DIRNAME),
    }
};

for(var app_name in config.app){
    config.webpack.entry[app_name] = path.join(ROOT_PATH, SOURCE_DIRNAME,"app",app_name,"entry.js");
}

Object.freeze(config); // On a separate line because IntelliJ's JS code assistance is not very smart :(
module.exports = config;
