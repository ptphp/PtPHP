'use strict';

import React from 'react';
import { message,Tabs,Icon,Row, Col, Button} from 'antd';
import reqwest from 'reqwest';
import BaseView from './Base.jsx'
import TaskView from './Task.jsx'
import UserListView from './UserList.jsx'

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

            tasks:[],
            users:[]
        };
    },
    goBack(){
        this.context.router.push(config.list_url);
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
                    this.setState({
                        row,tasks,loading:false,users
                    });
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
                    <TabPane tab="参与用户" key="1">
                        <UserListView parent={this}/>
                    </TabPane>
                </Tabs>
            </div>
        )
    },
});
