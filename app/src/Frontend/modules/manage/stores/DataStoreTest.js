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

window.ptest = window.dataStore;
