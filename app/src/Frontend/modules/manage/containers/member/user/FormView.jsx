'use strict';
import React from 'react';
import { Form,Input,Row,Col,Tooltip,message,Table,Popconfirm ,Icon , Button} from 'antd';

const FormView = React.createClass({
    contextTypes: {
        dataStore: React.PropTypes.object.isRequired
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
        let mobileProps = {};
        if(this.props.parent.state.selectedRow &&
            this.props.parent.state.selectedRow.staff_mobile &&
            this.props.parent.state.selectedRow.staff_mobile.length > 0){
            mobileProps.disabled = true;
        }
        const { getFieldProps } = this.props.form;
        let span_lable = 4;
        let span_val = 20;
        return (
            <Form horizontal className="edit_main">
                <Row className="edit_row">
                    <Col span="12">
                        <Form.Item
                            label="手机号："
                            labelCol={{ span:span_lable }}
                            wrapperCol={{ span: span_val }}>
                            <Input {...mobileProps} {...getFieldProps('mobile', {initialValue: this.getFieldValue('mobile')})}/>
                        </Form.Item>
                    </Col>
                </Row>
                <Row className="edit_row">
                    <Col span="12">
                        <Form.Item
                            label="密码："
                            labelCol={{ span:span_lable }}
                            wrapperCol={{ span: span_val }}>
                            <Input {...getFieldProps('password', {initialValue: this.getFieldValue('password')})}/>
                        </Form.Item>
                    </Col>
                </Row>
            </Form>
		);
	}
});

export default Form.create()(FormView);