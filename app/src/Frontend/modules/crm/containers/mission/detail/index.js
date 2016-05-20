/**
 * Created by jf on 15/12/10.
 */

"use strict";

import React from 'react';
import Tappable from 'react-tappable';
import {
    Panel,
    Button,
    CellsTitle,
    PanelHeader,
    PanelBody,
    PanelFooter,
    MediaBox,
    MediaBoxHeader,
    MediaBoxBody,
    MediaBoxTitle,
    MediaBoxDescription,
    MediaBoxInfo,
    MediaBoxInfoMeta,
    Cells,
    Cell,
    CellHeader,
    CellBody,
    Toast,
    Article,
    CellFooter,
    Dialog

} from '../../../../../index';
const {Alert} = Dialog;

var Sentry = require('react-sentry');
import Page from '../../../components/page/index';
import AvatarView from "./AvatarView.jsx";

import './index.less';

export default React.createClass( {
    mixins: [Sentry],
    contextTypes: {
        dataStore: React.PropTypes.object.isRequired,
        router: React.PropTypes.object.isRequired
    },

    getInitialState(){
        return {
            loading:true,
            mission:null,
            user_mission:null,
            user_missions:null,
            avatars:[],
            missionBtnStatus:0,
            join_users_total:0,
            toast_title:"完成",
            toast_show:false,
            toastTimer:null,

            showAlert: false,
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
    getDetail(){
        this.setState({loading:true});
        this.context.dataStore.mission_detail(this.props.params.id,(result,error)=>{
            if(error){
                this.setState({loading:false});
            }else{
                if(this.isMounted()){
                    let {mission,avatars,tasks,user_mission,user_missions,join_users_total} = result;
                    let missionBtnStatus = 0;
                    if(user_mission && user_mission.begin_time){
                        missionBtnStatus = user_mission.finish_time ? 2 : 1;
                    }
                    this.setState({
                        loading:false,
                        mission,
                        avatars,
                        user_mission,
                        user_missions,
                        tasks,
                        missionBtnStatus,
                        join_users_total
                    });
                }
            }
        });
    },
    componentDidMount(){
        this.getDetail();
    },
    onDoVerify(){
        let task_key = this.state.user_mission ? this.state.user_mission.task_key : 1;
        this.context.router.push("/mission/verify/" + this.state.mission.id+"/"+task_key);
    },
    componentWillUnmount() {
        this.state.toastTimer && clearTimeout(this.state.toastTimer);
    },
    onBeginTask:function(){
        if(!window.auth.user_id){
            window.go_login();
        }else{
            this.setState({loading:true});
            this.context.dataStore.begin_task(this.state.mission.id,(result,error)=>{
                if(error){
                    let {alert} = this.state;
                    alert.title = result;
                    this.setState({
                        alert,
                        showAlert:true,
                        loading:false
                    });
                }else{
                    this.state.toastTimer = setTimeout(()=> {
                        this.setState({toast_show: false});
                    }, 1500);
                    this.setState({
                        loading:false,
                        toast_show:true,
                        toast_title:"参加成功"
                    });
                    this.getDetail();
                }
            })
        }
    },
    onGoGl(id){
        this.context.router.push("/mission/example/" + id+"/"+this.props.params.id);
    },
    getVerifyDetail(id){
        var res = null;
        this.state.user_missions.map(detail=>{
            if(detail.id == id) res = detail
        });
        return res;
    },
    renderTaskCtl(task){
        let {user_mission,user_missions} = this.state;
        if(!user_mission) return null;

        if(task.key == user_mission.task_key){
            if(user_mission.verify_id){
                let verify = this.getVerifyDetail(user_mission.verify_id);
                if(verify.status == 0 ){
                    return <span className="btn task_ver">审核中</span>
                }
                if(verify.status == 1 ){
                    return <span className="btn task_finish">已完成</span>
                }
                if(verify.status == 2 ){
                    return <span onClick={this.onDoVerify} className="btn task_repeat">重新提交</span>
                }
            }else{
                return <span onClick={this.onDoVerify} className="btn task_do">交任务</span>
            }
        }else if(task.key < user_mission.task_key){
            return <span className="btn task_finish">已完成</span>
        }
    },
    renderDetail(){
        let {mission,avatars} = this.state;
        if(!mission) return <div></div>

        return <div>
            <Panel>
                <PanelBody>
                    <MediaBox type="appmsg">
                        <MediaBoxHeader><img src={mission.thumb} /></MediaBoxHeader>
                        <MediaBoxBody>
                            <MediaBoxTitle>{mission.title}</MediaBoxTitle>
                            <MediaBoxDescription >
                                <span style={{fontSize:12}}>
                                    奖励： <span className="text_red">{mission.award}</span>元绿电
                                </span>
                                <span style={{display:"block",paddingTop:8,paddingBottom:15}}>
                                    {
                                        this.state.missionBtnStatus == 0 ?
                                            <span className="join_btn do_join" onClick={this.onBeginTask}>参与</span>:
                                            <span className="join_btn has_join">已报名</span>
                                    }
                                </span>
                            </MediaBoxDescription>

                        </MediaBoxBody>
                    </MediaBox>
                </PanelBody>
            </Panel>

            <CellsTitle>任务说明</CellsTitle>
            <Article className="Article mission-tips">
                <PanelBody>
                    <div dangerouslySetInnerHTML={{__html: mission.tips}} />
                </PanelBody>
            </Article>
            <CellsTitle>任务奖励</CellsTitle>
            <Article className="Article" style={{paddingLeft:24,paddingTop:16,paddingBottom:0}}>
                <section className="xiandingwei">
                    {this.state.tasks.map(task=>{
                        let ongoing = false;
                        if(this.state.user_mission && task.key == this.state.user_mission.task_key){
                            ongoing = true;
                        }
                        let isLast = task.key == this.state.tasks.length;
                        let class_name = ongoing ? "TaskItem Article_task" :  "TaskItem Articleheight";
                        if(this.state.user_mission && task.key < this.state.user_mission.task_key){
                            class_name += " finish";
                        }
                        return (
                            <div className={class_name} key={task.key}>
                                <i className="num_bg">{task.key}</i>
                                <div className="top-award">奖励 <em>{task.award}</em> 元</div>
                                <div className ="Articleheight_text">{task.title}</div>
                                <div className="ctl">
                                    {
                                        task.example ?
                                            <span onClick={this.onGoGl.bind(this,task.id)} className="btn gl">攻略</span>:null
                                    }
                                    {this.renderTaskCtl(task)}
                                </div>
                                {isLast?<div className='zhengdang'>&nbsp;&nbsp;&nbsp;</div>:null}
                            </div>
                        )
                    })}
                    <div className="boderleft"></div>
                </section>

            </Article>
            <CellsTitle>示例</CellsTitle>
            <Article className="Article">
                <section dangerouslySetInnerHTML={{__html: mission.example}} />
            </Article>

            <CellsTitle>已参加人员({this.state.join_users_total})</CellsTitle>
            <CellBody className ='txbg'>
                <AvatarView avatars={avatars} />
            </CellBody>
        </div>;
    },
    render() {
        let result = this.renderDetail();
        return (
            <Page className="mission-detail" goBack={()=>{
            this.context.router.push("/mission/home");
            }} title="绿电任务">
                {result}
                <Toast icon="loading" show={this.state.loading}>加载中...</Toast>
                <Toast show={this.state.toast_show}>{this.state.toast_title}</Toast>
                <Alert title={this.state.alert.title} buttons={this.state.alert.buttons} show={this.state.showAlert} />
            </Page>
        );
    }
});