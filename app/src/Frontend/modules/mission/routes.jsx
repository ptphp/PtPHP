'use strict';
import React from 'react';
import {Router, Route, Redirect,IndexRoute} from 'react-router';
import App from './containers/App';

import {
    MissionHome,
    MissionDetail
} from './containers/mission/index';
import {
    AuthLogin,
    AuthFgtPwd,
    AuthReg
} from './containers/auth/index';

window.Utils = require("../../utils");

const NotFound = React.createClass({render () {return <h1>404 Not Found</h1>;}});
//<IndexRoute component={MissionHome}/>
const AppRoutes = (
    <Router ignoreScrollBehavior>
        <Redirect from="/" to="/mission/home" />
        <Redirect from="/rd" to="/mission/home" />
        <Route path='/' breadcrumbName="首页" component={App}>
            <Redirect from="/auth" to="/auth/login" />
            <Route path='auth' breadcrumbName="认证">
                <Route path="login" component={AuthLogin}/>
                <Route path="fgtpwd" component={AuthFgtPwd}/>
                <Route path="reg" component={AuthReg}/>
            </Route>

            <Redirect from="/mission" to="/mission/home" />
            <Route path='mission' breadcrumbName="任务">
                <Route path="home" component={MissionHome}/>
                <Route path="detail/:id" component={MissionDetail}/>
            </Route>
        </Route>
        <Route path="*" component={NotFound} status={404} />
    </Router>
);

module.exports = AppRoutes;
