'use strict';

import React from 'react';
import { message,Tabs,Icon,Row, Col, Button} from 'antd';
import reqwest from 'reqwest';
import BaseView from './Base.jsx'
const config = require("./config");

const TabPane = Tabs.TabPane;

export default React.createClass({
    contextTypes: {
        router: React.PropTypes.object.isRequired
    },
    getInitialState() {
        return {
            current: 'tab1',
            rowKey:this.props.params.id === undefined ? null : this.props.params.id,
            row:null,
            loading:false,

            pics:[]
        };
    },
    goBack(){
        console.log(config.list_url)
        this.context.router.push(config.list_url);
    },
    fetchRow(){
        this.setState({loading:true});
        let params = {};
        params.id = this.state.rowKey;
        params.action = "row";
        params.controller = config.controller;
        params.access_token = window.get_access_token();
        reqwest({
            url: window.API_URL,
            method: 'post',
            data: params,
            type: 'json',
            success: (response) => {
                //debugger;
                window.set_access_token(response);
                if(response.error){
                    if(this.isMounted()){
                        //console.log("isMounted2",row);
                        this.setState({
                            loading:false
                        });
                    }
                    return message.error(response.result)
                }else{
                    //console.log(response.result);
                    let { row } = response.result;
                    //console.log("isMounted1");
                    if(this.isMounted()){
                        //console.log("isMounted2",row);
                        let pics = [];
                        if(row.pics && row.pics.length > 0){
                            var i = 0;
                            row.pics.split("|").map(url=>{
                                i++;
                                pics.push({
                                    uid:i,
                                    url:url,
                                    thumbUrl:url,
                                });
                            });
                        }
                        this.setState({
                            row,pics,
                            hus:response.result.hus,
                            loading:false
                        });
                    }
                }
            }
        });
    },
    actionSave(){
        let params = {};
        let row = this.refs.BaseView.getForm().getFieldsValue();
        var _row = this.state.row;
        let { pics } = this.state;
        if(pics.length < 1) {return message.error("您至少要上传一张图片");}
        var _pics = [];
        pics.map(pic=>{
            _pics.push(pic.url);
        });
        row.pics = _pics.join("|");
        row.name = window.editor ? window.editor.getValue() : "";
        //console.log(row)
        this.setState({row});
        params.row = JSON.stringify(row);
        params.action = this.state.rowKey === null ? "add" : "update";
        if(this.state.rowKey) {params.id = this.state.rowKey;}
        params.controller = config.controller;
        params.access_token = window.get_access_token();
        this.setState({ loading:true });
        reqwest({
            url: window.API_URL,
            method: 'post',
            data: params,
            type: 'json',
            success: (response) => {
                //debugger;
                this.setState({ loading:false });
                window.set_access_token(response);
                if(response.error){
                    return message.error(response.result,2);
                }else{
                    message.success('保存成功');
                    let id = response.result.id;
                    if(this.state.rowKey === null){
                        this.context.router.push(config.edit_url + "/" + id);
                        this.setState({row:_row});
                    }
                }
            }
        });
    },
    componentDidMount() {
        if(this.state.rowKey !== null){
            this.fetchRow();
        }
    },
    render(){
        let props_disabled = {};
        if(!this.state.rowKey){
            props_disabled.disabled = "disabled";
        }
        return (
            <div>
                <div style={{ marginBottom: 16  }}>
                    <Button.Group>
                        <Button type="primary" onClick={this.goBack}>
                            <Icon type="left" />返回
                        </Button>
                    </Button.Group>
                </div>
                <Tabs >
                    <TabPane tab="基本信息" key="1">
                        <BaseView ref="BaseView" parent={this} />
                    </TabPane>
                </Tabs>
                <Row style={{marginTop:30}}>
                    <Col span="4" offset="2">
                        <Button type="primary" onClick={this.actionSave} loading={this.state.loading}>
                            <Icon type="save" />保存
                        </Button>
                    </Col>
                </Row>

            </div>
        )
    },
});
