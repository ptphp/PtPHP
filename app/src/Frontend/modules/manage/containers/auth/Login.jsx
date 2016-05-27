'use strict';
/* global JSEncrypt,Utils */
import React from 'react';
import {Form, Row, Col, Input, Button, Icon, message} from 'antd';
const FormItem = Form.Item;

export default Form.create()(React.createClass({
    contextTypes: {
        router: React.PropTypes.object.isRequired,
        store: React.PropTypes.object.isRequired
    },
    propTypes: {
        form: React.PropTypes.object
    },
    getInitialState() {
        return {
            loading:true,
            encryptData: null,
            useRsaAuth:false
        };
    },
    doLogin(e) {
        e.preventDefault();
        //console.log('收到表单值：', this.props.form.getFieldsValue());
        let {username, password } = this.props.form.getFieldsValue();
        if (!username) {
            return message.error("手机号不能为空");
        }
        if (!password) {
            return message.error("密码不能为空");
        }
        var data = {};
        data.username = username;
        data.password = password;
        if(this.state.useRsaAuth){
            //debugger;
            var encryptData = this.state.encryptData;
            if (!encryptData) {
                return message.error("encryptData is null");
            }
            var encrypt = new JSEncrypt();
            encrypt.setPublicKey(encryptData.public_key);
            data[encryptData.field_name] = encryptData.field_value;

            data.username = encrypt.encrypt(data.username);
            data.password = encrypt.encrypt(data.password);
        }
        this.context.store.doLogin(data,(result,error)=>{
            this.setState({loading: false});
            if(!error){
                message.success(result.message);
                this.context.store.setLogin();
                setTimeout(()=>{
                    this.context.router.push("/");
                },1000);
            }
        });
    },
    goWechatLogin(){
        location.href = this.store.getWechatLoginUrl();
    },
    componentDidMount(){
        this.setState({loading: true},()=>{
            this.context.store.getAuthInfo(({encryptData,useRsaAuth})=>{
                //console.log(encryptData);
                this.setState({
                    encryptData,
                    useRsaAuth:useRsaAuth,
                    loading:false
                });
            });
        });
    },
    render () {
        const { getFieldProps } = this.props.form;
        const formItemLayout = {
            labelCol: { span: 4 },
            wrapperCol: { span: 18 },
        };
        return (
            <div className="login">
                <Form horizontal onSubmit={this.doLogin} className="login_form">
                    <Row style={{marginBottom:16,marginTop:16,textAlign:"center",fontSize:22,fontWeight:600}}>
                        <Col span="24">
                            登 陆
                        </Col>
                    </Row>
                    <FormItem
                        id="username"
                        label="手机："
                        {...formItemLayout}
                        >
                        <Input type="text" placeholder="请输入手机号"
                            {...getFieldProps('username')}
                            />
                    </FormItem>
                    <FormItem
                        id="password"
                        label="密码："
                        {...formItemLayout}
                        >
                        <Input type="password" placeholder="请输入密码"
                            {...getFieldProps('password')}
                            />
                    </FormItem>
                    <Row style={{marginBottom:16}}>
                        <Col span="20" offset="2">
                            <Button type="primary" htmlType="submit"
                                    style={{width:"100%",height:36}}
                                    loading={this.state.loading}
                                    >确定</Button>
                        </Col>
                    </Row>
                    <Row style={{display:"none"}}>
                        <Col span="20" offset="2">
                            <Button type="ghost" htmlType="button"
                            style={{width:"100%",height:36}}
                            onClick={this.goWechatLogin}
                            ><Icon type="qrcode"/>微信登录</Button>
                        </Col>
                    </Row>
                </Form>
            </div>
        );
    }
}));
