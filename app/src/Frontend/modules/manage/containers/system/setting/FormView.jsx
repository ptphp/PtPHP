'use strict';
import React from 'react';
import { Form,Input,Row,Col,Tooltip,message,Table,Popconfirm ,Icon , Button} from 'antd';

const FormView = React.createClass({
    contextTypes: {
        store: React.PropTypes.object.isRequired
    },
    getInitialState() {
        return {};
    },
    getFieldValue(key){
        let { selectedRow } = this.props.parent.state;
        return selectedRow && selectedRow[key] !== null ? selectedRow[key] : null;
    },
    componentDidMount(){

    },
	render() {
        const { getFieldProps } = this.props.form;
        let span_lable = 4;
        let span_val = 20;
        return (
            <Form horizontal className="edit_main">
                <Row className="edit_row">
                    <Col span="12">
                        <Form.Item
                            label="Key："
                            labelCol={{ span:span_lable }}
                            wrapperCol={{ span: span_val }}>
                            <Input {...getFieldProps('set_key', {initialValue: this.getFieldValue('set_key')})}/>
                        </Form.Item>
                    </Col>
                </Row>
                <Row className="edit_row">
                    <Col span="12">
                        <Form.Item
                            label="Title："
                            labelCol={{ span:span_lable }}
                            wrapperCol={{ span: span_val }}>
                            <Input {...getFieldProps('set_title', {initialValue: this.getFieldValue('set_title')})}/>
                        </Form.Item>
                    </Col>
                </Row>
                <Row className="edit_row">
                    <Col span="12">
                        <Form.Item
                            label="Value："
                            labelCol={{ span:span_lable }}
                            wrapperCol={{ span: span_val }}>
                            <Input type="textarea" rows="4"  {...getFieldProps('set_value', {initialValue: this.getFieldValue('set_value')})}/>
                        </Form.Item>
                    </Col>
                </Row>
            </Form>
		);
	}
});

export default Form.create()(FormView);