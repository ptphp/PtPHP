/**
 * Created by jf on 15/12/10.
 */

//"use strict";

import React from 'react';
var Sentry = require('react-sentry');

import {
    Article
} from '../../../../index';

import Page from '../../components/page/index';

import './index.less';
import iconSrc from './../../components/tabbar/img/home.png';

export default React.createClass( {
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
    },
    render () {
        return (
            <Page title="关于我们" goBack={()=>{history.go(-1)}} className="me-view">
                <Article>
                    <h1>大标题</h1>
                    <section>
                        <h2 className="title">章标题</h2>
                        <section>
                            <h3>1.1 节标题</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                consequat. Duis aute</p>
                        </section>
                        <section>
                            <h3>1.2 节标题</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </section>
                    </section>
                </Article>
            </Page>
        );
    }
});
