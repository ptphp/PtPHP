/**
 * Created by n7best.
 */

"use strict";

import React from 'react';
import Page from '../../component/page';

import {
    Cells,
    Cell,
    CellHeader,
    CellBody,
    CellFooter,
} from '../../../../index';

export default class TabDemo extends React.Component {

    render() {
        return (
            <Page className="tab" title="Tab">
                <Cells access>
                    <Cell href="#navbar">
                        <CellBody>
                            navbar
                        </CellBody>
                        <CellFooter/>
                    </Cell>
                    <Cell href="#navbar2">
                        <CellBody>
                            navbar2
                        </CellBody>
                        <CellFooter/>
                    </Cell>
                    <Cell href="#tabbar">
                         <CellBody>
                            tabbar
                        </CellBody>
                        <CellFooter/>
                    </Cell>
                    <Cell href="#tabbar2">
                         <CellBody>
                            tabbar2
                        </CellBody>
                        <CellFooter/>
                    </Cell>
                </Cells>
            </Page>
        );
    }
};
