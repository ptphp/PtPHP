/**
 * Created by jf on 15/12/10.
 */

"use strict";

import React from 'react';

import { ButtonArea,
    Button,
    CellsTitle,
    CellBody,
    Cell,
    Form,
    FormCell,
    TextArea,
    Uploader,
    Toast,
    Dialog
} from '../../../../../index';
const {Alert} = Dialog;

var Sentry = require('react-sentry');
import Page from '../../../components/page/index';
import './index.less';

export default React.createClass( {
    mixins: [Sentry],
    contextTypes: {
        store: React.PropTypes.object.isRequired,
        router: React.PropTypes.object.isRequired
    },
    getInitialState(){
        return {
            loading:false,
            pics:[],
            note:"",


            toast_title:"完成",
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

    onUploadChange(file,e){
        let pics = this.state.pics;
        pics.push({
            url:file.data,
            onRemove:(idx)=>{
                let {pics} = this.state
                pics = pics.filter((file,key)=>{
                    return idx !== key;
                });
                this.setState({pics});
            }
        });
        this.setState({
            pics
        });
    },
    hideAlert() {
        this.setState({showAlert: false});
    },
    componentDidMount(){
        Utils.set_site_title("交任务");
    },
    doVerify(){
        let {alert} = this.state;
        if(this.state.pics.length == 0){
            alert.title = "请先上传图片";
            this.setState({
                alert,
                showAlert:true
            });
            return;
        }
        let $mession_id = this.props.parent.state.mission.id;
        let $task_key = this.props.parent.state.taskKey;
        let pics = [];
        this.state.pics.map(pic=>{
            pics.push(pic.url)
        });
        let $note = this.state.note;
        this.setState({
            loading:true
        });
        this.context.store.do_verify($mession_id,$task_key,pics.join("|"),$note,(result,error)=>{
            if(error){
                //alert(result);
            }else{
                this.state.toastTimer = setTimeout(()=> {
                    this.setState({toast_show: false});
                    this.props.parent.hideVerifyModal();
                    this.props.parent.getDetail();
                }, 1500);
                this.setState({
                    loading:false,
                    toast_show:true,
                    toast_title:"提交成功"
                });
            }
        })
    },

    componentWillUnmount() {
        this.state.toastTimer && clearTimeout(this.state.toastTimer);
    },
    renderDetail(){
        return (
            <div>
                <Form>
                    <FormCell>
                        <CellBody>
                            <Uploader
                                title="审核图片上传"
                                maxCount={6}
                                files={this.state.pics}
                                onError={msg => alert(msg)}
                                onChange={this.onUploadChange}
                            />
                        </CellBody>
                    </FormCell>
                    <CellsTitle>备注</CellsTitle>
                    <FormCell>
                        <CellBody>
                            <TextArea valu={this.state.note} onChange={e=>{this.setState({note:e.target.value})}} placeholder="请输入备注" rows="3" maxlength="200" />
                        </CellBody>
                    </FormCell>

                    <div style={{margin:"16px 20px"}}>
                        <Button onClick={this.doVerify}>提交</Button>
                    </div>

                </Form>
            </div>
        )
    },
    render() {
        let detail = this.renderDetail();
        return (
            <div>
                {detail}
                <Toast icon="loading" show={this.state.loading}>提交中...</Toast>
                <Toast show={this.state.toast_show}>{this.state.toast_title}</Toast>
                <Alert title={this.state.alert.title} buttons={this.state.alert.buttons} show={this.state.showAlert} />
            </div>
        );
    }
});