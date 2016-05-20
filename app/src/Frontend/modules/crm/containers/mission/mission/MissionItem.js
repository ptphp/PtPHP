/**
 * Created by jf on 15/12/10.
 */

"use strict";

import React from 'react';
var Tappable = require('react-tappable');

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
} from '../../../../../index';

export default React.createClass( {
    contextTypes: {
        router: React.PropTypes.object.isRequired
    },
    getInitialState(){
        return {}
    },
    componentDidMount(){

    },
    goDetail(id){
        this.context.router.push("/mission/detail/" + id);
    },
    render() {
        let {mission} = this.props;
        return (
            <MediaBox type="appmsg" key={this.props.key} >
                <Tappable onTap={this.goDetail.bind(this,mission.id)} stopPropagation>
                    <MediaBoxHeader><img className="weui_media_appmsg_thumb" src={mission.thumb} /></MediaBoxHeader>
                    <MediaBoxBody>
                        <MediaBoxTitle>  {mission.title}</MediaBoxTitle>
                        <MediaBoxDescription>
                            {mission.desc}
                        </MediaBoxDescription>
                    </MediaBoxBody>
                    <span className="jiangli">
                        {mission.award}

                    </span>
                    {
                        mission.is_rec ?
                            <span className="award">推荐</span>:null
                    }
                </Tappable>
            </MediaBox>
        );
    }
});


