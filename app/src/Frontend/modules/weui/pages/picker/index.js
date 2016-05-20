/**
 * Created by jf on 15/12/10.
 */

"use strict";

import React from 'react';
import Page from '../../component/page';

export default React.createClass({

    render() {
        let style = {
            transform: "translate3d(0px, 92px, 0px)",
            transitionDuration: "0ms"
        }
        return (
            <Page className="picker" title="Picker" spacing>
                <div className="weui-picker-container  weui-picker-container-visible">
                    <div className="weui-picker-modal picker-columns  weui-picker-modal-visible">
                        <div className="toolbar">
                            <div className="toolbar-inner"><a href="javascript:;" className="picker-button close-picker">完成</a>
                                <h1 className="title">怎么称呼您？</h1></div>
                        </div>
                        <div className="picker-modal-inner picker-items">
                            <div className="picker-items-col picker-items-col-center ">
                                <div className="picker-items-col-wrapper"
                                     style={style}>
                                    <div className="picker-item picker-selected" data-picker-value="Mr">Mr</div>
                                    <div className="picker-item" data-picker-value="Ms">Ms</div>
                                </div>
                            </div>
                            <div className="picker-items-col picker-items-col-center ">
                                <div className="picker-items-col-wrapper"
                                     style={style}>
                                    <div className="picker-item picker-selected" data-picker-value="Amy">Amy</div>
                                    <div className="picker-item" data-picker-value="Bob">Bob</div>
                                    <div className="picker-item" data-picker-value="Cat">Cat</div>
                                    <div className="picker-item" data-picker-value="Dog">Dog</div>
                                    <div className="picker-item" data-picker-value="Ella">Ella</div>
                                    <div className="picker-item" data-picker-value="Fox">Fox</div>
                                    <div className="picker-item" data-picker-value="Google">Google</div>
                                </div>
                            </div>
                            <div className="picker-center-highlight"></div>
                        </div>
                    </div>
                </div>
            </Page>
        );
    }
});