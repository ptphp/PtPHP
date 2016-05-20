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
        const { getFieldProps } = this.props.form;
        let span_lable = 4;
        let span_val = 20;
        console.log(this.getFieldValue('pid'));
        return (
            <Form horizontal className="edit_main">
                <Row className="edit_row">
                    <Col span="12" style={{display:"none"}}>
                        <Form.Item
                            label="："
                            labelCol={{ span:span_lable }}
                            wrapperCol={{ span: span_val }}>
                            <Input {...getFieldProps('dep_pid', {initialValue: this.getFieldValue('pid')})}/>
                        </Form.Item>
                    </Col>
                    <Col span="12">
                        <Form.Item
                            label="部门名："
                            labelCol={{ span:span_lable }}
                            wrapperCol={{ span: span_val }}>
                            <Input {...getFieldProps('dep_name', {initialValue: this.getFieldValue('dep_name')})}/>
                        </Form.Item>
                    </Col>
                </Row>
            </Form>
		);
	}
});

export default Form.create()(FormView);