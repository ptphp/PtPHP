'use strict';
import React from 'react';
import { Row,Col,Tooltip,message,Table,Popconfirm ,Icon , Button} from 'antd';

import RowOptItemBox from '../../../components/widget/RowOptItemBox.jsx';
import RowBox        from '../../../components/widget/RowBox.jsx';
import ListBox       from '../../../components/widget/ListBox.jsx';
import ItemBox       from './ItemBox.jsx';

import FormView             from './FormView.jsx';

import config from './config';
import './index.less';

export default React.createClass({
    contextTypes: {
        store: React.PropTypes.object.isRequired
    },
    getInitialState() {
        return {
            showDetailView:false,
            showAddView:false,
            showUpdateView:false,
            showPositionView:false,

            selectedRowKeys:[],

            pagination:{},
            rows: [],
            selectedRow:null,
            loading: false,
            loading_action_save:false,
            loading_action_remove:false,
            columns:  [
                {
                    title: '',
                    dataIndex: 'cat_name',
                    key: 'cat_name',
                    width: 250,
                    className:"department_td",
                    render:(value,row)=>{
                        return <ItemBox option_action={this.option_action.bind(this,row)} parent={this} row={row}/>;
                    }
                }
            ],
            rows_key_pid:[],
            rows_key_name:[],
            onExpandedRowsChange:[],
        };
    },
    option_action(row,e){
        if(e.key == "edit"){
            this.action_update(row)
        }
        if(e.key == "remove"){
            if(!confirm("确认要删除么?")) return;
            this.action_remove(row)
        }
        if(e.key == "add_sub"){
            let _row = {
                dep_name:"",
                pid:row.key
            };
            this.action_update(_row)
        }
    },

    action_remove(row){
        console.log("删除",row.key)
        this.setState({ loading: true,loading_action_remove:true });
        this.context.store.actionPost(config.controller,"remove",{id:row.key},(result,error)=>{
            if(error){
                message.error(result);
                this.setState({loading: false,loading_action_remove:false});
            }else{
                message.success("删除成功");
                this.action_list();
            }
        });
    },
    action_update(row){
        console.log("修改",row)
        this.setState({
            selectedRow:row,
            showUpdateView:true
        });
    },
    action_add(){
        console.log("新加")
        this.setState({
            selectedRow:null,
            showAddView:true
        });
    },
    action_detail(row){
        console.log("查看",row)
        this.setState({
            selectedRow:row,
            showDetailView:true
        });
    },
    action_save(){
        var row = this.refs.form.getFieldsValue();
        let data = {};
        var {selectedRow} = this.state;
        var rowKey = null;
        console.log("保存",selectedRow,row)
        if(selectedRow && selectedRow.key){
            rowKey = data.id = selectedRow.key;
        }
        data.row = JSON.stringify(row);
        let action = (selectedRow && selectedRow.key) ? "update":"add";
        this.setState({
            loading_action_save:true
        });
        this.context.store.actionPost(config.controller,action,data,(result,error)=>{
            if(error){
                this.setState({
                    loading_action_save:false
                });
                return message.error(result);
            }else{
                message.success("保存成功");
                this.setState({
                    showAddView:false,showUpdateView:false,
                    loading_action_save:false
                });
                this.action_list();
            }

        });
    },
    action_list(params = {}) {
        console.log("列表",params)
        this.setState({ loading: true });
        this.context.store.actionPost(config.controller,"list",params,(result,error)=>{
            if(error){
                this.setState({loading: false});
            }else{
                let {rows,rows_key_pid,rows_key_name} = result;
                if(this.isMounted()){
                    this.setState({
                        loading: false,
                        rows_key_pid,rows_key_name,
                        expandedRowKeys:rows.length > 0 ? [rows[0].key]:[],
                        rows
                    });
                }
            }
        });
    },
    get_is_parent(key){
        let {rows_key_pid} = this.state;
        for(let row_id in rows_key_pid){
            let pid = rows_key_pid[row_id];
            if(pid == key) return true;
        }
        return false;
    },
    onExpandedRowsChange(rows){
        this.setState({
            expandedRowKeys: rows,
        });
    },
    componentDidMount(){
        this.action_list();
    },
    renderList(){
        return (
            <Row style={{marginTop:16}}>
                <Col span="24" className="department-list">
                    <Table
                        onExpandedRowsChange={this.onExpandedRowsChange}
                        expandedRowKeys={this.state.expandedRowKeys}
                        loading={this.state.loading}
                        pagination={false}
                        columns={this.state.columns}
                        dataSource={this.state.rows}
                        indentSize={20}
                        showHeader={false}
                        bordered={true}/>
                </Col>
            </Row>
        );
    },
    renderAdd(){
        return (
            <RowBox parent={this}
                    action_back={()=>{this.setState({showAddView:false})}}
                    action_save={this.action_save}>
                <FormView ref="form" parent={this}/>
            </RowBox>
        );
    },
    renderUpdate(){
        return (
            <RowBox parent={this}
                    action_back={()=>{this.setState({showUpdateView:false})}}
                    action_save={this.action_save}>
                <FormView ref="form" parent={this}/>
            </RowBox>
        );
    },
    render() {

        let result = null;
        let hideList = {};
        let hideUnList = {display:"none"};
        if(this.state.showAddView) {
            result = this.renderAdd()
        }else if(this.state.showUpdateView){
            result = this.renderUpdate()
        }
        if(result){
            hideList = {display:"none"};
            hideUnList = {};
        }
        return (
            <div>
                <div style={hideUnList}>
                    {result}
                </div>
                <div style={hideList}>
                    {this.renderList()}
                </div>
            </div>
        );
    }
});
