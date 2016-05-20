'use strict';
var checker = require("./checker");
var url = require("./url");

function CurentDate()
{
    var now = new Date();
    var year = now.getFullYear();       //年
    var month = now.getMonth() + 1;     //月
    var day = now.getDate();            //日
    //var hh = now.getHours();            //时
    //var mm = now.getMinutes();          //分
    var clock = year + "-";
    if(month < 10) {clock += "0";}
    clock += month + "-";
    if(day < 10) {clock += "0";}
    clock += day;
    return clock;
}
function FormatDate(now)
{
    var year = now.getFullYear();       //年
    var month = now.getMonth() + 1;     //月
    var day = now.getDate();            //日
    var clock = year + "-";
    if(month < 10) {clock += "0";}
    clock += month + "-";
    if(day < 10) {clock += "0";}
    clock += day;
    return clock;
}

function FormatDateTime(now)
{
    var year = now.getFullYear();       //年
    var month = now.getMonth() + 1;     //月
    var day = now.getDate();            //日

    var hh = now.getHours();            //时
    var mm = now.getMinutes();          //分
    var date = year + "-";
    if(month < 10) {date += "0";}
    date += month + "-";
    if(day < 10) {date += "0";}
    date += day + " ";

    if(hh < 10) {hh = "0"+parseInt(hh);}
    date += hh + ":";
    if(mm < 10) {mm = "0"+parseInt(mm);}
    date += mm;

    return date;
}

exports.Date = {
    CurentDate:CurentDate,
    FormatDate:FormatDate,
    FormatDateTime:FormatDateTime
};

function is_weixin_browser(){
    return navigator.userAgent.toLowerCase().match(/micromessenger/i)+"" === "micromessenger";
}

function set_site_title(title){
    var body = document.getElementsByTagName('body')[0];
    document.title = title;
    var iframe = document.createElement("iframe");
    iframe.setAttribute("src", "/favicon.ico");
    window.i_s_t_e = function() {
        setTimeout(function() {
            iframe.removeEventListener('load',window.i_s_t_e);
            document.body.removeChild(iframe);
        }, 0);
    }
    iframe.addEventListener('load', window.i_s_t_e);
    document.body.appendChild(iframe);
}
if(window.production){
    window.console.log=function(){};
}

exports.set_site_title = set_site_title;
exports.checker = checker;
exports.url = url;
exports.is_weixin_browser = is_weixin_browser;
