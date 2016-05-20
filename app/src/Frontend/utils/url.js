'use strict';
exports.getPageName = getPageName;
exports.getQuery = getQuery;
exports.getHash = getHash;
exports.parse = parseUrl;
exports.serialize = serialize;

function getPageName() {
    var u = location.pathname;
    var a = u.split(/\//);
    var m = a.pop().match(/(?:^|\/)($|[^\.]+)/);

    return m[1] ? m[1] : 'index';
}

function getQuery(name) {
    var u = location.search.slice(1);
    if (!location.search){
        u = location.hash.slice(1);
    }
    var re = new RegExp(name + '=([^&\\s+]+)');
    var m = u.match(re);
    var v = m ? m[1] : '';

    return (v === '' || isNaN(v)) ? v : v - 0;
}

function getHash(name) {
    var u = location.hash.slice(1);
    var re = new RegExp(name + '=([^&\\s+]+)');
    var m = u.match(re);
    var v = m ? m[1] : '';

    return (v === '' || isNaN(v)) ? v : v - 0;
}

function parseUrl(url) {
    var a = document.createElement('a');

    a.href = (url || 'x.html');

    return {
        host: a.host,
        protocol: a.protocol
    };
}

function serialize(obj) {
    var s = [];
    for (var k in obj){
        var v = obj[k];
        s.push(k + '=' + encodeURIComponent(v));
    }

    return s.join('&');
}
