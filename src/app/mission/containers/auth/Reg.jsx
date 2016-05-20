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
            showPwdModal:false,
            loading:false,
            site_title:"注册",

            mobile:"",
            password:"",
            captcha:"",

            nextBtnDisabled:true,
            nextBtn1Disabled:true,

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
    componentDidMount(){
        Utils.set_site_title(this.state.site_title);
    },
    goBack(){
        history.go(-1)
    },
    onMobileChanged(e){
        let mobile = e.target.value;
        if(!Utils.checker.is_mobile(mobile)){
            this.setState({mobile,nextBtnDisabled:true});
        }else{
            this.setState({mobile,nextBtnDisabled:false});
        }
    },
    getCaptcha(){
        var {mobile} = this.state;
        if(!Utils.checker.is_mobile(mobile)) return false;
        this.setState({loading:true});
        this.context.dataStore.auth_get_reg_captcha(mobile,(result,error)=>{
            if(error){
                let {alert} = this.state;
                alert.title = result;
                this.setState({
                    loading:false,
                    alert,
                    showAlert:true
                });
            }else{
                if(result.second < 50){
                    let {alert} = this.state;
                    alert.title = "验证码已发送过,"+result.second+" 秒后重新获取";
                    this.setState({
                        loading:false,
                        alert,
                        showAlert:true
                    });
                }else{
                    this.state.toastTimer = setTimeout(()=> {
                        this.setState({toast_show: false});
                        this.showPwdModal();
                    }, 1500);
                    this.setState({
                        loading:false,
                        toast_show:true,
                        toast_title:"发送成功"
                    });
                }

            }
        })
    },
    doReg(){
        let {password,mobile,captcha} = this.state;
        if(!Utils.checker.is_mobile(mobile)) return false;
        if(!captcha || captcha.length != 6) return false;
        if(password && password.length >= 6){
            this.setState({loading:true});
            this.context.dataStore.auth_do_reg(mobile,password,captcha,(result,error)=>{
                console.log(result)
                if(error){
                    let {alert} = this.state;
                    alert.title = result;
                    this.setState({
                        loading:false,
                        alert,
                        showAlert:true
                    });
                }else{
                    this.state.toastTimer = setTimeout(()=> {
                        this.setState({toast_show: false});
                        this.showPwdModal();
                        //todo jump to other case?
                        let redirect = this.context.dataStore.get_redirect();
                        if(!redirect) redirect = "/";
                        this.context.router.push(redirect);
                    }, 1500);
                    this.setState({
                        loading:false,
                        toast_show:true,
                        toast_title:"注册成功"
                    });
                }
            })
        }
    },
    onCaptchaChanged(e){
        let captcha = e.target.value;
        let {password} = this.state;
        if(captcha && captcha.length == 6 && password &&  password.length >= 6){
            this.setState({captcha,nextBtn1Disabled:false});
        }else{
            this.setState({captcha,nextBtn1Disabled:true});
        }
    },
    onPwdChanged(e){
        let password = e.target.value;
        let {captcha} = this.state;
        if(password && password.length >= 6 && captcha && captcha.length == 6){
            this.setState({password,nextBtn1Disabled:false});
        }else{
            this.setState({password,nextBtn1Disabled:true});
        }
    },
    showPwdModal(){
        this.setState({
            showPwdModal:true
        });
    },
    hidePwdModal(){
        this.setState({
            showPwdModal:false
        });
    },
    componentWillUnmount() {
        this.state.toastTimer && clearTimeout(this.state.toastTimer);
    },
    renderPwdModal(){
        let nextBtnProps = {disabled:true,type:"default"};
        if(!this.state.nextBtn1Disabled){nextBtnProps = {};}
        return (
            <Modal title="请输入密码" onClose={this.hidePwdModal} show={this.state.showPwdModal}>
                <Form>
                    <FormCell>
                        <CellHeader>
                            <Label>手机号</Label>
                        </CellHeader>
                        <CellBody>
                            {this.state.mobile}
                        </CellBody>
                    </FormCell>
                    <FormCell>
                        <CellHeader>
                            <Label>验证码</Label>
                        </CellHeader>
                        <CellBody>
                            <Input onChange={this.onCaptchaChanged} value={this.state.captcha} type="tel" placeholder="请输入6位验证码"/>
                        </CellBody>
                    </FormCell>
                    <FormCell>
                        <CellHeader>
                            <Label>密 码</Label>
                        </CellHeader>
                        <CellBody>
                            <Input type="password" onChange={this.onPwdChanged} value={this.state.password} placeholder="请输入密码"/>
                        </CellBody>
                    </FormCell>
                </Form>
                <ButtonArea>
                    <Button onClick={this.doReg} {...nextBtnProps}>下一步</Button>
                </ButtonArea>
                {this.renderTips()}
            </Modal>
        )
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
    render() {
        if(this.state.showPwdModal) return this.renderPwdModal();
        let nextBtnProps = {disabled:true,type:"default"};
        if(!this.state.nextBtnDisabled){nextBtnProps = {};}

        return (
            <Page className="reg-main" goBack={this.goBack} title={this.state.site_title}>
                <div className="banner">
                    <div className="logo"></div>
                </div>
                <Form>
                    <FormCell>
                        <CellHeader>
                            <Label>手机号</Label>
                        </CellHeader>
                        <CellBody>
                            <Input onChange={this.onMobileChanged} value={this.state.mobile} type="tel" placeholder="请输入手机号"/>
                        </CellBody>
                    </FormCell>
                </Form>
                <ButtonArea>
                    <Button onClick={this.getCaptcha} {...nextBtnProps}>获取验证码</Button>
                </ButtonArea>
                <div className="auth_bottom">
                    <div className="cnt">
                        <a href="#/auth/login">已有帐号? 去登录</a>
                    </div>
                </div>
                {this.renderTips()}
            </Page>
        );
    }
});
