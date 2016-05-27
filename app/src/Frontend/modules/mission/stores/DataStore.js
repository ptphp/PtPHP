'use strict';

var EventEmitter = require('events').EventEmitter;
var reqwest = require("reqwest");
var Mission = require("./reducer/Mission");
//var defaultData = require('./default');
//var defaults = require('defaults');

if (typeof Object.assign != 'function') {
    (function () {
        Object.assign = function (target) {
            'use strict';
            if (target === undefined || target === null) {
                throw new TypeError('Cannot convert undefined or null to object');
            }

            var output = Object(target);
            for (var index = 1; index < arguments.length; index++) {
                var source = arguments[index];
                if (source !== undefined && source !== null) {
                    for (var nextKey in source) {
                        if (source.hasOwnProperty(nextKey)) {
                            output[nextKey] = source[nextKey];
                        }
                    }
                }
            }
            return output;
        };
    })();
}

var setting = {
    cache_prefix: 'sc_',
    refreshInterval: 5000,
    apiUrl: window.apiUrl
};
if( window.production){
    window.console.log = function (){};
}
function DataStore() {
    EventEmitter.call(this);
    Mission.call(this);
    this.appSetting = {};
}

Object.assign(DataStore.prototype, EventEmitter.prototype);
Object.assign(DataStore.prototype, Mission.prototype);

DataStore.prototype.actionPost = function (controller,action,data,callback) {
    var packet = Object.assign({
        controller:controller,
        action: action,
        access_token:this.getAccessToken()
    }, data);
    reqwest({
        url: setting.apiUrl,
        method: 'post',
        timeout:15000,
        data: packet,
        type: 'json',
        success: (response) => {
            if(response.access_token) {this.setAccessToken(response.access_token);}
            //console.log(response);
            if(response.error === 8001){
                this.setCache("is_logined",0);
                this.emit('not-login');
            }
            //if(response.error > 0) { message.warn(response.result);}
            if(callback) {callback(response.result,response.error);}
        }
    });
};
DataStore.prototype.actionPostLdt = function (controller,action,data,callback) {
    var packet = Object.assign({
        controller:controller,
        action: action,
        access_token:this.getAccessToken()
    }, data);
    reqwest({
        url: setting.apiUrl.replace("/mission",""),
        method: 'post',
        timeout:15000,
        data: packet,
        type: 'json',
        success: (response) => {
            if(response.access_token) {this.setAccessToken(response.access_token);}
            //console.log(response);
            if(response.error === 8001){
                this.setCache("is_logined",0);
                this.emit('not-login');
            }
            //if(response.error > 0) { message.warn(response.result);}
            if(callback) {callback(response.result,response.error);}
        }
    });
};

window.permission_check = function($node){
    var res = false;
    if(!window.dataStore.appSetting.permissions || !window.dataStore.appSetting.permissions[$node]){
        if(window.dataStore.appSetting.is_super_admin){
            res = true;
        }
    }else{
        res = true;
    }
    return res;
};

DataStore.prototype.setCache = function(key,value){
    window.localStorage[setting.cache_prefix + key] = JSON.stringify(value);
};
DataStore.prototype.getCache = function(key){
    let v = window.localStorage[setting.cache_prefix + key];
    return v ? JSON.parse(v) : null;
};
DataStore.prototype.getAccessToken = function(){
    return this.getCache("access_token");
};
DataStore.prototype.setAccessToken = function(token){
    return this.setCache("access_token",token);
};
DataStore.prototype.synchronize = function (cb) {
    //console.log("synchronize");
    window.dataStore.actionPost("admin/synchronize","data",{
        //timestamp: this.cache.timestamp
    },(result,error)=>{
        //console.log(result,error);
        if(!error){
            window.sync_time_id = setTimeout(window.dataStore.synchronize,window.dataStore.appSetting.setting.interval);
        }
        if(cb){cb(result,error);}
    });
};

DataStore.prototype.loadSetting = function(cb){
    this.actionPost("admin/system/setting","info",{},(result,error)=>{
        //console.log(result,error);
        if(!error){
            this.setAppSetting(result);
        }
        if(cb){cb(result,error);}
    });
};
DataStore.prototype.setAppSetting = function(app_setting){
    this.appSetting = app_setting;
};
DataStore.prototype.getAppSetting = function(key){
    return this.appSetting[key];
};
DataStore.prototype.getAuthInfo = function(cb){
    this.actionPost("admin/auth","info",{},function(result,error){
        //console.log(result,error);
        if(cb) { cb(result,error); }
    });
};
DataStore.prototype.doLogin = function(data,cb){
    this.actionPost("admin/auth","login",data,function(result,error){
        //console.log(result,error);
        if(cb) { cb(result,error); }
    });
};
DataStore.prototype.logout = function(cb){
    this.setCache("is_logined",0);
    this.setAccessToken("");
    this.actionPost("admin/auth","logout",{},function(result,error){
        location.reload();
        if(cb) { cb(result,error); }
    });
};
DataStore.prototype.setLogin = function(){
    this.setCache("is_logined",1);
};

DataStore.prototype.getWechatLoginUrl = function(){
    return setting.apiUrl + "?controller=wechat/auth&action=login";
};

window.API_URL = setting.apiUrl;
window.set_access_token = function(response){
    window.dataStore.setAccessToken(response.access_token);
};
window.get_access_token = function(){
    return window.dataStore.getAccessToken();
};


DataStore.prototype.begin_task = function(mission_id,callback){
    this.actionPost("mission/task","begin_task",{mission_id:mission_id},function(result,error){
        if(callback) callback(result,error);
    });
};

DataStore.prototype.do_verify = function($mession_id,$task_key,$pics,$note,callback){
    this.actionPost("mission/task","do_verify",{
        mission_id:$mession_id,
        task_key:$task_key,
        pics:$pics,
        note:$note
    },function(result,error){
        if(callback) callback(result,error);
    });
};
DataStore.prototype.my_task = function(callback){
    this.actionPost("mission/task","my_task",{},function(result,error){
        if(callback) callback(result,error);
    });
};

DataStore.prototype.my_task = function(callback){
    this.actionPost("mission/task","my_task",{},function(result,error){
        if(callback) callback(result,error);
    });
};
DataStore.prototype.mission_list = function(callback){
    this.actionPost("mission","list",{},(result,error)=>{
        if(callback) callback(result,error);
    });
};
DataStore.prototype.mission_detail = function(mission_id,callback){
    this.actionPost("mission","detail",{id:mission_id},(result,error)=>{
        if(callback) callback(result,error);
    });
};
DataStore.prototype.mission_example = function(task_id,callback){
    this.actionPost("mission","example",{task_id:task_id},(result,error)=>{
        if(callback) callback(result,error);
    });
};

DataStore.prototype.auth_get_reset_pwd_captcha = function(mobile,callback){
    this.actionPostLdt("captcha","reset_pwd",{mobile},(result,error)=>{
        if(callback) callback(result,error);
    });
};
DataStore.prototype.auth_get_reg_captcha = function(mobile,callback){
    this.actionPostLdt("captcha","reg",{mobile},(result,error)=>{
        if(callback) callback(result,error);
    });
};
DataStore.prototype.auth_do_reg = function(mobile,password,captcha,callback){
    this.actionPostLdt("auth","reg",{username:mobile,password,captcha},(result,error)=>{
        if(callback) callback(result,error);
    });
};
DataStore.prototype.auth_do_reset_pwd = function(mobile,password,captcha,callback){
    this.actionPostLdt("auth","reset_pwd",{username:mobile,password,captcha},(result,error)=>{
        if(callback) callback(result,error);
    });
};

DataStore.prototype.set_redirect = function(url){
    this.setCache("redirect_url",url);
};

DataStore.prototype.get_redirect = function(){
    let url = this.getCache("redirect_url");
    return url ? url:null;
};



DataStore.prototype.auth_do_login = function(username,password,callback){
    this.actionPostLdt("auth","login",{username,password},(result,error)=>{
        if(callback) callback(result,error);
    });
};

DataStore.prototype.upload_pic = function(content,callback){
    this.actionPost("mission/tool","upload_content_base64",{content:content},(result,error)=>{
        if(callback) callback(result,error);
    });
};


module.exports = DataStore;
