'use strict';
import React from 'react';
import { Row,Col,Menu,Dropdown,Tooltip,message,Table,Popconfirm ,Icon , Button} from 'antd';

import RowOptItemBox from '../../../components/widget/RowOptItemBox.jsx';
import RowBox        from '../../../components/widget/RowBox.jsx';
import ListBox       from '../../../components/widget/ListBox.jsx';
import SearchPanel   from './SearchPanel.jsx';

import DetailView    from './DetailView.jsx';
import FormView      from './FormView.jsx';
import PermissionView      from './PermissionView.jsx';

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
            showPermissionView:false,

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
                    title: '角色名',
                    key:"role_name",
                    dataIndex: 'role_name'
                },
                {
                    title: '操作',
                    key:"",
                    width:90,
                    dataIndex: '',
                    render: (value,row)=>{
                        return <RowOptItemBox actions={[
                        {title:"修改",icon:"edit"},
                        {title:"权限",icon:""}]} option_action={this.option_action.bind(this,row)}/>;
                    }
                }
            ],
            permissions:null,
            system_permissions:null
        };
    },
    option_action(row,e){
        if(e.key == "修改"){
            this.action_update(row)
        }
        if(e.key == "权限"){
            this.action_show_permission_pannel(row)
        }
    },
    action_show_permission_pannel(row){
        this.context.store.actionPost(config.controller_permission,"get",{role_id:row.key},(result,error)=>{
            if(error){
                return message.error(result);
            }else{
                if(this.isMounted()){
                    this.setState({
                        selectedRow:row,
                        showPermissionView:true,
                        permissions:result.permissions,
                        system_permissions:result.system_permissions,
                    });
                }
            }
        });
    },
    action_remove(){
        console.log("删除",this.state.selectedRowKeys)
        if(this.state.selectedRowKeys.length == 0) return message.error("请选择要删除的记录");
        this.setState({ loading: true,loading_action_remove:true });
        this.context.store.actionPost(config.controller,"remove",{ids:this.state.selectedRowKeys.join(",")},(result,error)=>{
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
        let action = selectedRow ? "update":"add";
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
                let {rows} = this.state;
                selectedRow = result.row;
                if(rowKey){
                    rows.forEach(_row=>{
                        if(_row.key == rowKey){
                            Object.assign(_row,row)
                        }
                    });
                    message.success("修改成功");
                }else{
                    message.success("新加成功");
                    rows.unshift(result.row);
                }

                this.setState({
                    rows,
                    selectedRow,
                    loading_action_save:false
                });
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
                let { pagination } = this.state;
                let {rows,total,limit,page} = result;
                pagination.total = total;
                pagination.pageSize = limit;
                pagination.current = page;
                if(this.isMounted()){
                    this.setState({
                        loading: false,selectedRowKeys:[],
                        pagination,rows
                    });
                }
            }
        });
    },
    action_save_permission(){
        console.log("保存权限",this.refs.form)
        let data = {};
        var {permissions,selectedRow} = this.state;
        if(!permissions) return message.success("没有更改的权限");
        data.role_id = selectedRow.key;
        data.permissions = JSON.stringify(permissions);
        this.setState({
            loading_action_save:true
        });
        this.context.store.actionPost(config.controller_permission,"save",data,(result,error)=>{
            if(error){
                this.setState({
                    loading_action_save:false
                });
                return message.error(result);
            }else{
                message.success("保存权限成功");
                this.setState({
                    loading_action_save:false
                });
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
    renderPermission(){
        return (
            <RowBox parent={this}
                    action_back={()=>{this.setState({showPermissionView:false})}}
                    action_save={this.action_save_permission}>
                <PermissionView ref="form" parent={this}/>
            </RowBox>
        );
    },
	render() {
        let result = null;
        let hideList = {};
        let hideUnList = {display:"none"};
        if(this.state.showAddView) {
            result = this.renderAdd()
        }else if(this.state.showPermissionView){
            result = this.renderPermission()
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
