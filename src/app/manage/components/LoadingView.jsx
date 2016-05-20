'use strict';
var React = require('react');
import {Spin} from 'antd';

export default React.createClass({
    render () {
        return (
            <div style={{textAlign:"center",paddingTop:200,}}>
                <Spin />
            </div>
        );
    }
});
