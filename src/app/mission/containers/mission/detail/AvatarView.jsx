/**
 * Created by jf on 15/12/10.
 */

"use strict";

import React from 'react';

import Page from '../../../components/page/index';

import './avatar.less';

export default React.createClass ({
    render:function(){
        let {avatars} = this.props;
        let show_more = false;
        if(avatars.length > 13){
            show_more = true;
            avatars.pop();
        }
        return(
            <div>
                {
                    avatars.map((avatar,i)=>{

                        let pic = avatar.pic ? avatar.pic : require('../img/nopic.png');
                        return (
                            <img key={i} src={pic} className="yuan_img" />
                        )
                    })
                }
                {
                    show_more ?
                        <img src={require('../img/usermore.png') } className="to" /> : null
                }

            </div>
        )
    }
});
