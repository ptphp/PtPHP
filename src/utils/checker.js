'use strict';
function is_mobile(str){
    return /^1[3|4|5|7|8][0-9]\d{8}$/.test(str);
}
exports.is_mobile = is_mobile;
