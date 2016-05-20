'use strict';
var React = require('react');
import {Icon, Dropdown, Menu} from 'antd';

export default React.createClass({
    contextTypes: {
        dataStore: React.PropTypes.object.isRequired
    },
    doLogout(){
        this.context.dataStore.logout();
    },
    render(){
        let staff_info = this.context.dataStore.getAppSetting("staff");
        let menu = (
            <Menu>
                <Menu.Item>
                    <a target="_blank" onClick={this.doLogout}>退出</a>
                </Menu.Item>
            </Menu>
        );
        return (
            <div className='top-user'>
                <Dropdown overlay={menu}>
                    {staff_info && staff_info.avatar ?
                        <img className="avatar" src={staff_info.avatar} alt=""/>
                        :
                        <div className="avatar" style={{fontSize:20}}>
                            <Icon type="user" />
                        </div>
                    }
                </Dropdown>
            </div>
        );
    }
});
