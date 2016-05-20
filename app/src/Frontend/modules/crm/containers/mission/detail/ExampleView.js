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

} from '../../../../../index';
const {Alert} = Dialog;

var Sentry = require('react-sentry');
import Page from '../../../components/page/index';
import './index.less';

export default React.createClass( {
    mixins: [Sentry],
    contextTypes: {
        dataStore: React.PropTypes.object.isRequired,
        router: React.PropTypes.object.isRequired
    },

    getInitialState(){
        return {
            loading:true,
            example:null,
        }
    },
    getDetail(){
        this.setState({loading:true});
        this.context.dataStore.mission_example(this.props.params.id,(result,error)=>{
            if(error){
                this.setState({loading:false});
            }else{
                if(this.isMounted()){
                    let {example} = result;

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
        let {example} = this.state;
        //if(!example) return <div></div>
        return (
            <Page className="mission-detail"  goBack={()=>{
            this.context.router.push("/mission/detail/"+this.props.params.mid);
            }}  title="任务攻略">
                <Article className="Article">
                    <PanelBody>
                        <section dangerouslySetInnerHTML={{__html: example}} />
                    </PanelBody>
                </Article>
            </Page>
        );
    }
});