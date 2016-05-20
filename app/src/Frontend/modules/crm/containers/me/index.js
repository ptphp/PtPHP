/**
 * Created by jf on 15/12/10.
 */

//"use strict";

import React from 'react';
var Sentry = require('react-sentry');

import {
    Panel,
    PanelBody,
    Toast,
    Cells,
    Cell,
    CellHeader,
    CellBody,
    CellFooter,
    Ico
} from '../../../../index';

import TabBar from '../../components/tabbar/index';
import Page from '../../components/page/index';

import './index.less';
import iconSrc from './../../components/tabbar/img/home.png';
import solar_banner from './img/solar_banner.jpg';

const MeIndex = React.createClass( {
    mixins: [Sentry],
    contextTypes: {
        dataStore: React.PropTypes.object.isRequired,
        router: React.PropTypes.object.isRequired,
    },
    getInitialState(){
        return {

        }
    },
    componentDidMount(){
        //document.title = "";
    },
    goBack(){
        location.href = "/";
    },
    onTabClick(){
        console.log(1)
    },
    render() {
        return (
            <TabBar tabTitle="我" className="me-view">
                <div className="me-banner">
                    <div className="avatar">
                        <img src="http://7xq9wj.com1.z0.glb.clouddn.com/wechat/avatar/4b950db8b4fca77a190eb665a78cd08d" alt=""/>
                    </div>
                    <div className="username">
                        Joseph
                    </div>
                </div>
                <Cells access>
                    <Cell onTap={()=>{this.context.router.push("/me/mission")}}>
                        <CellHeader>
                            <Ico value="home"/>
                        </CellHeader>
                        <CellBody>
                            我的任务
                        </CellBody>
                        <CellFooter>
                        </CellFooter>
                    </Cell>
                </Cells>
                <Cells access>
                    <Cell onTap={()=>{this.context.router.push("/me/setting")}}>
                        <CellHeader>
                            <Ico value="setting"/>
                        </CellHeader>
                        <CellBody>
                            设置
                        </CellBody>
                        <CellFooter>
                        </CellFooter>
                    </Cell>
                </Cells>
            </TabBar>
        );
    },
    render1 () {
        return (
            <TabBar tabTitle="我" className="me-view">
                <div className="me-banner">
                    <div className="avatar">
                        <img src="http://7xq9wj.com1.z0.glb.clouddn.com/wechat/avatar/4b950db8b4fca77a190eb665a78cd08d" alt=""/>
                    </div>
                    <div className="username">
                        Joseph
                    </div>
                </div>
                <Cells access>
                    <Cell>
                        <CellHeader>
                            <img src={iconSrc} alt="" style={{display: `block`, width: `20px`, marginRight: `5px`}}/>
                        </CellHeader>
                        <CellBody>
                            绿电资产
                        </CellBody>
                        <CellFooter>
                        </CellFooter>
                    </Cell>
                    <Cell>
                        <CellHeader>
                            <img src={iconSrc} alt="" style={{display: `block`, width: `20px`, marginRight: `5px`}}/>
                        </CellHeader>
                        <CellBody>
                            绿电交易纪录
                        </CellBody>
                        <CellFooter>
                        </CellFooter>
                    </Cell>
                    <Cell>
                        <CellHeader>
                            <img src={iconSrc} alt="" style={{display: `block`, width: `20px`, marginRight: `5px`}}/>
                        </CellHeader>
                        <CellBody>
                            绿电订单
                        </CellBody>
                        <CellFooter>
                        </CellFooter>
                    </Cell>
                </Cells>
                <Cells access>
                    <Cell>
                        <CellHeader>
                            <img src={iconSrc} alt="" style={{display: `block`, width: `20px`, marginRight: `5px`}}/>
                        </CellHeader>
                        <CellBody>
                            我的任务
                        </CellBody>
                        <CellFooter>
                        </CellFooter>
                    </Cell>
                    <Cell>
                        <CellHeader>
                            <img src={iconSrc} alt="" style={{display: `block`, width: `20px`, marginRight: `5px`}}/>
                        </CellHeader>
                        <CellBody>
                            优惠券
                        </CellBody>
                        <CellFooter>
                        </CellFooter>
                    </Cell>
                </Cells>
                <Cells access>
                    <Cell>
                        <CellHeader>
                            <img src={iconSrc} alt="" style={{display: `block`, width: `20px`, marginRight: `5px`}}/>
                        </CellHeader>
                        <CellBody>
                            安全管理
                        </CellBody>
                        <CellFooter>
                        </CellFooter>
                    </Cell>
                    <Cell>
                        <CellHeader>
                            <img src={iconSrc} alt="" style={{display: `block`, width: `20px`, marginRight: `5px`}}/>
                        </CellHeader>
                        <CellBody>
                            关于我们
                        </CellBody>
                        <CellFooter>
                        </CellFooter>
                    </Cell>
                </Cells>
                <Cells access>
                    <Cell>
                        <CellHeader>
                            <img src={iconSrc} alt="" style={{display: `block`, width: `20px`, marginRight: `5px`}}/>
                        </CellHeader>
                        <CellBody>
                            解除微信绑定
                        </CellBody>
                        <CellFooter>
                        </CellFooter>
                    </Cell>
                    <Cell>
                        <CellHeader>
                            <img src={iconSrc} alt="" style={{display: `block`, width: `20px`, marginRight: `5px`}}/>
                        </CellHeader>
                        <CellBody>
                            退出登录
                        </CellBody>
                        <CellFooter>
                        </CellFooter>
                    </Cell>
                </Cells>
            </TabBar>
        );
    }
});
import MeSetting from './setting';
import MeAboutUs from './aboutus';

export {
    MeIndex,
    MeSetting,
    MeAboutUs
}