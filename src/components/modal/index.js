

"use strict";

import React from 'react';
var Tappable = require('react-tappable');
import Icon from "../../libs/weui/components/icon/index";
import './index.less';


export default React.createClass( {
    componentDidMount(){

    },
    render() {
        let {title,onClose,show,children} = this.props;
        if(typeof onClose != "function") onClose = ()=>{};
        let show_style = show ? {display:'block'} : {display:'none'};
        console.log(Icon)
        return (
            <div className="pt-modal" style={show_style}>
                <header>
                    <div className="title">
                        {title}
                    </div>
                    <Tappable component="a" onTap={onClose}>
                        <Icon value="cancel" />
                    </Tappable>
                </header>
                <section>
                    {children}
                </section>
            </div>
        );
    }
});
