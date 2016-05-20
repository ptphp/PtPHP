
/**
 * Created by jf on 15/12/10.
 */

"use strict";

import React from 'react';
import ReactCSSTransitionGroup from 'react-addons-css-transition-group';

var Tappable = require('react-tappable');
import {
    Tab,
    TabBody,
    TabBar,
    TabBarItem,
    TabBarIcon,
    TabBarLabel,
    Article
} from '../../../../index';
import './index.less';

import IconMe from './img/icon/me.svg';
import IconMeOn from './img/icon/me_on.svg';

import IconClient from './img/icon/work.svg';
import IconClientOn from './img/icon/work_on.svg';

import IconContact from './img/icon/contact.svg';
import IconContactOn from './img/icon/contact_on.svg';

export default React.createClass( {

    contextTypes: {
        dataStore: React.PropTypes.object.isRequired,
        router: React.PropTypes.object.isRequired
    },
    getInitialState(){
        return {
            tab:0
        }
    },
    componentDidMount(){},
    onTabClick(url){
        if(!url) return;
        if(url.indexOf("#") === 0){
            this.context.router.push(url.substring(1));
        }else{
            location.href = url;
        }
    },
    render() {
        let items = [
            {
                title:"通讯录",
                icon:IconContact,
                iconOn:IconContactOn,
                url:"#/contact/contacts"
            },
            {
                title:"客户",
                icon:IconClient,
                iconOn:IconClientOn,
                url:"#/client/home"
            },
            {
                title:"我",
                icon:IconMe,
                iconOn:IconMeOn,
                url:"#/me"
            }
        ];
        var {children,tabTitle,tabKey,onTabClick,className,style} = this.props;
        onTabClick = typeof onTabClick == 'function' ? onTabClick : ()=>{};
        let props = {};
        if(className){
            props.className = className;
        }
        if(className){
            props.style = style;
        }
        return (
            <Tab {...props}>
                <TabBody>
                    <ReactCSSTransitionGroup
                        component="div"
                        transitionName="page"
                        transitionEnterTimeout={500}
                        transitionLeaveTimeout={500}>
                        <div>{children}</div>
                    </ReactCSSTransitionGroup>
                </TabBody>
                <TabBar>
                    {
                        items.map((item,key)=>{
                            if(tabTitle == item.title){
                                Utils.set_site_title(tabTitle);
                            }
                            return (
                                <TabBarItem
                                    key={key}
                                    active={tabTitle == item.title}
                                    onClick={this.onTabClick.bind(this,item.url)}
                                    icon={<img src={tabTitle == item.title ? item.iconOn:item.icon}/>}
                                    label={item.title}
                                />
                            )
                        })
                    }
                </TabBar>
            </Tab>
        );
    }
});