'use strict';

import React from 'react';
import { Modal,message,Button,Row,Col,Input,InputNumber,Table,Popconfirm,Icon } from 'antd';
const config = require("./config");
import EditorView from '../../components/tools/EditorView.jsx';

export default React.createClass({
    contextTypes: {
        dataStore: React.PropTypes.object.isRequired
    },
    getInitialState(){
        return {

        }
    },
    componentDidMount(){},
    handleOk(){
        //console.log(window['editor_task_example'] && window['editor_task_example'].getValue());
        let { selectedRow } = this.props.parent.state;
        var example = window['editor_task_example'] ? window['editor_task_example'].getValue() : "";
        this.context.dataStore.save_task_example(selectedRow.id,example,(result,error)=>{
            this.props.parent.state.selectedRow.example = example;
            this.props.parent.setState({modalVisible:false});
        });

    },
    handleCancel(){
        this.props.parent.setState({modalVisible:false});
    },
    getContent(){
        let content;
        if(window['editor_task_example'] && window['editor_task_example'].getValue().length > 0){
            console.log("getContent from edit")
            content = window['editor_task_example'].getValue();
        }else{
            console.log("getContent from selectedRow")
            content = this.props.parent.state.selectedRow ? this.props.parent.state.selectedRow.example:"";
        }
        return content ? content : "";
    },
    render(){
        //console.log(this.props.parent.state.selectedRow)
        return (
            <div>
                {
                    this.props.parent.state.selectedRow ?
                        <Modal width="750" title={this.props.parent.state.selectedRow.title+" 攻略"} visible={this.props.parent.state.modalVisible}
                               onOk={this.handleOk}
                               onCancel={this.handleCancel}>
                            <Row>
                                <Col span="24">
                                    <EditorView parent={this} editName={"editor_task_example"} getContent={this.getContent} />
                                </Col>
                            </Row>
                        </Modal>:null
                }
            </div>
        );
    }
});
