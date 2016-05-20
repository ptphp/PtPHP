'use strict';

import React from 'react';
import { Form,Row,Col } from 'antd';
const FormItem = Form.Item;
import EditorView from '../../components/tools/EditorView.jsx';

export default Form.create()(React.createClass({
    contextTypes: {
        router: React.PropTypes.object.isRequired
    },
    getInitialState(){
        return {}
    },
    componentDidMount(){},
    getFieldValue(key){
        return this.props.parent.state.row && this.props.parent.state.row[key] !== null ? this.props.parent.state.row[key] : null;
    },

    getTipsContent(){
        //console.log(this.props.parent.state)
        let content;
        //console.log(content)
        if(window['editor_tips'] && window['editor_tips'].getValue().length > 0){
            content = window['editor_tips'].getValue();
        }else{
            content = this.getFieldValue("tips");
        }
        return content ? content : "";
    },
    render(){
        return (
            <div>
                <Form horizontal style={{marginTop:30}}>
                    <Row>
                        <Col span="24">
                            <FormItem
                                label="任务说明："
                                labelCol={{ span:3 }}
                                wrapperCol={{ span: 21 }}>
                                <EditorView parent={this} editName="editor_tips" getContent={this.getTipsContent} />
                            </FormItem>
                        </Col>
                    </Row>
                </Form>
            </div>
        );
    }
}));
