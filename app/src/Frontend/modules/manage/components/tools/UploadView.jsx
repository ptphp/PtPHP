'use strict';

import React from 'react';
import { Upload, message, Icon } from 'antd';

export default React.createClass({
    getDefaultProps() {
        return {
            max:4
        };
    },
    getInitialState() {
        return {};
    },
    handleChange(info) {
        let fileList = info.fileList;
        // 1. 上传列表数量的限制
        //    只显示最近上传的一个，旧的会被新的顶掉
        fileList = fileList.slice(-4);

        // 2. 读取远程路径并显示链接
        fileList = fileList.map((file) => {
            if (file.response) {
                // 组件会将 file.url 作为链接进行展示
                file.url = file.response.result.url;
            }
            return file;
        });

        // 3. 按照服务器返回信息筛选成功上传的文件
        fileList = fileList.filter((file) => {
            if (file.response) {
                return file.response.error === 0;
            }
            return true;
        });
        if (info.file.response) {
            this.props.setPics(fileList);
        }
    },
    render(){
        var props = {
            action: window.API_URL,
            data: {
                access_token: window.get_access_token(),
                controller: "admin/tool",
                action: "upload",
            },
            beforeUpload:()=>{
                if(this.props.getPics().length < this.props.max){
                    return true;
                }else{
                    message.error("您最多能上传" + this.props.max + "张图片")
                    return false;
                }
            },
            onChange: this.handleChange,
            multiple: false,
            listType: 'picture-card',
            fileList: this.props.getPics()
        };
        //console.log(this.state.pics);
        return (
            <Upload {...props}>
                <Icon type="plus"/>
                <div className="ant-upload-text">上传</div>
            </Upload>
        )
    }
});
