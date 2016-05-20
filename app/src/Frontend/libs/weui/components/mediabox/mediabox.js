/**
 * Created by n7best
 */



import React from 'react';
import classNames from 'classnames';
var Tappable = require('react-tappable');
export default class MediaBox extends React.Component {
    static propTypes = {
        type: React.PropTypes.string
    };

    static defaultProps = {
        type: 'text'
    };

    render() {
        const {children, type, className, ...others} = this.props;
        const Component = this.props.href ? 'a' : 'div';
        const cls = classNames({
            weui_media_box: true,
            weui_media_appmsg: type === 'appmsg',
            weui_media_text: type === 'text',
            weui_media_small_appmsg: type === 'small_appmsg',
        }, className);

        if(typeof onTap == 'function'){
            return (
                <Tappable component={Component} className={cls} {...others} onTap={onTap} stopPropagation>{children}</Tappable>
            );
        }else {
            return (
                <Component className={cls} {...others}>{children}</Component>
            );
        }
    }
};