'use strict';
import React from 'react';
import { Tooltip,message,Table,Popconfirm ,Icon , Button} from 'antd';
const config = require("./config");

export default React.createClass({
    contextTypes: {
        router: React.PropTypes.object.isRequired,
        store: React.PropTypes.object.isRequired
    },
    getInitialState() {
        return {
            loading: false

        };
    },
    componentDidMount(){
    },
	render() {
        return (
            <div>

            </div>
		);
	}
});
