/**
 * Created by jf on 15/12/10.
 */

//"use strict";

import React from 'react';
var Sentry = require('react-sentry');

import {
    Panel,
    PanelBody,
    Toast
} from '../../../../index';

import TabBar from '../../components/tabbar/index';
import Page from '../../components/page/index';
import './index.less';

import SwiperBox from "../../../../components/swiper/index";

const HomeIndex = React.createClass( {
    mixins: [Sentry],
    contextTypes: {
        store: React.PropTypes.object.isRequired,
        router: React.PropTypes.object.isRequired,
    },
    getInitialState(){
        return {

        }
    },
    componentDidMount(){
    },
    goBack(){
        location.href = "/";
    },
    onTabClick(){
        console.log(1)
    },
    render() {
        let slides = [
            {
                url:"#/mission",
                pic:"http://7xqzw4.com2.z0.glb.qiniucdn.com/1.jpg"
            },
            {
                url:"#/me",
                pic:"http://y0.ifengimg.com/haina/2016_17/8717e73c592786b_w588_h307.jpg"
            }
        ]
        return (
            <TabBar tabTitle="通讯录">

                <SwiperBox slides={slides} />
            </TabBar>
        );
    }
});

export {
    HomeIndex
}
