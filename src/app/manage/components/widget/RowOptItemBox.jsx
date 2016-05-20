'use strict';
import React from 'react';
import { Icon, Button, Tooltip } from 'antd';
import { Menu, Dropdown } from 'antd';

const RowOptItemBox = React.createClass({
    render(){
        const menu = (
            <Menu onClick={this.props.option_action}>
                {
                    this.props.actions.map(action=>{
                        let title,icon;
                        return (
                            <Menu.Item key={action.title} className="row-opt-item">
                                {
                                    action.icon ?
                                        <Icon type={action.icon} />:null
                                }
                                {action.title}
                            </Menu.Item>
                        )
                    })
                }
            </Menu>
        );
        return (
            <Dropdown overlay={menu}>
                <a className="ant-dropdown-link" href="javascript:void(0)">
                    操作 <Icon type="down" />
                </a>
            </Dropdown>
        )
    }
});

export default RowOptItemBox;
