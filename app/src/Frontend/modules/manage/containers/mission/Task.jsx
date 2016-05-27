'use strict';

import React from 'react';
import { message,Button,Row,Col,Input,InputNumber,Table,Popconfirm,Icon } from 'antd';
const config = require("./config");
import TaskExample from './TaskExample.jsx'

export default React.createClass({
    contextTypes: {
        router: React.PropTypes.object.isRequired,
        store: React.PropTypes.object.isRequired
    },
    getInitialState(){
        return {
            selectedRow:null,
            modalVisible:false,
            columns:[
                {
                    title: '序号',
                    key:"key",
                    dataIndex: 'key',
                    width:40
                },
                {
                    title: '奖励余额',
                    key:"award",
                    dataIndex: 'award',
                    width:120,
                    render: (value,row)=>{
                        //console.log(row);
                        return (
                            <InputNumber onChange={this.onChangeTaskField.bind(this,"award",row)} value={value} />
                        );
                    }
                },
                {
                    title: '任务描述',
                    key:"title",
                    dataIndex: 'title',
                    render: (value,row)=>{
                        //console.log(row);
                        return (
                            <Input onChange={this.onChangeTaskField.bind(this,"title",row)} value={value} />
                        );
                    }
                },
                {
                    title: '攻略',
                    key:"example",
                    width:60,
                    dataIndex: 'example',
                    render: (value,row)=>{
                        //console.log(row);
                        return (
                            <span>
                                {
                                    row.id ?
                                        <Button type="primary" size="small" onClick={this.taskExample.bind(this,row)}>
                                            <Icon type="edit" />
                                        </Button> : <span />
                                }
                            </span>
                        );
                    }
                },
                {
                    title: '操作',
                    key:"",
                    dataIndex: '',
                    width:80,
                    render: (value,row)=>{
                        //console.log(row);
                        return (
                            <Button.Group>
                                <Popconfirm title="确定要删除吗？"
                                            placement="top"
                                            onConfirm={this.onRemoveRow.bind(this,row)}>
                                    <Button type="ghost" size="small">
                                        <Icon type="delete" />
                                    </Button>
                                </Popconfirm>
                            </Button.Group>
                        );
                    }
                }
            ]
        }
    },
    taskExample(row){
        window['editor_task_example'] && window['editor_task_example'].setValue(row.example?row.example:"");
        this.setState({
            selectedRow:row,
            modalVisible:true
        });
    },
    onChangeTaskField(field,row,e){
        let value;
        if(field == "title"){
            value = e.target.value;
        }else{
            value = e;
        }

        let {tasks} = this.props.parent.state;
        let _tasks = [];
        tasks.map(task=>{
            if(task.key == row.key){
                task[field] = value;
            }
            _tasks.push(task);
        })
        console.log(field,row.key, value,_tasks)
        this.props.parent.setState({
            tasks:_tasks
        });
    },
    removeRow(id){
        this.context.store.actionPost(config.controller, "remove_task", {id: id}, (result, error)=> {
            if (error) {
                return message.error(result)
            } else {
                message.success('删除成功');
            }
        });
    },
    onRemoveRow(row){
        if(this.props.parent.state.rowKey && this.props.parent.state.row && this.props.parent.state.row.join_nums > 0) return ;
        let {tasks} = this.props.parent.state;
        let _tasks = [];
        var key = 1;
        if(row.id){
            this.removeRow(row.id);
            tasks.map(task=>{
                if(task.id != row.id){
                    task.key = key++;
                    _tasks.push(task);
                }
            })
        }else{
            tasks.map(task=>{
                if(task.key != row.key){
                    task.key = key++;
                    _tasks.push(task);
                }
            })
        }
        this.props.parent.setState({
            tasks:_tasks
        });
    },
    addRow(){
        let {tasks} = this.props.parent.state;
        let task = {
            id:"",
            award:0,
            title:""
        };
        task['key'] = tasks.length + 1;
        console.log(task);
        tasks.push(task);
        this.props.parent.setState({tasks});
    },
    componentDidMount(){},
    render(){
        return (
            <div>
                <TaskExample parent={this} />
                <Row style={{marginBottom:16}}>
                    <Col span="2" offset="22">
                        <Button onClick={this.addRow}>新加</Button>
                    </Col>
                </Row>
                <Row>
                    <Col span="24">
                        <Table columns={this.state.columns}
                               dataSource={this.props.parent.state.tasks}
                               pagination={false}
                               size="small" />
                    </Col>
                </Row>
            </div>
        );
    }
});
