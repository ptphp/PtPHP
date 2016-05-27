'use strict';

import React from 'react';
import { message,Tabs,Icon,Row, Col, Button} from 'antd';
import reqwest from 'reqwest';
import BaseView from './Base.jsx'
import BaseThumbView from './BaseThumb.jsx'
import BaseTipsView from './BaseTips.jsx'
import BaseExampleView from './BaseExample.jsx'

import TaskView from './Task.jsx'

const config = require("./config");
const TabPane = Tabs.TabPane;

export default React.createClass({
    contextTypes: {
        router: React.PropTypes.object.isRequired,
        store: React.PropTypes.object.isRequired
    },
    getInitialState() {
        return {
            rowKey:this.props.params.id === undefined ? null : this.props.params.id,
            row:null,
            loading:false,

            pics:[],
            tasks:[]
        };
    },
    goBack(){
        this.context.router.push(config.list_url);
    },
    handlePics(row){
        let pics = [];
        if(row.thumb && row.thumb.length > 0){
            var i = 0;
            row.thumb.split("|").map(url=>{
                i++;
                pics.push({
                    uid:i,
                    url:url,
                    thumbUrl:url,
                });
            });
        }
        return pics;
    },
    fetchRow(){
        this.setState({loading:true});
        this.context.store.actionPost(config.controller,"row",{id:this.state.rowKey},(result,error)=>{
            if(error){
                if(this.isMounted())
                    this.setState({loading:false});
                return message.error(result)
            }else{

                if(this.isMounted()){
                    let { row ,tasks,users} = result;
                    let pics = this.handlePics(row);
                    this.setState({
                        row,pics,tasks,loading:false,users
                    });
                }
            }
        });
    },
    actionSave(){
        if(this.state.tasks.length == 0) {return message.error("至少要加一个子任务");}
        let params = {};
        let row = this.refs.BaseView.getForm().getFieldsValue();
        var _row = this.state.row;
        let { pics } = this.state;
        if(pics.length < 1) {return message.error("您至少要上传一张缩略图");}
        var _pics = [];
        pics.map(pic=>{
            _pics.push(pic.url);
        });
        row.thumb = _pics.join("|");
        row.example = window['editor_example'] ? window['editor_example'].getValue() : "";
        row.tips = window['editor_tips'] ? window['editor_tips'].getValue() : "";
        if(row.start_time && typeof row.start_time == 'object') row.start_time = Utils.Date.FormatDateTime(row.start_time);
        if(row.end_time && typeof row.end_time == 'object') row.end_time = Utils.Date.FormatDateTime(row.end_time);

        //console.log(row)
        row.tasks = this.state.tasks;
        this.setState({row});
        params.row = JSON.stringify(row);
        if(this.state.rowKey) {params.id = this.state.rowKey;}
        this.setState({ loading:true });
        this.context.store.actionPost(config.controller,this.state.rowKey === null ? "add" : "update",params,(result,error)=>{
            if(error){
                this.setState({loading:false});
                return message.error(result,2);
            }else{
                var id = result.id;
                if(this.state.rowKey === null){
                    message.success("新加成功");
                    this.setState({row:_row});
                    setTimeout(()=>{
                        this.context.router.push(config.edit_url + "/" + id);
                    },1500);
                }else{
                    message.success("修改成功");
                    this.fetchRow();
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
        console.log(this.state.row);
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
                    <TabPane tab="缩略图" key="2">
                        <BaseThumbView parent={this} />
                    </TabPane>
                    <TabPane tab="任务说明" key="3">
                        <BaseTipsView parent={this} />
                    </TabPane>
                    <TabPane tab="示例文字" key="4">
                        <BaseExampleView parent={this} />
                    </TabPane>
                    <TabPane tab="子任务" key="5">
                        <TaskView parent={this}/>
                    </TabPane>
                </Tabs>
                {
                    !(this.state.rowKey && this.state.row && this.state.row.join_nums > 0)?
                        <Row style={{marginTop:30}}>
                            <Col span="4" offset="2">
                                <Button type="primary" onClick={this.actionSave} loading={this.state.loading}>
                                    <Icon type="save" />保存
                                </Button>
                            </Col>
                        </Row>:null
                }
            </div>
        )
    },
});
