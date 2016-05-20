/**
 * Created by jf on 15/12/10.
 */

"use strict";

import React from 'react';
import ReactDOM from 'react-dom';
import ReactCSSTransitionGroup from 'react-addons-css-transition-group';
import { Router, Route, IndexRoute,useRouterHistory} from 'react-router';
import WeUI from '../../index';
import '../../libs/weui/weui.min.css';
import '../../style/weui.less';
import Home from './pages/home/index';
import Button from './pages/button/index';
import Cell from './pages/cell/index';
import Toast from './pages/toast/index';
import Dialog from './pages/dialog/index';
import Progress from './pages/progress/index';
import Msg from './pages/msg/index';
import Article from './pages/article/index';
import ActionSheet from './pages/actionsheet/index';
import Icons from './pages/icons/index';
import Panel from './pages/panel/index';
import Tab from './pages/tab/index';
import NavBar from './pages/tab/navbar';
import NavBar2 from './pages/tab/navbar_auto';
import TabBar from './pages/tab/tabbar';
import TabBar2 from './pages/tab/tabbar_auto';
import SearchBar from './pages/searchbar/index';
import SwiperDemo from './pages/swiper/index';
import PtrDemo from './pages/ptr/index';
import Infinite from './pages/infinite/index';

import Picker from './pages/picker/index';


import createHashHistory        from 'history/lib/createHashHistory';

const history = useRouterHistory(createHashHistory)({queryKey: false});

class App extends React.Component {
        render() {
                return (
                    <ReactCSSTransitionGroup
                        component="div"
                        transitionName="page"
                        transitionEnterTimeout={501}
                        transitionLeaveTimeout={500}
                        style={{height: '100%'}}
                    >
                            {React.cloneElement(this.props.children, {
                                    key: this.props.location.pathname
                            })}
                    </ReactCSSTransitionGroup>
                );
        }
}

ReactDOM.render((
    <Router history={history}>
        <Route path="/" component={App}>
            <IndexRoute component={Home}/>
            <Route path="button" component={Button}/>
            <Route path="cell" component={Cell}/>
            <Route path="toast" component={Toast}/>
            <Route path="dialog" component={Dialog}/>
            <Route path="progress" component={Progress}/>
            <Route path="msg" component={Msg}/>
            <Route path="article" component={Article}/>
            <Route path="actionsheet" component={ActionSheet}/>
            <Route path="icons" component={Icons}/>
            <Route path="panel" component={Panel}/>
            <Route path="tab" component={Tab}/>
            <Route path="navbar" component={NavBar}/>
            <Route path="navbar2" component={NavBar2}/>
            <Route path="tabbar" component={TabBar}/>
            <Route path="tabbar2" component={TabBar2}/>
            <Route path="searchbar" component={SearchBar}/>
            <Route path="swiper" component={SwiperDemo}/>
            <Route path="ptr" component={PtrDemo}/>
            <Route path="infinite" component={Infinite}/>
            <Route path="picker" component={Picker}/>
        </Route>
    </Router>
), document.getElementById('root'));