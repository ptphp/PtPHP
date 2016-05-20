'use strict';
import React from 'react';
import { Form,Input,Select,TreeSelect,Row,Col,Tooltip,message,Table,Popconfirm ,Icon , Button} from 'antd';

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
    onChangeCat(value){
        if(value && value != "d_1"){
            let selectedCatId = value.replace("d_","");
            this.props.parent.setState({
                selectedCatId
            });
        }
    },
    getDepartmentValue(){
        let {selectedCatId} = this.props.parent.state;
        return selectedCatId ? selectedCatId:""
    },
    componentDidMount(){

    },

	render() {
        let {cats,selectedCatId} = this.props.parent.state;
        const tProps = {
            treeData:cats.rows,
            value: selectedCatId ? "d_"+selectedCatId :"",
            onChange: this.onChangeCat,
            multiple: false,
            placeholder:"请选择",
            style: {
                width: "100%",
            }
        };
        const { getFieldProps } = this.props.form;
        let span_lable = 4;
        let span_val = 20;

        return (
            <Form horizontal className="edit_main">
                <Row className="edit_row">
                    <Col span="12">
                        <Form.Item
                            label="商品名："
                            labelCol={{ span:span_lable }}
                            wrapperCol={{ span: span_val }}>
                            <Input {...getFieldProps('god_name', {initialValue: this.getFieldValue('god_name')})}/>
                        </Form.Item>
                    </Col>
                </Row>
                <Row className="edit_row">
                    <Col span="12">
                        <Form.Item
                            label="商品分类："
                            labelCol={{ span:span_lable }}
                            wrapperCol={{ span: span_val }}>
                            <TreeSelect {...tProps} />
                        </Form.Item>
                    </Col>
                </Row>

            </Form>
		);
	}
});

export default Form.create()(FormView);