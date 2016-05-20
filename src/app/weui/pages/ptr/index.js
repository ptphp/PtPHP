/**
 * Created by jf on 15/12/10.
 */

"use strict";

import React from 'react';
import Page from '../../component/ptr/page';
import {
    Cells,
    Cell,
    CellHeader,
    CellBody,
    CellFooter
} from '../../../../index';

export default React.createClass({
    getInitialState(){
        return {
            status : 'Normal',
            refreshing: 'No',
            list : []
        }
    },
    componentDidMount(){
        let newlist = [];
        for (let i = 0; i <= 15; i++) {
            newlist.push({label:'Item '+i});
        };

        this.setState({list:newlist});

    },

    refresh(el,event){
        this.setState({status:'Refreshing'});
        this.setState({refreshing:'Yes'});
        setTimeout(()=>{
            let newlist = this.state.list;
            newlist.unshift({label:'New Item'});
            this.setState({list: newlist});
            el.ptrDone(event);
            this.setState({refreshing:'no'});
        },2000);
    },

    renderlist(){
        return this.state.list.map((item,i)=>{
            return <Cell key={i}><CellBody>{item.label}</CellBody></Cell>;
        });
    },

    render(){
        return (
            <Page title="Pull To Refresh" className="pageptr"
                  ptr
                  onPullRefreshStart={()=>this.setState({status:'Pull Start'})}
                  onPullRefreshMove={()=>this.setState({status:'Pull Moving'})}
                  onPullRefreshEnd={()=>this.setState({status:'Pull End'})}
                  onRefresh={this.refresh}
                  onRefreshDone={()=>this.setState({status:'Refresh Done'})}
            >
                <Cells split tips="This is the end">
                    <Cell><CellBody>Event Illstruation:</CellBody></Cell>
                    <Cell><CellBody>Pull Status: {this.state.status}</CellBody></Cell>
                    <Cell><CellBody>Refreshing: {this.state.refreshing}</CellBody></Cell>
                    {this.renderlist()}
                </Cells>
            </Page>
        );
    }
});