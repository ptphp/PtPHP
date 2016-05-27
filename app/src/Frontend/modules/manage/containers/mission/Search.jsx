'use strict';
import React from 'react';
import { Form,Row,Col,Input,Icon,Button } from 'antd';

export default Form.create()(React.createClass({
    contextTypes: {
        store: React.PropTypes.object.isRequired
    },
    getInitialState() {
        return {};
    },
    componentDidMount(){
    },
    render() {
        const { getFieldProps } = this.props.form;
        return (
            <div style={{marginBottom: 16}}>
                <Form horizontal className="advanced-search-form" key="more">
                    <Row>
                        <Col span="8">
                            <Form.Item
                                label="名称："
                                labelCol={{ span: 10 }}
                                wrapperCol={{ span: 14 }}>
                                <Input placeholder="请输入名称" {...getFieldProps('title')}/>
                            </Form.Item>
                        </Col>
                    </Row>
                    <Row  type="flex" justify="end"  align="middle">
                        <Col style={{ width:480,textAlign: 'right',paddingRight:10 }}>
                            <Button type="primary" onClick={this.props.parent.doSearch} htmlType="button">
                                <Icon type="search"/> 搜索
                            </Button>
                            <Button type="dashed" onClick={this.props.parent.clearSearch}>
                                重置
                            </Button>
                        </Col>
                    </Row>
                </Form>
            </div>
        );
    }
}));
