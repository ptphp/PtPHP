/**
 * Created by jf on 15/12/10.
 */

//"use strict";

import React from 'react';
var Sentry = require('react-sentry');

import {
    SearchBar,
    Cells,
    Cell,
    CellHeader,
    CellBody,
    CellFooter
} from '../../../../index';

import TabBar from '../../components/tabbar/index';
import Item from './ContactsItem.jsx';
import './index.less';


export default React.createClass( {
    mixins: [Sentry],
    contextTypes: {
        store: React.PropTypes.object.isRequired,
        router: React.PropTypes.object.isRequired,
    },
    getInitialState(){
        return {
            rows:[
                {
                    name:"1李四",
                    py:"",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },
                {
                    name:"2李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },
                {
                    name:"3李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },
                {
                    name:"4李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },
                {
                    name:"5李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },
                {
                    name:"2李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },
                {
                    name:"3李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },
                {
                    name:"4李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },,
                {
                    name:"2李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },
                {
                    name:"3李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },
                {
                    name:"4李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },,
                {
                    name:"2李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },
                {
                    name:"3李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },
                {
                    name:"4李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },,
                {
                    name:"2李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },
                {
                    name:"3李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },
                {
                    name:"4李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },,
                {
                    name:"2李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },
                {
                    name:"3李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },
                {
                    name:"4李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },
                {
                    name:"2李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },
                {
                    name:"3李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                },
                {
                    name:"4李四",
                    py:"wanwu",
                    avatar:"https://avatars3.githubusercontent.com/u/1365881?v=3&s=40",
                }
            ],
            query:""
        }
    },
    componentDidMount(){
    },
    handleChange(text){
        this.setState({
            query:text
        });
    },
    render() {
        let {rows,query} = this.state;

        let searchRegex = new RegExp(query);
        let charRegex = new RegExp("^[a-zA-Z]$");
        let filteredRows = rows.filter(row=>{return searchRegex.test(charRegex.test(query) ? row.py : row.name)});
        return (
            <TabBar tabTitle="通讯录" className="contacts">
                <Cells access className="depart_btn">
                    <Cell>
                        <CellBody>部门</CellBody>
                        <CellFooter>全部</CellFooter>
                    </Cell>
                </Cells>
                <SearchBar placeholder="请输入员工姓名或者拼音"
                    onChange={this.handleChange}
                />
                <Cells access className="depart_btn">
                    {
                        filteredRows.map(row=>{
                            return (
                                <Item contact={row} />
                            )
                        })
                    }
                </Cells>
            </TabBar>
        );
    }
});

