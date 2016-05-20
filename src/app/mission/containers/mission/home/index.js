/**
 * Created by jf on 15/12/10.
 */

//"use strict";

import React from 'react';
import {Toast} from '../../../../../../src/index';
var Sentry = require('react-sentry');

import {
    Panel,
    PanelHeader,
    PanelBody,
    PanelFooter,
    MediaBox,
    MediaBoxHeader,
    MediaBoxBody,
    MediaBoxTitle,
    MediaBoxDescription,
    MediaBoxInfo,
    MediaBoxInfoMeta,
    Cells,
    Cell,
    CellHeader,
    CellBody,
    CellFooter
} from '../../../../../../src/index';

import Page from '../../../components/page/index';
import MissionItem from './MissionItem';
import './index.less';

export default React.createClass( {
    mixins: [Sentry],
    contextTypes: {
        dataStore: React.PropTypes.object.isRequired
    },
    getInitialState(){
        return {
            missions:[],
            loaded:false
        }
    },
    componentDidMount(){
        Utils.set_site_title("赚绿电");
        this.setState({
            loading:true,
        });
        this.context.dataStore.mission_list((result,error)=>{
            //console.log(result,error)
            if(error){
                this.setState({loading:false});
            }else{
                if(this.isMounted()){
                    this.setState({
                        loading:false,
                        missions:result.missions
                    });
                }
            }
        });
    },
    renderNoResult(){
        return (
            <div className="no_result">
                <div className="line_mid"></div>
                <div className="line_bot"></div>
                <div className="row">
                    <div className="left"></div>
                    <div className="info">
                        想赚绿电还要等等喔...
                    </div>
                    <div className="right"></div>
                </div>
            </div>
        )
    },
    render() {
        let result;
        if(!this.state.loading){
            result = [];
            let {missions} = this.state;
            missions.map(mission=>{
                result.push(<MissionItem key={mission.id} mission={mission} />);
            })

            if(result.length == 0){
                result = this.renderNoResult();
            }
        }else{
            return <Toast icon="loading" show={true}>加载中...</Toast>
        }
        return (
            <Page className="task-list" goBack={()=>{
                location.href = "/";
                }} title="赚绿电">
                <Panel access>
                    <PanelBody className="mission-list">
                        {result}
                    </PanelBody>
                </Panel>
            </Page>
        );
    }
});