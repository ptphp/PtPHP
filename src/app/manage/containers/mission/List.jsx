'use strict';
import React from 'react';
import { Tooltip,message,Table,Popconfirm ,Icon , Button} from 'antd';
import SearchPanel from './Search.jsx';
const config = require("./config");

export default React.createClass({
    contextTypes: {
        router: React.PropTypes.object.isRequired,
        dataStore: React.PropTypes.object.isRequired
    },
    getInitialState() {
        return {
            rows: [],
            selectedRowKeys:[],
            pagination: {
                showSizeChanger: true
            },
            selectedRowKey:"",
            loading: false,
            columns:[
                {
                    title: 'ID',
                    key:"key",
                    dataIndex: 'key',
                    sorter:true,
                    width:60
                },
                {
                    title: '任务名称',
                    key:"title",
                    dataIndex: 'title',
                    width:150
                },
                {
                    title: '任务描述',
                    key:"desc",
                    dataIndex: 'desc'
                },
                {
                    title: '参与/完成',
                    key:"join_nums",
                    width:100,
                    dataIndex: 'join_nums',
                    render:(value,row)=>{
                        return  <span>
                            {row.join_nums}/{row.finish_nums}
                        </span>
                    }
                },
                {
                    title: '状态',
                    key:"status",
                    dataIndex: 'status',
                    width:60,
                    filters: [
                        { text: '上架', value: '1' },
                        { text: '下架', value: '2' },
                    ],
                    render:(value,row)=>{
                        return (
                            <span>{value == 1 ? "上架":"下架"}</span>
                        )
                    }
                },
                {
                    title: '排序',
                    key:"ord",
                    dataIndex: 'ord',
                    sorter:true,
                    width:80
                },
                {
                    title: '操作',
                    key:"",
                    width:120,
                    dataIndex: '',
                    render: (value,row)=>{
                        //console.log(row);
                        return (
                            <Button.Group>
                                <Tooltip title="参与用户">
                                    <Button type="ghost" size="small"
                                            onClick={this.onShowUsers.bind(this,row)}
                                    >
                                        <Icon type="user" />
                                    </Button>
                                </Tooltip>
                                <Tooltip title="修改">
                                    <Button type="ghost" size="small"
                                            onClick={this.onChangeRow.bind(this,row)}
                                            >
                                        <Icon type="edit" />
                                    </Button>
                                </Tooltip>
                                <Popconfirm title="确定要删除吗？"
                                            placement="top"
                                            onConfirm={this.onRemoveRow.bind(this,row.key)}
                                            >
                                    <Button type="ghost" size="small">
                                        <Icon type="delete" />
                                    </Button>
                                </Popconfirm>
                            </Button.Group>
                        );
                    }
                }
            ],
        };
    },
    onRemoveRow(key){
        this.setState({ loading: true });
        this.context.dataStore.actionPost(config.controller,"remove",{id:key},(result,error)=>{
            if(error){
                this.setState({loading: false});
            }else{
                message.success("删除成功");
                let { rows } = this.state;
                this.setState({loading: false,rows: rows.filter(row=>row.id !== key)});
            }
        });
    },
    onChangeRow(row){
        this.context.router.push(config.edit_url+"/" + row.id);
    },
    onShowUsers(row){
        this.context.router.push(config.show_users_url+"/" + row.id);
    },
    addRow (){
        this.context.router.push(config.edit_url);
    },
    fetchList(params = {}) {
        this.context.dataStore.set_mission_query(params);
        this.setState({ loading: true });
        this.context.dataStore.actionPost(config.controller,"list",params,(result,error)=>{
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
    handleTableChange(pagination, filters, sorter) {
        const pager = this.state.pagination;
        pager.current = pagination.current;
        this.setState({pagination: pager});
        this.fetchList({
            limit: pagination.pageSize,
            page: pagination.current,
            sorter:JSON.stringify({order:sorter.order,field:sorter.field}),
            filters:JSON.stringify({...filters}),
            search:JSON.stringify(this.getSearchField())
        });
    },
    onSelectChange(selectedRowKeys) {
        this.setState({ selectedRowKeys });
    },
    getSearchField(){
        var _search = this.refs.search.getForm().getFieldsValue();
        return _search;
    },
    beforeClearSearch(){
        this.refs.search.getForm().resetFields();
        return {};
    },
    doSearch(clearSearch = 0){
        let search = clearSearch == 1 ? this.beforeClearSearch():this.getSearchField();
        var {pagination,filters,sortOrder,sortColumn} = this.refs.table.state;
        if(clearSearch == 1){
            this.refs.table.setState({filters:{}});
            filters = {};
        }
        pagination.current = 1;
        this.setState({pagination});
        this.fetchList({
            limit: pagination.pageSize,
            page: pagination.current,
            sorter:JSON.stringify({order:sortOrder,field:(sortColumn && sortColumn.key) ? sortColumn.key : ""}),
            filters:JSON.stringify({...filters}),
            search:JSON.stringify(search)
        });
    },
    clearSearch(){
        this.context.dataStore.del_mission_query()
        this.doSearch(1);
    },
    componentDidMount(){
        this.fetchList(this.context.dataStore.get_mission_query());
    },
	render() {
        const { loading, selectedRowKeys } = this.state;
        const rowSelection = {
            selectedRowKeys,
            onChange: this.onSelectChange
        };

        return (
            <div>
                <SearchPanel parent={this} ref="search"/>

                <div style={{ marginRight: 16,marginBottom: 16 , marginTop: 0,textAlign:"right" }}>
                    <Button.Group>
                        <Button type="primary" onClick={this.addRow}>
                            <Icon type="plus" /> 新加
                        </Button>
                    </Button.Group>
                </div>
                <Table ref="table"
                    rowSelection={rowSelection}
                    columns={this.state.columns}
                    dataSource={this.state.rows}
                    loading={this.state.loading}
                    pagination={this.state.pagination}
                    onChange={this.handleTableChange}
                />
            </div>
		);
	}
});
