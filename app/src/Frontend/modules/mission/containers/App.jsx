'use strict';
var React = require('react');
import ReactCSSTransitionGroup from 'react-addons-css-transition-group';
var Sentry = require('react-sentry');
var DataStore = require('../stores/DataStore');
var dataStore = new DataStore();
window.dataStore = dataStore;
if(DEBUG) {
    require('../stores/DataStoreTest');
}

import '../../../libs/weui/weui.min.css';
import '../../../style/weui.less';
require('./main.css');

export default React.createClass({
    mixins: [Sentry],
    propTypes: {
        location: React.PropTypes.object
    },
    childContextTypes: {
        dataStore: React.PropTypes.object
    },
    getChildContext () {
        return {
            dataStore: dataStore
        };
    },
    getInitialState () {
        return {
        };
    },
    componentDidMount () {

    },
    render () {
        const key = this.props.location.pathname;
        let title = "goBack";
        let goBack =true;
        return (
            <div>

                <ReactCSSTransitionGroup
                    component="div"
                    transitionName="page"
                    transitionEnterTimeout={500}
                    transitionLeaveTimeout={500}
                    style={{height: '100%'}}
                >
                    {React.cloneElement(this.props.children || <div />, {key: key})}
                </ReactCSSTransitionGroup>
            </div>
            );
    },
});
