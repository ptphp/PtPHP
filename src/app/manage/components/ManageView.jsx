'use strict';
var React = require('react');
import {Icon, Breadcrumb, QueueAnim} from 'antd';
import Sider from "./Sider";
var Sentry = require('react-sentry');
import LoadingView from "./LoadingView";
import TopUserView from "./TopUserView";

export default React.createClass({
    contextTypes: {
        dataStore: React.PropTypes.object.isRequired,
        router: React.PropTypes.object.isRequired
    },
    propTypes: {
        location: React.PropTypes.object
    },
    mixins: [Sentry],
    getInitialState(){
        return {
            logined:false,
            loadingSetting:true,
        };
    },
    componentDidMount () {
        //console.log("ManageView componentDidMount",this.context.dataStore);
        this.watch(this.context.dataStore, 'not-login', ()=> {
            //console.log("app not login",this.props.location.pathname);
            if(window.sync_time_id) {clearTimeout(window.sync_time_id);}
            this.context.router.push('/auth/login?redirect=' + this.props.location.pathname);
        });
        this.context.dataStore.loadSetting((result,error)=>{
            if(!error){
                if(this.isMounted()){
                    this.setState({
                        loadingSetting:false
                    });
                }
                if(window.sync_time_id) {clearTimeout(window.sync_time_id);}
                this.context.dataStore.synchronize();
            }
        });
    },
    render () {
        if(this.state.loadingSetting){ return <LoadingView />;}
        const key = this.props.location.pathname;
        //console.log(key)
        return (
            <div className='ant-layout-aside'>
                <Sider {...this.props}/>
                <div className='ant-layout-main'>
                    <div className='ant-layout-header'>
                        <a className='top-menu' >
                            <Icon type='bars' />
                        </a>
                        <TopUserView />
                    </div>
                    <div className='ant-layout-breadcrumb'>
                        <Breadcrumb {...this.props} />
                    </div>
                    <div className='ant-layout-container' >
                        <div className='ant-layout-content'>
                            <QueueAnim type={['right', 'left']} className='pt-router-wrap' duration={30}>
                                {React.cloneElement(this.props.children || <div />, {key: key})}
                            </QueueAnim>
                        </div>
                    </div>
                    <div className='ant-layout-footer'>
                        版权所有 © 2016
                    </div>
                </div>
            </div>
        );
    }
});
