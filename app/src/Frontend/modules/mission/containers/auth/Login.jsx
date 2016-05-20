/**
 * Created by jf on 15/12/10.
 */

//"use strict";

import React from 'react';
var Sentry = require('react-sentry');

import {
    Toast,
    Cells,
    CellHeader,
    CellBody,
    Label,
    Input,
    Form,
    FormCell,
    ButtonArea,
    Button,
    Dialog,
    Modal
} from '../../../../index';
const {Alert} = Dialog;

import Page from '../../components/page/index';
import './index.less';

export default React.createClass( {
    mixins: [Sentry],
    contextTypes: {
        dataStore: React.PropTypes.object.isRequired,
        router: React.PropTypes.object.isRequired
    },
    getInitialState(){
        return {
            site_title:"登录",
            username:"",
            password:"",

            nextBtnDisabled:true,

            toast_title:"",
            toast_show:false,
            toastTimer:null,

            showAlert:false,
            alert: {
                title: '',
                buttons: [
                    {
                        label: '关闭',
                        onClick: this.hideAlert
                    }
                ]
            }
        }
    },
    hideAlert() {
        this.setState({showAlert: false});
    },
    componentWillUnmount() {
        this.state.toastTimer && clearTimeout(this.state.toastTimer);
    },
    componentDidMount(){
        Utils.set_site_title(this.state.site_title);
    },
    goBack(){
        history.go(-1)
    },

    renderTips(){
        return (
            <div>
                <Toast icon="loading" show={this.state.loading}>提交中...</Toast>
                <Toast show={this.state.toast_show}>{this.state.toast_title}</Toast>
                <Alert title={this.state.alert.title} buttons={this.state.alert.buttons} show={this.state.showAlert} />
            </div>
        )
    },
    onFieldChange(key,e){
        let value = e.target.value;
        var state =  this.state;
        let {password,username} = state;
        if(key == 'password'){
            password = value;
        }else{
            username = value;
        }

        if(username && username.length > 3 && password && password.length >=6){
            state['nextBtnDisabled'] = false;
        }else{
            state['nextBtnDisabled'] = true;
        }
        state[key] = value;
        this.setState(state);
    },
    doLogin(){
        let {password,username} =  this.state;
        if(!username || username.length <= 3 || !password || password.length < 6){
            return false;
        }
        this.setState({loading:true});
        this.context.dataStore.auth_do_login(username,password,(result,error)=>{
            if(error){
                let {alert} = this.state;
                alert.title = result;
                this.setState({
                    loading:false,
                    alert,
                    showAlert:true
                });
            }else{
                window.auth.is_logined = true;
                this.state.toastTimer = setTimeout(()=> {
                    this.setState({toast_show: false});
                    //todo jump to other case?
                    let redirect = this.context.dataStore.get_redirect();
                    if(!redirect) redirect = "/";
                    this.context.router.push(redirect);
                }, 1500);
                this.setState({
                    loading:false,
                    toast_show:true,
                    toast_title:"登录成功"
                });
            }
        })

    },
    render() {

        let nextBtnProps = {disabled:true,type:"default"};
        if(!this.state.nextBtnDisabled){nextBtnProps = {};}
        return (
            <Page className="login-main" goBack={this.goBack} title={this.state.site_title}>
                <div className="banner">
                    <div className="logo"></div>
                </div>
                <Form>
                    <FormCell>
                        <CellHeader>
                            <Label>用户名</Label>
                        </CellHeader>
                        <CellBody>
                            <Input value={this.state.username} onChange={this.onFieldChange.bind(this,'username')} placeholder="请输入手机号或用户名"/>
                        </CellBody>
                    </FormCell>
                    <FormCell>
                        <CellHeader>
                            <Label>密码</Label>
                        </CellHeader>
                        <CellBody>
                            <Input value={this.state.password} onChange={this.onFieldChange.bind(this,'password')} type="password" placeholder="请输入密码"/>
                        </CellBody>
                    </FormCell>
                </Form>
                <ButtonArea>
                    <Button {...nextBtnProps} onClick={this.doLogin}>下一步</Button>
                </ButtonArea>
                <div className="auth_bottom">
                    <div className="lft">
                        <a href="#/auth/reg">注册用户</a>
                    </div>
                    <div className="rgt">
                        <a href="#/auth/fgtpwd">忘记密码?</a>
                    </div>
                </div>

                {this.renderTips()}
            </Page>
        );
    }
});
