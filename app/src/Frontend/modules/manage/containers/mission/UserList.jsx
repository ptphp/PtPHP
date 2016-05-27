'use strict';

import React from 'react';
import { Collapse,Modal,message,Button,Row,Col,Input,InputNumber,Table,Popconfirm,Icon,Tooltip } from 'antd';
const config = require("./config");
const Panel = Collapse.Panel;
import "./index.less"

import UserAvatarBox from "../../components/avatar/index";


export default React.createClass({
    contextTypes: {
        router: React.PropTypes.object.isRequired,
        store: React.PropTypes.object.isRequired
    },
    getInitialState(){
        return {
            reason:"",
            loadingSuccess:false,
            selectedTask:null,
            modalVisible:false,
            modalTitle:false,
            columns:[
                {
                    title: 'ID',
                    key:"id",
                    dataIndex: 'id',
                    width:40
                },
                {
                    title: '用户',
                    key:"user_id",
                    dataIndex: 'user_id',
                    render:(value,row)=>{
                        return <UserAvatarBox avatar={row.avatar} name={row.name} />
                    }
                },
                {
                    title: '参与时间',
                    key:"begin_time",
                    dataIndex: 'begin_time',
                },
                {
                    title: '完成时间',
                    key:"finish_time",
                    dataIndex: 'finish_time',
                },
                {
                    title: '审核操作',
                    key:"",
                    dataIndex: '',
                    render:(value,row)=>{
                        return (
                            <Button.Group>
                                {
                                    this.props.parent.state.tasks.map(task=>{
                                        //console.log(row.task_key,task.key);
                                        let props = {
                                            size:"small"
                                        };
                                        if(row.task_key > task.key){
                                            props['type'] = "ghost";
                                            props['onClick'] = this.onShowTaskDetail.bind(this,row.user_id,row.mission_id,task.key);
                                        }
                                        if(row.task_key == task.key){
                                            props['type'] = "primary";
                                            props['onClick'] = this.onShowTaskDetail.bind(this,row.user_id,row.mission_id,task.key);
                                        }
                                        if(row.task_key < task.key){
                                            props['type'] = "dashed";
                                            props['disabled'] = "true";
                                        }
                                        return (
                                            <Button {...props} key={task.key}>
                                                {task.key}
                                            </Button>
                                        )
                                    })
                                }
                            </Button.Group>
                        )
                    }
                }
            ]
        }
    },
    onShowTaskDetail(user_id,mission_id,task_key){
        //console.log(user_id,mission_id,task_key)
        this.context.store.get_verify_task(user_id,mission_id,task_key,(result,error)=>{
            if(result.tasks.length == 0){
                return message.warn("还没提交审核");
            }

            this.setState({
                selectedTask:result.tasks,
                modalVisible:true,
                modalTitle:"审核",
                reason:""
            });
        })
    },
    componentDidMount(){},
    handleOk(){
        this.setState({
            modalVisible:false
        });
    },
    handleCancel(){
        this.setState({
            modalVisible:false
        });
    },
    renderTaskItem(task){
        let header = "";

        let res = (
            <div>
                <Row className="task_verify_row hd">
                    <Col span="3">
                        任务:
                    </Col>
                    <Col span="9">
                        {task.title}
                    </Col>
                    <Col span="3">
                        奖励:
                    </Col>
                    <Col span="9">
                        {task.award}
                    </Col>
                </Row>
                <Row className="task_verify_row">
                    <Col span="3">
                        图片:
                    </Col>
                    <Col span="18" className="verify_pics">
                        {task.pics.split("|").map((pic,i)=>{
                            return (
                                <img key={i} onClick={()=>{
                                window.open(pic);
                                }} src={pic} alt=""/>
                            )
                        })}
                    </Col>
                </Row>
                <Row className="task_verify_row">
                    <Col span="3">
                        上传时间:
                    </Col>
                    <Col span="18">
                        {task.add_time}
                    </Col>
                </Row>
                <Row className="task_verify_row">
                    <Col span="3">
                        备注:
                    </Col>
                    <Col span="18">
                        {task.note}
                    </Col>
                </Row>
                <Row className="task_verify_row">
                    <Col span="3">
                        状态:
                    </Col>
                    <Col span="18">
                        {task.status == 0?"侍审核":""}
                        {task.status == 1?"已通过":""}
                        {task.status == 2?"已拒绝":""}
                    </Col>
                </Row>
                {
                    task.up_time ?
                        <Row className="task_verify_row">
                            <Col span="3">
                                审核时间:
                            </Col>
                            <Col span="18">
                                {task.up_time}
                            </Col>
                        </Row>:null
                }
                {
                    task.reason ?
                        <Row className="task_verify_row">
                            <Col span="3">
                                审核意见:
                            </Col>
                            <Col span="18">
                                {task.reason}
                            </Col>
                        </Row>:null
                }
                {
                    task.status == 0 ?
                        <Row className="task_verify_row">
                            <Col span="3">
                                审核意见:
                            </Col>
                            <Col span="18">
                                <Input type="textarea" value={this.state.reason} onChange={(e)=>{
                                let val = e.target.value;
                                this.setState({reason:val});
                                }} rows="3"/>
                            </Col>
                        </Row>:null
                }
                {
                    task.status == 0 ?
                        <Row className="task_verify_row" style={{paddingTop:16}}>
                            <Col offset="3" span="18">
                                <Button onClick={this.do_verify.bind(this,1,task.id)} type="primary" loading={this.state.loadingSuccess}>通过</Button> &nbsp;&nbsp;&nbsp;
                                <Button onClick={this.do_verify.bind(this,2,task.id)} >拒绝</Button>
                            </Col>
                        </Row>:null
                }
            </div>
        )

        if(this.state.selectedTask.length > 1){
            header = task.title;
            if(task.status == 0){
                header += " 侍审核";
            }
            if(task.status == 1){
                header += " 已通过";
            }
            if(task.status == 2){
                header += " 已拒绝";
            }
            return (
                <Panel header={header} key={task.id}>
                    {res}
                </Panel>
            )
        }else{
            return (
                <div>
                    {res}
                </div>
            )
        }
    },
    renderTasks(){
        if(this.state.selectedTask.length > 1){
            //console.log("len",this.state.selectedTask.length)
            return (
                <Collapse accordion defaultActiveKey={[""+this.state.selectedTask.length]}>
                    {this.state.selectedTask.map(task=>{
                        return this.renderTaskItem(task);
                    })}
                </Collapse>
            );
        }else{
            return this.renderTaskItem(this.state.selectedTask[0]);
        }
    },
    do_verify(flag,id){
        if(flag == 2 && this.state.reason.length == 0){
            return message.warn("审核意见不能为空");
        }
        //console.log(id,flag,this.state.reason)
        this.setState({
            loadingSuccess:true
        });
        this.context.store.do_verify(id,flag,this.state.reason,(result,error)=>{
            let{user_id,mission_id,task_key} = result;
            this.context.store.get_verify_task(user_id,mission_id,task_key,(result,error)=>{
                if(error){
                    return message.warn(result);
                }
                this.setState({
                    loadingSuccess:false,
                    selectedTask:result.tasks,
                    modalVisible:true,
                    modalTitle:"审核",
                    reason:""
                });
            })
        });
    },
    render(){
        //console.log(this.props.parent.state.users);
        console.log(this.state.selectedTask)
        return (
            <div>
                {
                    this.state.modalVisible ?
                        <Modal width="750" footer="" title={this.state.modalTitle} visible={this.state.modalVisible}
                               onOk={this.handleOk}
                               onCancel={this.handleCancel}>
                            <Row>
                                <Col span="24">
                                    {this.state.selectedTask ? this.renderTasks():null}
                                </Col>
                            </Row>
                        </Modal>:null
                }
                <Row>
                    <Col span="24">
                        <Table columns={this.state.columns}
                               dataSource={this.props.parent.state.users}
                               pagination={false}
                               size="small" />
                    </Col>
                </Row>
            </div>
        );
    }
});
