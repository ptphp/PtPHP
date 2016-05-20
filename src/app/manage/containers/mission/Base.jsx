'use strict';

import React from 'react';
import { Form,Row,Col,Input,Radio,Select,InputNumber,DatePicker } from 'antd';
const RadioButton = Radio.Button;
const RadioGroup = Radio.Group;
const FormItem = Form.Item;

export default Form.create()(React.createClass({
    contextTypes: {
        router: React.PropTypes.object.isRequired
    },
    getInitialState(){
        return {}
    },
    componentDidMount(){},
    getFieldValue(key){
        return this.props.parent.state.row && this.props.parent.state.row[key] !== null ? this.props.parent.state.row[key] : null;
    },
    getPics() {
        return this.props.parent.state.pics;
    },
    setPics(pics) {
        this.props.parent.setState({pics});
    },
    getTipsContent(){
        //console.log(this.props.parent.state)
        let content;
        //console.log(content)
        if(window['editor_tips'] && window['editor_tips'].getValue().length > 0){
            content = window['editor_tips'].getValue();
        }else{
            content = this.getFieldValue("tips");
        }
        return content ? content : "";
    },
    getExampleContent(){
        //console.log(this.props.parent.state)
        let content;
        //console.log(content)
        if(window['editor_example'] && window['editor_example'].getValue().length > 0){
            content = window['editor_example'].getValue();
        }else{
            content = this.getFieldValue("example");
        }
        return content ? content : "";
    },
    render(){
        const { getFieldProps } = this.props.form;
        let rm_props = {};
        if(this.props.parent.state.rowKey){
            rm_props.disabled = "disabled";
        }
        return (
            <div>
                <Form horizontal style={{marginTop:30}}>
                    <Row>
                        <Col span="24">
                            <FormItem
                                label="任务名称："
                                labelCol={{ span:3 }}
                                wrapperCol={{ span: 21 }}>
                                <Input {...getFieldProps('title', {initialValue: this.getFieldValue('title')})}/>
                            </FormItem>
                        </Col>
                    </Row>
                    <Row>
                        <Col span="24">
                            <FormItem
                                label="任务描述："
                                labelCol={{ span:3 }}
                                wrapperCol={{ span: 21 }}>
                                <Input rows="6" type="textarea" {...getFieldProps('desc', {initialValue: this.getFieldValue('desc')})}/>
                            </FormItem>
                        </Col>
                    </Row>
                    <Row>
                        <Col span="24">
                            <FormItem
                                label="商家名称："
                                labelCol={{ span:3 }}
                                wrapperCol={{ span: 21 }}>
                                <Input {...getFieldProps('com_name', {initialValue: this.getFieldValue('com_name')})}/>
                            </FormItem>
                        </Col>
                    </Row>
                    <Row>
                        <Col span="12">
                            <FormItem
                                label="任务标签："
                                labelCol={{ span:6 }}
                                wrapperCol={{ span: 18 }}>
                                <RadioGroup {...getFieldProps('tag', {initialValue: ""+this.getFieldValue('tag')})}>
                                    <RadioButton value="1">最热</RadioButton>
                                    <RadioButton value="2">新手任务</RadioButton>
                                    <RadioButton value="3">进阶任务</RadioButton>
                                </RadioGroup>
                            </FormItem>
                        </Col>
                        <Col span="12">
                            <FormItem
                                label="任务类型："
                                labelCol={{ span:6 }}
                                wrapperCol={{ span: 18 }}>
                                <Select  {...getFieldProps('type', {initialValue: (this.getFieldValue('type') == null) ? "":""+this.getFieldValue('type')})}>
                                    <Option value="">请选择</Option>
                                    <Option value="1">签到</Option>
                                    <Option value="2">关注</Option>
                                    <Option value="3">注册</Option>
                                    <Option value="4">分享</Option>
                                    <Option value="5">下单</Option>
                                    <Option value="6">进阶</Option>
                                </Select>
                            </FormItem>
                        </Col>
                    </Row>
                    <Row>
                        <Col span="12">
                            <FormItem
                                label="任务限制次数："
                                labelCol={{ span:6 }}
                                wrapperCol={{ span: 18 }}>
                                <InputNumber {...rm_props} {...getFieldProps('remain_times', {initialValue: this.getFieldValue('remain_times')})}/>
                            </FormItem>
                        </Col>
                        <Col span="12">
                            <FormItem
                                label="奖励绿电金额："
                                labelCol={{ span:6 }}
                                wrapperCol={{ span: 18 }}>
                                <InputNumber step="0.1" {...getFieldProps('award', {initialValue: this.getFieldValue('award')})}/>
                            </FormItem>
                        </Col>
                    </Row>
                    <Row>
                        <Col span="12">
                            <FormItem
                                label="任务按钮名称："
                                labelCol={{ span:6 }}
                                wrapperCol={{ span: 18 }}>
                                <Input {...getFieldProps('btn_name', {initialValue: this.getFieldValue('btn_name')})}/>
                            </FormItem>
                        </Col>
                    </Row>
                    <Row>
                        <Col span="12">
                            <FormItem
                                label="商家平台类型："
                                labelCol={{ span:6 }}
                                wrapperCol={{ span: 18 }}>
                                <RadioGroup {...getFieldProps('platform', {initialValue: this.getFieldValue('platform')})}>
                                    <RadioButton value="wechat">微信</RadioButton>
                                    <RadioButton value="app">APP</RadioButton>
                                </RadioGroup>
                            </FormItem>
                        </Col>
                        <Col span="12">
                            <FormItem
                                label="详情页平台名："
                                labelCol={{ span:6 }}
                                wrapperCol={{ span: 18 }}>
                                <Input {...getFieldProps('platform_name', {initialValue: this.getFieldValue('platform_name')})}/>
                            </FormItem>
                        </Col>
                    </Row>
                    <Row>
                        <Col span="12">
                            <FormItem
                                label="微信号："
                                labelCol={{ span:6 }}
                                wrapperCol={{ span: 18 }}>
                                <Input {...getFieldProps('wechat_account', {initialValue: this.getFieldValue('wechat_account')})}/>
                            </FormItem>
                        </Col>
                    </Row>

                    <Row>
                        <Col span="24">
                            <FormItem
                                label="有效时间："
                                labelCol={{ span:3 }}
                                wrapperCol={{ span: 21 }}>
                                <Col span="5">
                                    <DatePicker showTime
                                                format="yyyy-MM-dd HH:mm:ss"
                                                placeholder="开始时间"
                                        {...getFieldProps('start_time', {initialValue: this.getFieldValue('start_time')})} />
                                </Col>
                                <Col span="1">
                                    <p className="ant-form-split">-</p>
                                </Col>
                                <Col span="6">
                                    <DatePicker showTime
                                                format="yyyy-MM-dd HH:mm:ss"
                                                placeholder="结束时间"
                                        {...getFieldProps('end_time', {initialValue: this.getFieldValue('end_time')})} />
                                </Col>
                            </FormItem>
                        </Col>
                    </Row>
                    <Row>
                        <Col span="12">
                            <FormItem
                                label="状态："
                                labelCol={{ span:6 }}
                                wrapperCol={{ span: 18 }}>
                                <RadioGroup {...getFieldProps('status', {initialValue: ""+this.getFieldValue('status')})}>
                                    <RadioButton value="1">上架</RadioButton>
                                    <RadioButton value="2">下架</RadioButton>
                                </RadioGroup>
                            </FormItem>
                        </Col>
                        <Col span="12">
                            <FormItem
                                label="推荐："
                                labelCol={{ span:6 }}
                                wrapperCol={{ span: 18 }}>
                                <RadioGroup {...getFieldProps('is_rec', {initialValue: ""+this.getFieldValue('is_rec')})}>
                                    <RadioButton value="0">否</RadioButton>
                                    <RadioButton value="1">是</RadioButton>
                                </RadioGroup>
                            </FormItem>
                        </Col>
                    </Row>
                    <Row>
                        <Col span="12">
                            <FormItem
                                label="排序："
                                labelCol={{ span:6 }}
                                wrapperCol={{ span: 18 }}>
                                <InputNumber {...getFieldProps('ord', {initialValue: this.getFieldValue('ord')})}/>
                            </FormItem>
                        </Col>
                    </Row>
                    <Row>
                        <Col span="12">
                            <FormItem
                                label="备注："
                                labelCol={{ span:6 }}
                                wrapperCol={{ span: 18 }}>
                                <Input {...getFieldProps('note', {initialValue: this.getFieldValue('note')})}/>
                            </FormItem>
                        </Col>
                    </Row>
                </Form>
            </div>
        );
    }
}));
