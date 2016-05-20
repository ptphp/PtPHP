/**
 * Created by jf on 15/12/10.
 */

//"use strict";

import React from 'react';
var Sentry = require('react-sentry');

import {
    Panel,
    PanelBody,
    Toast
} from '../../../../../index';

import Page from '../../../components/page/index';

import './index.less';

export default React.createClass( {
    mixins: [Sentry],
    contextTypes: {
        dataStore: React.PropTypes.object.isRequired,
        router: React.PropTypes.object.isRequired,
    },
    getInitialState(){
        return {
            missions:[]
        }
    },
    componentDidMount(){
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
    goBack(){
        location.href = "/";
    },
    onTabClick(){
        console.log(1)
    },
    render() {
        let result;
        if(!this.state.loading){
            result = [];
            let {missions} = this.state;
            missions.map(mission=>{
                result.push(<MissionItem key={mission.id} mission={mission} />);
            })
        }else{
            return <Toast icon="loading" show={true}>加载中...</Toast>
        }
        return (
            <Page tabTitle="任务">
                <Panel access>
                    <PanelBody className="mission-list">
                        {result}
                    </PanelBody>
                </Panel>
            </Page>
        );
    }
});