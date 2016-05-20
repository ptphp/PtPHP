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
    componentDidMount(){

    },
    onChangeDepartment(value){
        if(value && value != "d_1"){
            let selectedPotId = null;
            let selectedDepId = value.replace("d_","");
            let {selectedRow} = this.props.parent.state;
            if(selectedRow && selectedRow.dep_id == selectedDepId && selectedRow.pot_id){
                selectedPotId = selectedRow.pot_id;
            }else{
                selectedPotId = "";
            }
            this.props.parent.setState({
                selectedDepId:selectedDepId,
                selectedPotId:selectedPotId
            });
        }
    },
    getDepartmentValue(){
        let {selectedDepId} = this.props.parent.state;

        return selectedDepId ? selectedDepId:""
    },
    getPositions(){
        let {selectedDepId,departments} = this.props.parent.state;
        console.log(selectedDepId,departments)
        let positions = [];
        if(departments.positions[selectedDepId]){
            positions = departments.positions[selectedDepId];
        }
        return positions;
    },
    getRoleId(){
        let role_id = this.getFieldValue("role_id");
        if(role_id){
            var _role_id = "";
            let {roles} = this.props.parent.state;
            roles.map(role=>{
                if(role.role_id == role_id) _role_id = role_id;
            });
            return _role_id
        }else{
            return "";
        }
    },
    getPotId(){
        var {selectedPotId,selectedDepId,departments} = this.props.parent.state;
        let pot_id = "";
        if(departments.positions[selectedDepId]){
            let positions = departments.positions[selectedDepId];
            positions.map(position=>{
                if(position.pot_id == selectedPotId) pot_id = selectedPotId;
            });
        }
        return pot_id;
    },
    onChangePosition(v){
        this.props.parent.setState({
            selectedPotId: v
        });
    },
	render() {
        let {departments,roles,selectedDepId} = this.props.parent.state;
        const { getFieldProps } = this.props.form;
        let span_lable = 4;
        let span_val = 20;
        const tProps = {
            treeData:departments.rows,
            value: selectedDepId ? "d_"+selectedDepId :"",
            onChange: this.onChangeDepartment,
            multiple: false,
            placeholder:"请选择",
            style: {
                width: "100%",
            }
        };
        let positions = this.getPositions();
        return (
            <Form horizontal className="edit_main">
                <Row className="edit_row">
                    <Col span="12">
                        <Form.Item
                            label="姓名："
                            labelCol={{ span:span_lable }}
                            wrapperCol={{ span: span_val }}>
                            <Input {...getFieldProps('stf_name', {initialValue: this.getFieldValue('stf_name')})}/>
                        </Form.Item>
                    </Col>
                </Row>
                <Row className="edit_row">
                    <Col span="12">
                        <Form.Item
                            label="手机号："
                            labelCol={{ span:span_lable }}
                            wrapperCol={{ span: span_val }}>
                            <Input {...getFieldProps('mobile', {initialValue: this.getFieldValue('mobile')})}/>
                        </Form.Item>
                    </Col>
                </Row>
                <Row className="edit_row">
                    <Col span="12">
                        <Form.Item
                            label="部门："
                            labelCol={{ span:span_lable }}
                            wrapperCol={{ span: span_val }}>
                            <TreeSelect {...tProps} />
                        </Form.Item>
                    </Col>
                </Row>
                <Row className="edit_row">
                    <Col span="12">
                        <Form.Item
                            label="职位："
                            labelCol={{ span:span_lable }}
                            wrapperCol={{ span: span_val }}>
                            <Select size="large" placeholder="请选择" value={this.getPotId()} onChange={this.onChangePosition}
                                    style={{ width: 200 }}>
                                <Option value="">请选择</Option>
                                {
                                    positions.map(position=>{
                                        return (
                                            <Option key={position.pot_id} value={position.pot_id}>{position.pot_name}</Option>
                                        )
                                    })
                                }
                            </Select>
                        </Form.Item>
                    </Col>
                </Row>
                <Row className="edit_row">
                    <Col span="12">
                        <Form.Item
                            label="角色："
                            labelCol={{ span:span_lable }}
                            wrapperCol={{ span: span_val }}>
                            <Select size="large" placeholder="请选择" {...getFieldProps('role_id',
                                {
                                    initialValue: this.getRoleId()
                                })}
                                    style={{ width: 200 }}>
                                <Option value="">请选择</Option>
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
            </Form>
		);
	}
});

export default Form.create()(FormView);