'use strict';
import React from 'react';
import {Menu, Icon} from 'antd';
const SubMenu = Menu.SubMenu;

const Sider = React.createClass({
    menuSelect(item){
        //console.log("menuSelect", item, key, selectedKeys);
        this.context.router.push(item.key);
    },
    contextTypes: {
        router: React.PropTypes.object.isRequired,
        store: React.PropTypes.object.isRequired
    },
    propTypes: {
        location: React.PropTypes.object
    },
    getInitialState(){
        return {
            defaultSelectedKeys:[],
            defaultOpenKeys:[]
        };
    },
    componentDidMount(){
        //console.log("componentDidMount");
    },
    componentWillReceiveProps(){
        //console.log("componentWillReceiveProps");
    },
    getKeys(){
        const key = this.props.location.pathname;
        const defaultSelectedKeys = [key.split("/").slice(0, 3).join("/")];
        const defaultOpenKeys = [ key.split("/").slice(0, 2).join("/") ];
        return {
            defaultSelectedKeys,
            defaultOpenKeys
        };
    },
	render() {
        let { menus, site_title } = this.context.store.getAppSetting("setting");
        let { defaultSelectedKeys, defaultOpenKeys } = this.getKeys();
        //console.log(defaultSelectedKeys, defaultOpenKeys);
        let menuProps = {
            defaultSelectedKeys,
            defaultOpenKeys
        };
        //console.log("render",menuProps);
		return (
			<aside className="ant-layout-sider">
				<div className="ant-layout-logo">{site_title}</div>
				<Menu ref="menu" mode="inline" theme="dark"
                    {...menuProps}
                      onSelect={this.menuSelect}
                      onClick={this.handleClick}
                      >
                    {
                        menus.map((menu)=>{
                            return (
                                <SubMenu key={menu.key} title={<span><Icon type={menu.icon} />{menu.title}</span>}>
                                {
                                    menu.sub.map((sub_menu)=>{
                                        return (
                                            <Menu.Item key={sub_menu.key}>{sub_menu.title}</Menu.Item>
                                        );
                                    })
                                }
                                </SubMenu>
                            );
                        })
                    }
				</Menu>
			</aside>
		);
	}
});

export default Sider;
