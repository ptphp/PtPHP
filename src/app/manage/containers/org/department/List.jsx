'use strict';
import React from 'react';
import { Row,Col,Tooltip,message,Table,Popconfirm ,Icon , Button} from 'antd';

import RowOptItemBox from '../../../components/widget/RowOptItemBox.jsx';
import RowBox        from '../../../components/widget/RowBox.jsx';
import ListBox       from '../../../components/widget/ListBox.jsx';
import DepartmentItemBox    from './DepartmentItemBox.jsx';
import PositionItemBox    from './PositionItemBox.jsx';

import FormView             from './FormView.jsx';
import PositionFormView     from './PositionFormView.jsx';

import config from './config';
import './index.less';

export default React.createClass({
    contextTypes: {
        dataStore: React.PropTypes.object.isRequired
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
                    dataIndex: 'role_name',
                    key: 'role_name',
                    width: 250,
                    className:"department_td",
                    render:(value,row)=>{
                        return <DepartmentItemBox option_action={this.option_action.bind(this,row)} parent={this} row={row}/>;
                    }
                },
                {
                    title: '职位',
                    dataIndex: '',
                    className:"position_td",
                    key: '',
                    render: (value,row)=>{
                        return <PositionItemBox parent={this} row={row}/>;
                    }
                }
            ],
            departments_pid_rows:[],
            departments_rows:[],
            onExpandedRowsChange:[],
            selectedPosition:null
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
        if(e.key == "add_position"){
            this.action_add_position(row.key)
        }
    },
    action_remove_position(pot_id){
        this.context.dataStore.actionPost(config.controller_position,"remove",{id:pot_id},(result,error)=>{
            if(error){
                return message.error(result);
            }else{
                message.success("删除成功");
                this.action_list();
            }
        });
    },
    action_remove(row){
        console.log("删除",row.key)
        this.setState({ loading: true,loading_action_remove:true });
        this.context.dataStore.actionPost(config.controller,"remove",{id:row.key},(result,error)=>{
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
    action_add_position(dep_id){
        console.log("新加职位")
        this.setState({
            selectedPosition:{
                dep_id:dep_id,
                pot_name:""
            },
            showPositionView:true
        });
    },
    action_update_position(row){
        console.log("新加职位")
        this.setState({
            selectedPosition:row,
            showPositionView:true
        });
    },
    action_detail(row){
        console.log("查看",row)
        this.setState({
            selectedRow:row,
            showDetailView:true
        });
    },
    action_save_position(){
        var row = this.refs.form.getFieldsValue();
        let data = {};
        var {selectedPosition} = this.state;
        var rowKey = null;
        console.log("保存",selectedPosition,row)
        if(selectedPosition && selectedPosition.pot_id){
            rowKey = data.id = selectedPosition.pot_id;
        }
        data.row = JSON.stringify(row);
        let action = (selectedPosition && selectedPosition.pot_id) ? "update":"add";
        this.setState({
            loading_action_save:true
        });
        this.context.dataStore.actionPost(config.controller_position,action,data,(result,error)=>{
            if(error){
                this.setState({
                    loading_action_save:false
                });
                return message.error(result);
            }else{
                message.success("保存成功");
                this.setState({
                    showPositionView:false,
                    loading_action_save:false
                });
                this.action_list();
            }

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
        this.context.dataStore.actionPost(config.controller,action,data,(result,error)=>{
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
        this.context.dataStore.actionPost(config.controller,"list",params,(result,error)=>{
            if(error){
                this.setState({loading: false});
            }else{
                let {rows,departments_pid_rows,departments_rows} = result;
                if(this.isMounted()){
                    this.setState({
                        loading: false,
                        departments_pid_rows,departments_rows,
                        expandedRowKeys:rows.length > 0 ? [rows[0].key]:[],
                        rows
                    });
                }
            }
        });
    },
    get_is_parent(department_id){
        let {departments_pid_rows} = this.state;
        for(let dep_id in departments_pid_rows){
            let pid = departments_pid_rows[dep_id];
            if(pid == department_id) return true;
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
    renderDetail(){
        return (
            <RowBox parent={this}
                    action_back={()=>{this.setState({showDetailView:false})}}>
                <DetailView parent={this}/>
            </RowBox>
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
    renderPosition(){
        return (
            <RowBox parent={this}
                    action_back={()=>{this.setState({showPositionView:false})}}
                    action_save={this.action_save_position}>
                <PositionFormView ref="form" parent={this}/>
            </RowBox>
        );
    },
    render() {

        let result = null;
        let hideList = {};
        let hideUnList = {display:"none"};
        if(this.state.showAddView) {
            result = this.renderAdd()
        }else if(this.state.showDetailView){
            result = this.renderDetail()
        }else if(this.state.showPositionView){
            result = this.renderPosition()
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
