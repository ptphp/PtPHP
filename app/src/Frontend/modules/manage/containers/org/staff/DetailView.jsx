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
                <Row className="detail-row">
                    <Col className="detail-label" span={span_label}>
                        手机:
                    </Col>
                    <Col className="detail-val" span={span_val}>
                        {row.mobile}
                    </Col>
                </Row>
                <Row className="detail-row">
                    <Col className="detail-label" span={span_label}>
                        部门:
                    </Col>
                    <Col className="detail-val" span={span_val}>
                        {row.dep_name}
                    </Col>
                </Row>
                <Row className="detail-row">
                    <Col className="detail-label" span={span_label}>
                        职位:
                    </Col>
                    <Col className="detail-val" span={span_val}>
                        {row.pot_name}
                    </Col>
                </Row>
                <Row className="detail-row">
                    <Col className="detail-label" span={span_label}>
                        角色:
                    </Col>
                    <Col className="detail-val" span={span_val}>
                        {row.role_name}
                    </Col>
                </Row>
                <Row className="detail-row">
                    <Col className="detail-label" span={span_label}>
                        添加时间:
                    </Col>
                    <Col className="detail-val" span={span_val}>
                        {row.add_time}
                    </Col>
                </Row>

            </div>
		);
	}
});
