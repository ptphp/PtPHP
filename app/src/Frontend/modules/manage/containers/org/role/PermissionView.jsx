'use strict';
import React from 'react';
import { Form,Input,Checkbox,Tabs,Row,Col,Tooltip,message,Table,Popconfirm ,Icon , Button} from 'antd';
const TabPane = Tabs.TabPane;
import './index.less';

export default React.createClass({
    contextTypes: {
        store: React.PropTypes.object.isRequired
    },
    getInitialState() {
        return {};
    },
    getPermissionItemTpl(key){
        if(!this.props.parent.state.system_permissions) return [];
        let items = this.props.parent.state.system_permissions;
        let t = key.split("/");
        var _items = items[t[0]][t[1]];
        let res = [];
        res.push({
            title:t[1],
            key:key,
            index:window.__p_index++,
        });
        _items.map((_item)=>{
            res.push({
                title:_item,
                key:key+"/"+_item,
                index:window.__p_index++,
            });
        });
        //console.log(res)
        return res;
    },
    componentDidMount(){

    },
    getCheckboxValue(key){
        let { permissions } = this.props.parent.state;
        //console.log(permissions);
        return permissions[key] ? true : false;
    },
    onChangeCheckbox(key,e){
        let { permissions } = this.props.parent.state;
        //console.log(key, e.target.checked)
        let checked = e.target.checked;
        permissions[key] = checked
        var $t = key.split("/");
        if(checked){
            if($t.length == 3){
                permissions[$t[0]+"/"+$t[1]] = checked;
                permissions[$t[0]] = checked;
            }else{
                permissions[$t[0]] = checked;
            }
        }else{
            //console.log($t);
            if($t.length == 2){
                let items = this.props.parent.state.system_permissions;
                var _items = items[$t[0]][$t[1]];
                _items.map((item)=>{
                    permissions[$t[0]+"/"+$t[1]+"/"+item] = checked;
                });
            }
        }
        //console.log(permissions);
        this.props.parent.setState({ permissions });
    },
    renderItem(key,sub_key,perm){
        return (
            <div key={key+sub_key}>
                <Row className="permission-item-title">
                    <Col offset="0" span="3">{sub_key}</Col>
                </Row>
                <Row className="permission-item-subs">
                    {this.getPermissionItemTpl(key+"/"+sub_key).map((item)=>{
                        return (
                            <label key={key+sub_key+item.key}>
                                <Checkbox checked={this.getCheckboxValue(item.key)}
                                          onChange={this.onChangeCheckbox.bind(this,item.key)} />
                                {item.title}
                            </label>
                        )
                    })}
                </Row>
            </div>
        )
    },
    renderTabPane(key,perm){
        //console.log(key,perm)
        let result = [];
        for(var sub_key in perm){
            result.push(this.renderItem(key,sub_key,perm[sub_key]));
        }
        return (
            <TabPane tab={key} key={key}>
                <Row className="permission-items">
                    <Col span="24">
                        {result}
                    </Col>
                </Row>
            </TabPane>
        )
    },
	render() {
        let {permissions,system_permissions} = this.props.parent.state;
        //console.log("render",permissions,system_permissions)
        let result = [];
        var d_key = null;
        for(var key in system_permissions){
            if(!d_key) d_key = key;
            result.push(this.renderTabPane(key,system_permissions[key]));
        }
        return (
            <Tabs defaultActiveKey={d_key} >
                {result}
            </Tabs>
		);
	}
});

