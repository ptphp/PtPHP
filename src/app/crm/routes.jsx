'use strict';
import React from 'react';
import {Router, Route, Redirect,IndexRoute} from 'react-router';
import App from './containers/App';

import {
    MissionHome,
    MissionDetail,
    MissionVerify,
    MissionExample,
    MeMission,
    MeMissionDetail
} from './containers/mission/index';

import {
    HomeIndex
} from './containers/home/index';

import {
    MeIndex,
    MeSetting,
    MeAboutUs
} from './containers/me/index';

import {
    Contacts,
    Departments,
    ContactDetail
} from './containers/contact/index';

import {
    ClientHome,
} from './containers/client/index';

import {
    PackageIndex
} from './containers/package/index';

window.Utils = require("../../utils");

const NotFound = React.createClass({render () {return <h1>404 Not Found</h1>;}});
//<IndexRoute component={MissionHome}/>
const AppRoutes = (
    <Router ignoreScrollBehavior>
        <Redirect from="/" to="/client" />
        <Route path='/' breadcrumbName="首页" component={App}>
            <Route path="home" component={HomeIndex}/>
            <Route path="me" component={MeIndex}/>
            <Route path="me/setting" component={MeSetting}/>
            <Route path="me/aboutus" component={MeAboutUs}/>
            <Route path="me/mission" component={MeMission}/>
            <Route path="me/mission/detail/:id" component={MeMissionDetail}/>
            <Route path="package" component={PackageIndex}/>
            <Redirect from="/mission" to="/mission/home" />
            <Route path='mission' breadcrumbName="任务">
                <Route path="home" component={MissionHome}/>
                <Route path="detail/:id" component={MissionDetail}/>
                <Route path="verify/:id/:key" component={MissionVerify}/>
                <Route path="example/:id/:mid" component={MissionExample}/>
            </Route>

            <Redirect from="/contact" to="/contact/contacts" />
            <Route path='contact' breadcrumbName="通讯录">
                <Route path="contacts" component={Contacts}/>
            </Route>

            <Redirect from="/client" to="/client/home" />
            <Route path='client' breadcrumbName="客户管理">
                <Route path="home" component={ClientHome}/>
            </Route>

        </Route>
        <Route path="*" component={NotFound} status={404} />
    </Router>
);

module.exports = AppRoutes;
