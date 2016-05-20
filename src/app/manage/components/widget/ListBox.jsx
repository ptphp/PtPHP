'use strict';
import React from 'react';
import { Row,Col,Tooltip,message,Table,Popconfirm ,Icon , Button} from 'antd';

export default React.createClass({
    onSelectChange(selectedRowKeys) {
        this.props.parent.setState({ selectedRowKeys });
    },
    getSearchField(){
        let {parent} = this.props;
        var _search = {};
        if(parent.refs.search){
            _search = parent.refs.search.getForm().getFieldsValue();
        }
        return _search;
    },
    handleTableChange(pagination, filters, sorter) {
        let {parent} = this.props;
        const pager = parent.state.pagination;
        pager.current = pagination.current;
        this.setState({pagination: pager});
        parent.action_list({
            limit: pagination.pageSize,
            page: pagination.current,
            sorter:JSON.stringify({order:sorter.order,field:sorter.field}),
            filters:JSON.stringify({...filters}),
            search:JSON.stringify(this.getSearchField())
        });
    },
    render(){
        let {parent} = this.props;
        let tableProps = {
            ref:"table",
            columns:parent.state.columns,
            dataSource:parent.state.rows,
            loading:parent.state.loading,
            pagination:parent.state.pagination,
            onChange:this.handleTableChange,
            rowSelection:{
                selectedRowKeys:parent.state.selectedRowKeys,
                onChange:this.onSelectChange
            }
        };
        return (
            <div>
                <Row>
                    <Col span="12">
                        <Row type="flex" justify="start">
                            <Col span="12">
                                <Button.Group>
                                    <Button type="primary" onClick={parent.action_add}>
                                        <Icon type="plus" /> 新加
                                    </Button>
                                </Button.Group>&nbsp;&nbsp;&nbsp;&nbsp;
                                <Popconfirm title="确定要删除吗？"
                                            placement="top"
                                            onConfirm={parent.action_remove}>
                                    <Button type="primary"
                                            disabled={!parent.state.selectedRowKeys.length > 0}
                                            loading={parent.state.loading_action_remove}>
                                        <Icon type="delete" /> 删除
                                    </Button>
                                </Popconfirm>
                                <span style={{ marginLeft: 8 }}>
                                    {
                                        parent.state.selectedRowKeys.length > 0 ?
                                            `选择了 ${parent.state.selectedRowKeys.length} 条记录` :
                                            ''
                                    }
                                </span>
                            </Col>
                        </Row>
                    </Col>
                    <Col span="12">
                        <Row type="flex" justify="end">
                            <Col>

                            </Col>
                        </Row>
                    </Col>
                </Row>
                <Row style={{marginTop:16}}>
                    <Col span="24">
                        <Table {...tableProps} />
                    </Col>
                </Row>
            </div>
        )
    }
});
