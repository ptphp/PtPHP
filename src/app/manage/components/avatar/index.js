'use strict';

import React from 'react';
import { Row,Col,Icon } from 'antd';
import "./index.less";

export default React.createClass({
    render(){
        let {avatar,name} = this.props;
        return (
            <Row className="avatar-box">
                <div className="avatar-col">
                    {
                        avatar ?
                            <img className="avatar" src={avatar} alt=""/>
                            :
                            <Icon type="user" />
                    }
                </div>
                <div className="username">
                    {name}
                </div>
            </Row>
        )
    }
});