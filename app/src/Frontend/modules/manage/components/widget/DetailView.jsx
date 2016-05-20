'use strict';
import React from 'react';
import { Row,Col,Tooltip,message,Table,Popconfirm ,Icon , Button} from 'antd';

export default React.createClass({
    contextTypes: {
        dataStore: React.PropTypes.object.isRequired
    },
    getInitialState() {
        return {};
    },
    componentDidMount(){

    },
    render() {
        let row = this.props.parent.state.selectedRow;
        let span_label = "3";
        let span_val = "21";
        return (
            <div className="detail-main">
                <Row className="detail-row">
                    <Col className="detail-label" span={span_label}>
                        姓名:
                    </Col>
                    <Col className="detail-val" span={span_val}>
                        {row.stf_name}
                    </Col>
                </Row>
            </div>
        );
    }
});
