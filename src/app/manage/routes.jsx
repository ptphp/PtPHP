'use strict';
import React from 'react';
import {Router, Route, Redirect} from 'react-router';

import App from './containers/App';

import { Auth,AuthLogin } from './containers/auth/index';
import { MissionView,MissionListView,MissionEditView,MissionUsersView } from './containers/mission/index';
import { DesktopView,WorkView } from './containers/work/index';

import {
    CmsBannerListView,
    CmsInfoListView,
    CmsView
} from './containers/cms/index';

import {
    CrmRevisitListView,
    CrmOrderListView,
    CrmAgentListView,
    CrmStoreListView,
    CrmClientListView,
    CrmProductListView,
    CrmView
} from './containers/crm/index';

import {
    DictAreaListView,
    DictView
} from './containers/dict/index';

import {
    ExpressOrderListView,
    ExpressShipListView,
    ExpressDispatchListView,
    ExpressQueryListView,
    ExpressStoreListView,
    ExpressComListView,
    ExpressView
} from './containers/express/index';


import {
    MarketTicketListView,
    MarketView
} from './containers/market/index';

import {
    MemberUserListView,
    MemberBillListView,
    MemberView
} from './containers/member/index';

import {
    OaReportListView,
    OaApproveListView,
    OaCheckinListView,
    OaAssignListView,
    OaView
} from './containers/oa/index';

import {
    OrgStaffListView,
    OrgDepartmentListView,
    OrgRoleListView,
    OrgView
} from './containers/org/index';

import {
    ReportView,
    ReportOaListView,
    ReportCrmListView,
} from './containers/report/index';

import {
    SolardianOrderListView,
    SolardianView,
    SolardianApiListView,
    SolardianPkgListView,
} from './containers/solardian/index';

import {
    SystemSettingListView,
    SystemLogListView,
    SystemView
} from './containers/system/index';


window.Utils = require("../../utils");
const NotFound = React.createClass({render () {return <h1>404 Not Found</h1>;}});
const requireLogin = (nextState, replace, cb) => {cb();};
const AppRoutes = (
    <Router ignoreScrollBehavior>
        <Redirect from="/" to="/mission" />
        <Route path='/' breadcrumbName="首页" component={App}>
            <Redirect from="/auth" to="/auth/login" />
            <Route path='auth' component={Auth}>
                <Route path='login' component={AuthLogin} />
            </Route>
            <Route onEnter={requireLogin}>
                <Redirect from="/mission" to="/mission/list" />
                <Route path="mission" breadcrumbName="任务管理" component={MissionView}>
                    <Route path="list" breadcrumbName="任务列表" component={MissionListView}/>
                    <Route path="edit" breadcrumbName="任务新加" component={MissionEditView}/>
                    <Route path="edit/:id" breadcrumbName="任务修改" component={MissionEditView}/>
                    <Route path="users/:id" breadcrumbName="参与用户" component={MissionUsersView}/>
                </Route>

                <Route path="desktop" breadcrumbName="工作台" component={DesktopView}>
                    <Route path="dashboard" breadcrumbName="个人中心" component={WorkView}/>
                </Route>
                <Route path="solardian" breadcrumbName="绿电中心" component={SolardianView}>
                    <Route path="order" breadcrumbName="缴费订单" component={SolardianOrderListView}/>
                    <Route path="package" breadcrumbName="绿电套餐" component={SolardianPkgListView}/>
                    <Route path="api" breadcrumbName="缴费接口" component={SolardianApiListView}/>
                </Route>
                <Route path="dict" breadcrumbName="字典" component={DictView}>
                    <Route path="area" breadcrumbName="地区" component={DictAreaListView}/>
                </Route>
                <Route path="express" breadcrumbName="快递仓管" component={ExpressView}>
                    <Route path="order" breadcrumbName="寄件订单" component={ExpressOrderListView}/>
                    <Route path="ship" breadcrumbName="代收件" component={ExpressShipListView}/>
                    <Route path="dispatch" breadcrumbName="代派件" component={ExpressDispatchListView}/>
                    <Route path="query" breadcrumbName="查件记录" component={ExpressQueryListView}/>
                    <Route path="store" breadcrumbName="店铺" component={ExpressStoreListView}/>
                    <Route path="com" breadcrumbName="快递公司" component={ExpressComListView}/>
                </Route>
                <Route path="org" breadcrumbName="组织结构" component={OrgView}>
                    <Route path="staff" breadcrumbName="员工" component={OrgStaffListView}/>
                    <Route path="department" breadcrumbName="部门" component={OrgDepartmentListView}/>
                    <Route path="role" breadcrumbName="角色" component={OrgRoleListView}/>
                </Route>
                <Route path="report" breadcrumbName="报表" component={ReportView}>
                    <Route path="crm" breadcrumbName="CRM" component={DictAreaListView}/>
                    <Route path="oa" breadcrumbName="OA" component={DictAreaListView}/>
                </Route>
                <Route path="crm" breadcrumbName="CRM" component={CrmView}>
                    <Route path="client" breadcrumbName="客户" component={CrmClientListView}/>
                    <Route path="agent" breadcrumbName="代理" component={CrmAgentListView}/>
                    <Route path="store" breadcrumbName="店铺" component={CrmStoreListView}/>
                    <Route path="order" breadcrumbName="订单" component={CrmOrderListView}/>
                    <Route path="revisit" breadcrumbName="回访" component={CrmRevisitListView}/>
                    <Route path="product" breadcrumbName="产品" component={CrmProductListView}/>
                </Route>
                <Route path="cms" breadcrumbName="内容管理" component={CmsView}>
                    <Route path="info" breadcrumbName="资料库" component={CmsInfoListView}/>
                    <Route path="banner" breadcrumbName="Banner" component={CmsBannerListView}/>
                </Route>
                <Route path="oa" breadcrumbName="OA" component={OaView}>
                    <Route path="report" breadcrumbName="报表" component={OaReportListView}/>
                    <Route path="approve" breadcrumbName="审批" component={OaApproveListView}/>
                    <Route path="checkin" breadcrumbName="签到" component={OaCheckinListView}/>
                    <Route path="assign" breadcrumbName="指令" component={OaAssignListView}/>
                </Route>
                <Route path="market" breadcrumbName="营销" component={MarketView}>
                    <Route path="ticket" breadcrumbName="优惠券" component={MarketTicketListView}/>
                </Route>
                <Route path="member" breadcrumbName="用户" component={MemberView}>
                    <Route path="user" breadcrumbName="用户管理" component={MemberUserListView}/>
                    <Route path="bill" breadcrumbName="交易流水" component={MemberBillListView}/>
                </Route>
                <Route path="system" breadcrumbName="系统" component={SystemView}>
                    <Route path="setting" breadcrumbName="系统设置" component={SystemSettingListView}/>
                    <Route path="log" breadcrumbName="操作日志" component={SystemSettingListView}/>
                </Route>
            </Route>
        </Route>
        <Route path="*" component={NotFound} status={404} />
    </Router>
);

module.exports = AppRoutes;
