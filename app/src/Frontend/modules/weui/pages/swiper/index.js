/**
 * Created by jf on 15/12/10.
 */

"use strict";

import React from 'react';
import Page from '../../component/page';
import SwiperBox from "../../../../components/swiper/index";

export default React.createClass({

    render() {
        let slides = [
            {
                url:"#/",
                pic:"http://7xqzw4.com2.z0.glb.qiniucdn.com/1.jpg"
            },
            {
                url:"#/",
                pic:"http://y0.ifengimg.com/haina/2016_17/8717e73c592786b_w588_h307.jpg"
            }
        ];
        return (
            <Page className="swiper" title="Swiper" spacing>
                <SwiperBox slides={slides}/>
            </Page>
        );
    }
});
