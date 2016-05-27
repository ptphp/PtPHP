/**
 * Created by jf on 15/12/10.
 */

"use strict";

import React from 'react';

import FaBeer1 from 'react-icons/lib/fa/user';
import MdIconPack from 'react-icons/lib/fa/wechat'

import {Icon,Ico} from '../../../../index';
import Page from '../../component/page';
import './icons.less';

export default class IconDemo extends React.Component {
    render() {
        return (
            <Page className="icons" title="Icons" spacing>
                <FaBeer1 style={{fontSize:32,color:"blue"}}/>
                <div className="icon_sp_area">
                    <Ico value="arrow_left"/>
                    <Ico value="arrow_right"/>
                    <Ico value="arrow_up"/>
                    <Ico value="user"/>
                    <Ico value="home"/>
                    <Ico value="message"/>
                    <Ico value="setting"/>
                    <Ico value="qdao"/>
                    <Ico value="menu"/>
                    <Ico value="star"/>
                    <Ico value="tel"/>
                    <Ico value="id"/>
                    <Ico value="start"/>
                    <Ico value="close"/>
                    <Ico value="logout"/>
                    <Ico value="desktop"/>
                    <Ico value="about"/>
                    <Ico value="aboutus"/>
                    <Ico value="add"/>
                </div>
                <Icon size="large" value="success" />
                <Icon size="large" value="info" />
                <Icon size="large" value="warn" />
                <Icon size="large" value="waiting" />
                <Icon size="large" value="safe_success" />
                <Icon size="large" value="safe_warn" />

                <div className="icon_sp_area">
                    <Icon value="success" />
                    <Icon value="success_circle" />
                    <Icon value="success_no_circle" />
                    <Icon value="info" />
                    <Icon value="waiting" />
                    <Icon value="waiting_circle" />
                    <Icon value="circle" />
                    <Icon value="warn" />
                    <Icon value="download" />
                    <Icon value="info_circle" />
                    <Icon value="cancel" />
                    <Icon value="clear" />
                </div>
            </Page>
        );
    }
};