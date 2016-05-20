import React from 'react';
import ReactDOM from 'react-dom';
import classNames from 'classnames';
import './page.less';

const Page = React.createClass({
    getInitialState(){
        return {

        }
    },
    componentDidMount(){
        //console.log("componentDidMount");
    },
    componentWillUnmount(){
        //console.log("componentWillUnmount");
    },
    render() {
        //console.log(this.state.infinite);
        const {title, subTitle, spacing, className, children} = this.props;
        return (
            <section ref="container" className={`page ${className}`}>
                <div className="hd">
                    <h1 className="title">{title}</h1>
                    <p className="sub_title">{subTitle}</p>
                </div>
                <div className={`bd ${spacing ? 'spacing' : ''}`}>
                    {children}
                </div>

                <div ref="loading" className="infinite-bottom">
                    <div className="preloader" ></div>
                    <div className="loading-text">正在加载...</div>
                </div>
            </section>
        );
    }
});

Page.propTypes = {
    className: React.PropTypes.string,
    title: React.PropTypes.string,
    desc: React.PropTypes.string,
    infinite: React.PropTypes.bool,
    ptrTriggerDistance: React.PropTypes.number,
    onRefresh: React.PropTypes.func,
    onRefreshDone: React.PropTypes.func,
    onPullRefreshStart: React.PropTypes.func,
    onPullRefreshMove: React.PropTypes.func,
    onPullRefreshEnd: React.PropTypes.func,
    transition: React.PropTypes.string,
    transitionTime: React.PropTypes.string,
};
Page.defaultProps = {
    className: '',
    infinite:false,
    ptrTriggerDistance: 44,
};
export default Page;
