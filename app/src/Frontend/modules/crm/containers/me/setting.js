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
    ActionSheet,
    Ico
} from '../../../../index';

import Page from '../../components/page/index';

import './index.less';

export default React.createClass( {
    mixins: [Sentry],
    contextTypes: {
        store: React.PropTypes.object.isRequired,
        router: React.PropTypes.object.isRequired,
    },
    getInitialState(){
        return {
            showLogoutTips:false,
            logoutTipsMenus:[{
                label: '确认退出',
                onClick: ()=> {
                    alert("logout");
                    this.setState({showLogoutTips:false})
                }
            }],
            logoutTipsActions:[
                {
                    label: '返回',
                    onClick: ()=>{this.setState({showLogoutTips:false})}
                }
            ],

            showUnbndTips:false,
            UnbndTipsMenus:[{
                label: '确认解绑',
                onClick: ()=> {
                    alert("UnbndTips")
                    this.setState({showUnbndTips:false})
                }
            }],
            UnbndTipsActions:[
                {
                    label: '返回',
                    onClick: ()=>{this.setState({showUnbndTips:false})}
                }
            ]
        }
    },
    showAboutUs(){
        this.context.router.push("/me/aboutus");
    },
    unBindWechat(){
        alert("unBindWechat");
    },
    componentDidMount(){
    },
    render () {
        return (
            <Page title="设置" goBack={()=>{history.go(-1)}} className="me-view">
                <Cells access>
                    <Cell onTap={this.showAboutUs}>
                        <CellHeader>
                            <Ico value="aboutus"/>
                        </CellHeader>
                        <CellBody>
                            关于我们
                        </CellBody>
                        <CellFooter>
                        </CellFooter>
                    </Cell>
                </Cells>
                <Cells access>
                    <Cell onTap={()=>{this.setState({showUnbndTips:true})}}>
                        <CellHeader>
                            <Ico value="arrow_right"/>
                        </CellHeader>
                        <CellBody>
                            解除微信绑定
                        </CellBody>
                        <CellFooter>
                        </CellFooter>
                    </Cell>
                    <Cell onTap={()=>{this.setState({showLogoutTips:true})}}>
                        <CellHeader>
                            <Ico value="logout"/>
                        </CellHeader>
                        <CellBody>
                            退出登录
                        </CellBody>
                        <CellFooter>
                        </CellFooter>
                    </Cell>
                </Cells>
                <ActionSheet
                    menus={this.state.logoutTipsMenus}
                    actions={this.state.logoutTipsActions}
                    show={this.state.showLogoutTips}
                    onRequestClose={()=>{this.setState({showLogoutTips:false})}} />
                <ActionSheet
                    menus={this.state.UnbndTipsMenus}
                    actions={this.state.UnbndTipsActions}
                    show={this.state.showUnbndTips}
                    onRequestClose={()=>{this.setState({showUnbndTips:false})}} />
            </Page>
        );
    }
});
