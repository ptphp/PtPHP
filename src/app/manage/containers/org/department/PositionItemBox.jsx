'use strict';
import React from 'react';
import { Tag,Icon, Button, Tooltip } from 'antd';
import { Menu, Dropdown } from 'antd';

const PositionItem = React.createClass({
    action(row,e){
        console.log(row, e.key)
        if(e.key == 'edit'){
            this.props.parent.props.parent.setState({
                selectedPosition:row,
                showPositionView:true
            });
        }
        if(e.key == 'delete'){
            this.props.parent.props.parent.action_remove_position(row.pot_id);
        }
    },
    render(){
        const menu = (
            <Menu onClick={this.action.bind(this,this.props.row)}>
                <Menu.Item key="edit"><Icon type="edit"/> 修改</Menu.Item>
                <Menu.Item key="delete"><Icon type="delete"/> 删除</Menu.Item>
            </Menu>
        );
        return (
            <Dropdown overlay={menu}>
                <a className="position-item ant-dropdown-link" href="javascript:void(0)">
                    {this.props.children} <Icon type="down" />
                </a>
            </Dropdown>
        )
    }
});

export default React.createClass({
    render(){
        let {row} = this.props;
        //console.log("positions",row.positions)

        let positions = [];

        if(row.positions && row.positions.length > 0){
            row.positions.map(position=>{
                positions.push(<PositionItem parent={this} row={position} key={position.pot_id} closable color="yellow">{position.pot_name}</PositionItem>);
            });
        }else{
            positions.push(<Tag key="-1">未添加</Tag>);
        }
        return (
            <span>
                {
                    this.props.row.pid == 0 ?
                        <span style={{fontSize:16,paddingLeft:16}}>职位</span>:
                        <span>{positions}</span>
                }
            </span>
        )
    }
});

