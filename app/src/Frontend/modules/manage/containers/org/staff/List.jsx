'use strict';
import React from 'react';
import { Row,Col,Menu,Dropdown,Tooltip,message,Table,Popconfirm ,Icon , Button} from 'antd';

import RowOptItemBox from '../../../components/widget/RowOptItemBox.jsx';
import RowBox        from '../../../components/widget/RowBox.jsx';
import ListBox       from '../../../components/widget/ListBox.jsx';
import SearchPanel  from './SearchPanel.jsx';

import DetailView    from './DetailView.jsx';
import FormView      from './FormView.jsx';

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

            selectedRowKeys:[],

            pagination:{},
            rows: [],
            selectedRow:null,
            loading: false,
            loading_action_save:false,
            loading_action_remove:false,
            columns:[
                {
                    title: 'ID',
                    key:"key",
                    dataIndex: 'key',
                    width:60
                },
                {
                    title: '姓名',
                    key:"stf_name",
                    dataIndex: 'stf_name'
                },
                {
                    title: '手机',
                    key:"mobile",
                    width:120,
                    dataIndex: 'mobile'
                },
                {
                    title: '部门',
                    key:"dep_name",
                    width:120,
                    dataIndex: 'dep_name'
                },

                {
                    title: '职位',
                    key:"pot_name",
                    width:120,
                    dataIndex: 'pot_name'
                },
                {
                    title: '角色',
                    key:"role_name",
                    dataIndex: 'role_name',
                    width:120,
                },
                {
                    title: '添加时间',
                    key:"add_time",
                    dataIndex: 'add_time',
                    width:150,
                },
                {
                    title: '授权登录',
                    key:"user_id",
                    dataIndex: 'user_id',
                    render: (user_id,row)=>{
                        return(
                            <div>{
                                user_id ?
                                    <Button type="primary" size="small">已授权</Button>:
                                    <Button size="small">未授权</Button>
                            }</div>
                        )
                    }
                },
                {
                    title: '操作',
                    key:"",
                    width:90,
                    dataIndex: '',
                    render: (value,row)=>{
                        return <RowOptItemBox actions={[
                        {title:"查看",icon:"book"},
                        {title:"修改",icon:"edit"}]} option_action={this.option_action.bind(this,row)}/>;
                    }
                }
            ],
            departments:[],
            roles:[],
            selectedDepId:null,
            selectedPotId:null,
        };
    },
    option_action(row,e){
        if(e.key == "修改"){
            this.action_update(row)
        }
        if(e.key == "查看"){
            this.action_detail(row)
        }
    },
    action_remove(){
        console.log("删除",this.state.selectedRowKeys)
        if(this.state.selectedRowKeys.length == 0) return message.error("请选择要删除的记录");
        this.setState({ loading: true,loading_action_remove:true });
        this.context.dataStore.actionPost(config.controller,"remove",{ids:this.state.selectedRowKeys.join(",")},(result,error)=>{
            if(error){
                this.setState({loading: false,loading_action_remove:false});
            }else{
                message.success("删除成功");
                let { rows } = this.state;
                this.setState({loading: false,selectedRowKeys:[],loading_action_remove:false,rows: rows.filter(row=>{
                    return this.state.selectedRowKeys.findIndex(x => x === row.key) < 0
                })});
            }
        });
    },
    action_update(row){
        console.log("修改",row)
        this.setState({
            selectedRow:row,
            showUpdateView:true,

            selectedDepId:row.dep_id,
            selectedPotId:row.pot_id,
        });
    },
    action_add(){
        console.log("新加")
        this.setState({
            selectedRow:null,
            showAddView:true,

            selectedDepId:null,
            selectedPotId:null,
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
        var {selectedRow,selectedDepId,selectedPotId} = this.state;
        var rowKey = null;
        console.log("保存",selectedRow,row)
        if(selectedRow && selectedRow.key){
            rowKey = data.id = selectedRow.key;
        }
        //if(!selectedDepId){
        //    return message.error("请选择部门");
        //}
        //if(!selectedPotId){
        //    return message.error("请选择职位");
        //}
        row.dep_id= selectedDepId;
        row.pot_id= selectedPotId;
        data.row = JSON.stringify(row);
        let action = selectedRow ? "update":"add";
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
                if(rowKey){
                    message.success("修改成功");
                }else{
                    message.success("新加成功");
                }
                this.setState({
                    loading_action_save:false,
                    showAddView:false,
                    showUpdateView:false
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
                let { pagination } = this.state;
                let {rows,total,limit,page,departments,roles} = result;
                pagination.total = total;
                pagination.pageSize = limit;
                pagination.current = page;
                if(this.isMounted()){
                    this.setState({
                        departments,roles,
                        loading: false,selectedRowKeys:[],
                        pagination,rows
                    });
                }
            }
        });
    },
    componentDidMount(){
        this.action_list();
    },
    renderList(){
        return (
            <div>
                <SearchPanel parent={this} ref="search"/>
                <ListBox parent={this} ref="listTable"/>
            </div>
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
	render() {
        let result = null;
        let hideList = {};
        let hideUnList = {display:"none"};
        if(this.state.showAddView) {
            result = this.renderAdd()
        }else if(this.state.showDetailView){
            result = this.renderDetail()
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
