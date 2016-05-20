
/**
 * Created by jf on 15/12/10.
 */

"use strict";

import React from 'react';
var Tappable = require('react-tappable');

import './index.less';


export default React.createClass( {
    componentDidMount(){
    },
    render() {
        const {title, subTitle, spacing, className, children,goBack} = this.props;
        Utils.set_site_title(title);
        let goBackFunc = ()=>{history.go(-1)};
        if(typeof goBack == 'function') goBackFunc = goBack;
        let h_style = Utils.is_weixin_browser() ? {display:"none"}:{};
        return (
            <section className={`page ${className}`}>
                <header style={h_style}>
                    {
                        goBack ?
                            <Tappable component="button" onTap={goBackFunc} className="NavigationBarLeftButton has-arrow">
                                <span className="NavigationBarLeftArrow" >&#xe600;</span>
                            </Tappable>:null
                    }
                    <h1 className="title">{title}</h1>
                    <Tappable component="button" className="NavigationBarRightButton" />
                </header>
                <div className={`bd ${spacing ? 'spacing' : ''}`}>
                    {children}
                </div>
            </section>
        );
    }
});