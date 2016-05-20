'use strict';
import React from 'react';
import { Icon, Button, Tooltip } from 'antd';
import { Menu, Dropdown } from 'antd';

export default React.createClass({
    onShowAddModeal(row){
        this.props.parent.action_add();
    },
    render(){
        let is_parent = this.props.parent.get_is_parent(this.props.row.key)
        let menu = (
            <Menu onClick={this.props.option_action}>
                <Menu.Item key="edit" className="dep-row-opt-item">
                    <Icon type="edit" /> 修改
                </Menu.Item>
                <Menu.Item key="add_sub" className="dep-row-opt-item">
                    <Icon type="plus" /> 新加子部门
                </Menu.Item>
                <Menu.Item key="add_position" className="dep-row-opt-item">
                    <Icon type="appstore" /> 新加职位
                </Menu.Item>
                {
                    !is_parent ?
                        <Menu.Item key="remove" className="dep-row-opt-item">
                            <Icon type="delete" /> 删除
                        </Menu.Item>:
                        <Menu.Item />
                }

            </Menu>
        );
        return (
            <span>
                {
                    this.props.row.pid == 0 ?
                        <span className="department-row-item">
                            {this.props.row.dep_name} <Button className="add_btn" onClick={this.onShowAddModeal.bind(this,this.props.row)} size="small" type="primary">新加子部门</Button>
                        </span>:
                        <Dropdown overlay={menu}>
                            <a className="department-row-item ant-dropdown-link" href="javascript:void(0)">
                                {this.props.row.dep_name} <Icon type="down" />
                            </a>
                        </Dropdown>
                }
            </span>
        )
    }
});

