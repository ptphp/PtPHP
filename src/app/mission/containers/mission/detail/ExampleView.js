/**
 * Created by jf on 15/12/10.
 */

"use strict";

import React from 'react';
import Tappable from 'react-tappable';
import {
    Panel,
    Button,
    CellsTitle,
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
    Toast,
    Article,
    CellFooter,
    Dialog

} from '../../../../../../src/index';
const {Alert} = Dialog;

var Sentry = require('react-sentry');
import Page from '../../../components/page/index';
import './index.less';

export default React.createClass( {
    mixins: [Sentry],
    contextTypes: {
        dataStore: React.PropTypes.object.isRequired,
    },

    getInitialState(){
        return {
            loading:true,
            example:null,
        }
    },
    getDetail(){
        this.setState({loading:true});
        console.log(this.props.parent.state.taskKey)
        this.context.dataStore.mission_example(this.props.parent.state.taskKey,(result,error)=>{
            if(error){
                this.setState({loading:false});
            }else{
                if(this.isMounted()){
                    let { example } = result;
                    this.setState({
                        loading:false,
                        example
                    });
                }
            }
        });
    },
    componentDidMount(){
        this.getDetail();
    },
    render() {
        return (
            <div dangerouslySetInnerHTML={{__html: this.state.example}} />
        );
    }
});