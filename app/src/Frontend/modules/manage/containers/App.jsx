'use strict';
var React = require('react');
var Sentry = require('react-sentry');

import ManageView from '../components/ManageView.jsx';
import 'antd/lib/index.css';
import 'simditor/styles/simditor.css';
require('./main.less');

var DataStore = require('../stores/DataStore');
var dataStore = new DataStore();
window.dataStore = dataStore;

if(!window.production){
    require('../stores/DataStoreTest');
}

export default React.createClass({
    mixins: [Sentry],
    propTypes: {
        location: React.PropTypes.object
    },
    childContextTypes: {
        store: React.PropTypes.object
    },
    getInitialState () {
        return {
        };
    },
    getChildContext () {
        return {
            store: dataStore
        };
    },
    componentDidMount () {

    },
    render () {
        const key = this.props.location.pathname;
        let result;
        //console.log("App",this.props.location.pathname);
        if(key.toLocaleLowerCase().indexOf("/auth") === 0){
            result = (
                <div>{React.cloneElement(this.props.children || <div />, {key: key})}</div>
            );
        }else{
            result = <ManageView {...this.props} />;
        }
        return result;
    }
});
