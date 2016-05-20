'use strict';

import React from 'react';
import { Form,Row,Col } from 'antd';
const FormItem = Form.Item;
import UploadView from '../../components/tools/UploadView.jsx';

export default Form.create()(React.createClass({
    contextTypes: {
        router: React.PropTypes.object.isRequired
    },
    getInitialState(){
        return {}
    },
    componentDidMount(){},
    getPics() {
        return this.props.parent.state.pics;
    },
    setPics(pics) {
        this.props.parent.setState({pics});
    },
    render(){
        return (
            <div>
                <Form horizontal style={{marginTop:30}}>
                    <Row>
                        <Col span="24">
                            <FormItem
                                label="缩略图："
                                labelCol={{ span: 3 }}
                                wrapperCol={{ span: 16 }}>
                                <div>
                                    <UploadView max="1" getPics={this.getPics} setPics={this.setPics}/>
                                </div>
                            </FormItem>
                        </Col>
                    </Row>
                </Form>
            </div>
        );
    }
}));
