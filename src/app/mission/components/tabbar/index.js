
/**
 * Created by jf on 15/12/10.
 */

"use strict";

import {
    Tab,
    TabBody,
    TabBar,
    TabBarItem,
    TabBarIcon,
    TabBarLabel,
    Article
} from '../../../../../src/index';

import React from 'react';
var Tappable = require('react-tappable');

import '../page/index.less';

import IconButton from './images/icon_nav_button.png';
import IconMsg from './images/icon_nav_msg.png';
import IconArticle from './images/icon_nav_article.png';
import IconCell from './images/icon_nav_cell.png';

export default React.createClass( {
    getInitialState(){
        return {
            tab:0
        }
    },
    componentDidMount(){
        const {title} = this.props;
        Utils.set_site_title(title);
    },
    render() {
        const {title, subTitle, spacing, className, children,goBack} = this.props;
        return (
            <section className={`page ${className}`}>
                <Tab>
                    <TabBody>
                        <div className={`bd ${spacing ? 'spacing' : ''}`}>
                            {children}
                        </div>
                    </TabBody>
                    <TabBar style={{display:"none"}}>
                        <TabBarItem
                            active={this.state.tab == 0}
                            onClick={e=>this.setState({tab:0})}
                            icon={<img src={IconButton}/>}
                            label="赚绿电"
                        />
                        <TabBarItem active={this.state.tab == 1} onClick={e=>this.setState({tab:1})}>
                            <TabBarIcon>
                                <img src={IconMsg}/>
                            </TabBarIcon>
                            <TabBarLabel>通讯录</TabBarLabel>
                        </TabBarItem>
                        <TabBarItem
                            active={this.state.tab == 2}
                            onClick={e=>this.setState({tab:2})}
                            icon={<img src={IconArticle}/>}
                            label="发现"
                        />
                        <TabBarItem
                            active={this.state.tab == 3}
                            onClick={e=>this.setState({tab:3})}
                            icon={<img src={IconCell}/>}
                            label="我"
                        />
                    </TabBar>
                </Tab>
            </section>

        )
    }
});