'use strict';
import React from 'react';
import { Form,Row,Col,Select,Input,message,Icon,Button,TreeSelect } from 'antd';

export default Form.create()(React.createClass({
    contextTypes: {
        dataStore: React.PropTypes.object.isRequired
    },
    getInitialState() {
        return {};
    },
    getSearchField(){
        return this.props.form.getFieldsValue()
    },
    beforeClearSearch(){
        this.props.form.resetFields();
        return {};
    },
    doSearch(clearSearch = 0){
        let search = clearSearch == 1 ? this.beforeClearSearch():this.getSearchField();
        var {parent} = this.props;
        if(parent.refs.listTable){
            var {pagination,filters,sortOrder,sortColumn} = parent.refs.listTable.refs.table.state;
            if(clearSearch == 1){
                parent.refs.listTable.refs.table.setState({filters:{}});
                filters = {};
            }
            this.props.parent.action_list({
                limit: pagination.pageSize,
                page: 1,
                sorter:JSON.stringify({order:sortOrder,field:(sortColumn && sortColumn.key) ? sortColumn.key : ""}),
                filters:JSON.stringify({...filters}),
                search:JSON.stringify(search)
            });
        }else{
            if(clearSearch == 1){
                filters = {};
            }
            this.props.parent.action_list({
                //limit: pagination.pageSize,
                page: 1,
                //sorter:JSON.stringify({order:sortOrder,field:(sortColumn && sortColumn.key) ? sortColumn.key : ""}),
                //filters:JSON.stringify({...filters}),
                search:JSON.stringify(search)
            });
        }


    },
    clearSearch(){
        //this.context.dataStore.del_mission_query()
        this.doSearch(1);
    },
    componentDidMount(){},
    render() {
        const { getFieldProps } = this.props.form;
        //let {roles} = this.props.parent.state;
        let roles = [];
        return (
            <div style={{marginBottom: 16}}>
                <Form horizontal className="advanced-search-form" key="more">
                    <Row>
                        <Col span="8">
                            <Form.Item
                                label="手机号："
                                labelCol={{ span: 5 }}
                                wrapperCol={{ span: 19 }}>
                                <Input placeholder="请输入" {...getFieldProps('mobile')}/>
                            </Form.Item>
                        </Col>
                        <Col span="8">
                            <Form.Item
                                label="姓名："
                                labelCol={{ span: 5 }}
                                wrapperCol={{ span: 19 }}>
                                <Input placeholder="请输入" {...getFieldProps('stf_name')}/>
                            </Form.Item>
                        </Col>
                        <Col span="8">
                            <Form.Item
                                label="角色："
                                labelCol={{ span: 5 }}
                                wrapperCol={{ span: 19 }}>
                                <Select size="large" placeholder="请选择" {...getFieldProps('role_id')}>
                                    <Option value="">不限</Option>
                                    {
                                        roles.map(role=>{
                                            return (
                                                <Option key={role.role_id} value={role.role_id}>{role.role_name}</Option>
                                            )
                                        })
                                    }
                                </Select>
                            </Form.Item>
                        </Col>
                    </Row>
                    <Row  type="flex" justify="end"  align="middle">
                        <Col style={{ width:480,textAlign: 'right',paddingRight:10 }}>
                            <Button.Group>
                                <Button type="primary" onClick={this.doSearch} htmlType="button">
                                    <Icon type="search"/> 搜索
                                </Button>
                                <Button type="primary" onClick={this.clearSearch}>
                                    重置
                                </Button>
                            </Button.Group>
                        </Col>
                    </Row>
                </Form>
            </div>
        );
    }
}));
