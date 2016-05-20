'use strict';

var DataStore = require('./DataStore');

DataStore.prototype.test_loadSetting = function(){
    this.loadSetting();
};

DataStore.prototype.test_getAuthInfo = function(){
    this.getAuthInfo();
};

DataStore.prototype.test_logout = function(){
    this.logout((result, error) => {
        console.log(result, error);
    });
};

DataStore.prototype.test_begin_task = function(){
    var mission_id = 8;
    this.begin_task(mission_id,function(result,error){
        console.log(result,error)
    });
};


DataStore.prototype.test_do_verify = function(){
    var mission_id = 8;
    var task_key = 1;
    var pics = "http://";
    var note = "note";
    this.do_verify(mission_id,task_key,pics,note,function(result,error){
        console.log(result,error)
    });
};

DataStore.prototype.test_my_task = function(){
    this.my_task(function(result,error){
        console.log(result,error)
    });
};

DataStore.prototype.test_mission_list = function(){
    var note = "note";
    this.mission_list(function(result,error){
        console.log(result,error)
    });
};
DataStore.prototype.test_mission_detail = function(){
    var $mission_id = 7;
    this.mission_detail($mission_id,function(result,error){
        console.log(result,error)
    });
};

window.ptest = window.dataStore;
