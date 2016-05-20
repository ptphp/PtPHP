'use strict';

import React from 'react';
var ReactDOM = require('react-dom')

var Simditor = require('simditor');

export default React.createClass({
    getDefaultProps() {
        return {
            editName:'editor'
        };
    },
    getInitialState() {
        return {};
    },
    componentWillReceiveProps(nextProps){
        //console.log("componentWillReceiveProps",nextProps.getContent());
        window[this.props.editName] && window[this.props.editName].setValue(nextProps.getContent());
    },
    //valueChanged(value){
    //    console.log("valueChanged", value);
    //},
    componentDidMount(){
        const toolbar = [ 'title', 'bold', 'italic', 'underline', 'strikethrough',
            'color', '|', 'ol', 'ul', 'blockquote',
            //'code',
            'table', '|',
            'link', 'image', 'hr', '|', 'indent', 'outdent' ];
        var textbox = ReactDOM.findDOMNode(this.refs.editor);
        if(window[this.props.editName]) {window[this.props.editName].destroy();}
        window[this.props.editName] = new Simditor({
            placeholder : '',
            toolbar : toolbar,  //工具栏
            //defaultImage : 'simditor-2.0.1/images/image.png', //编辑器插入图片时使用的默认图片
            textarea: textbox,
            upload : {
                url : window.API_URL, //文件上传的接口地址
                params: {
                    access_token:window.get_access_token(),
                    controller:"admin/tool",
                    action:"upload",
                    simditor:1
                }, //键值对,指定文件上传接口的额外参数,上传的时候随文件一起提交
                fileKey: 'file', //服务器端获取文件数据的参数名
                connectionCount: 3,
                leaveConfirm: '正在上传文件'
            }
        });
        // window.editor.on("valuechanged", (e, src)=>{
        //     //this.valueChanged(e.target.getValue())
        // });
        //debugger;
        window[this.props.editName] && window[this.props.editName].setValue(this.props.getContent());
        console.log("componentDidMount",this.props.getContent());
    },
    render(){
        return (
            <textarea ref="editor" />
        )
    }
});
