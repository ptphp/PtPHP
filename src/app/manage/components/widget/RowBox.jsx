'use strict';
import React from 'react';
import { Row,Col,Popconfirm,Icon, Button, Tooltip } from 'antd';

const RowBox = React.createClass({
    render(){
        let {action_back,action_save,children} = this.props;
        if(this.props.parent){
            var {loading_action_save} = this.props.parent.state;
            loading_action_save = loading_action_save ? true:false
        }else{
            var loading_action_save = false;
        }
        return (
            <div>
                <Row>
                    <Col span="12">
                        <Row type="flex" justify="start">
                            <Col span="12">
                                <Button.Group>
                                    {
                                        action_save ?
                                            <Popconfirm title="确定返回吗？"
                                                        placement="top"
                                                        onConfirm={action_back}>
                                                <Button type="ghost">
                                                    <Icon type="left" /> 返回
                                                </Button>
                                            </Popconfirm>
                                            :
                                            <Button type="ghost" onClick={action_back}>
                                                <Icon type="left" /> 返回
                                            </Button>
                                    }
                                </Button.Group>
                            </Col>
                        </Row>
                    </Col>
                    <Col span="12">
                        <Row type="flex" justify="end">
                            <Col >
                                {
                                    action_save ?
                                        <Button.Group>
                                            <Button type="primary" onClick={action_save} loading={loading_action_save}>
                                                <Icon type="save" /> 保存
                                            </Button>
                                        </Button.Group>:null

                                }
                            </Col>
                        </Row>
                    </Col>
                </Row>
                <Row style={{marginTop:16}}>
                    <Col span="24">
                        {children}
                    </Col>
                </Row>
                <Row>
                    <Col span="24">
                        <Row type="flex" justify="end">
                            <Col >
                                {
                                    action_save ?
                                        <Button.Group>
                                            <Button type="primary" onClick={action_save} loading={loading_action_save}>
                                                <Icon type="save" /> 保存
                                            </Button>
                                        </Button.Group>:null

                                }
                            </Col>
                        </Row>
                    </Col>
                </Row>
            </div>
        )
    }
});
export default RowBox;
