'use strict';

var React = require('react'); // React must be in scope when using JSX because JSX is translated into React.createElement(...)
var ReactDOM = require('react-dom');
import createHashHistory        from 'history/lib/createHashHistory';
import {
    Router,
    useRouterHistory
}                               from 'react-router';

var AppRoutes = require('./routes');
const history = useRouterHistory(createHashHistory)({queryKey: false});
function mainApp () {
    ReactDOM.render(
        <Router children={AppRoutes} history={history}/>,
        document.getElementById('root')
    );
}
document.addEventListener('DOMContentLoaded', mainApp);
