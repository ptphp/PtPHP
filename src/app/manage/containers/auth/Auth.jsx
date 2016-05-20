'use strict';
import React from 'react';

export default React.createClass({
    propTypes: {
        location: React.PropTypes.object
    },
    render () {
        const key = this.props.location.pathname;
        return (
            <div>{React.cloneElement(this.props.children || <div />, {key: key})}</div>
        );
    }
});
