/**
 * Created by jf on 15/12/10.
 */

//"use strict";

import React from 'react';

import {
    Cell,
    CellHeader,
    CellBody,
    CellFooter
} from '../../../../index';

import './index.less';

export default React.createClass( {
    getInitialState(){
        return {

        }
    },
    componentDidMount(){

    },
    getAvatar(contact){
        if(contact && contact.avatar){
            return (
                <img src={contact.avatar} alt="" style={{display: `block`, width: `20px`, marginRight: `5px`}}/>
            )
        }else{
            return (
                <span>no pic</span>
            )
        }
    },
    render() {
        let {contact} = this.props;
        let avatar  = this.getAvatar(contact);
        return (
            <Cell href="#">
                <CellHeader>
                    {avatar}
                </CellHeader>
                <CellBody>
                    {contact.name}
                </CellBody>
                <CellFooter>
                </CellFooter>
            </Cell>
        );
    }
});

