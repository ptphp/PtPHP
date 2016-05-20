import React from 'react';
import style from './style';
import classNames from 'classnames';

import "./swiper.min.css";
import "./style.less";
require("swiper")

// http://www.idangero.us/swiper/api
const SwiperBox = React.createClass( {
    componentDidMount() {
        this.delSwiper();
        if(this.isMounted()){
            setTimeout(()=>{
                let {initialSlide,options} = this.props;
                this.swiper = Swiper(this.refs.swiper_container,options);
                if (initialSlide) {
                    this.swiper.on("imagesReady", function() {
                        this.swiper.slideTo(initialSlide);
                    }.bind(this));
                }
            },200)
        }
    },
	componentWillUnmount () {
        this.delSwiper();
	},
    delSwiper(){
        if(this.swiper){
            this.swiper.destroy();
            delete this.swiper;
        }
    },
	shouldComponentUpdate (nextProps) {
		return (typeof this.props.shouldUpdate !== "undefined") && this.props.shouldUpdate(nextProps);
	},
    render() {
        let {slides} = this.props;
		return (
            <div ref="swiper_container" className="swiper-container">
                <div className="swiper-wrapper">
                    {
                        slides.map((slide,i)=>{
                            console.log(i)
                            let style = {
                                height:this.props.height,
                                width:this.props.width,
                            };
                            if(slide.height) style = slide.height;
                            if(slide.width) style = slide.width;
                            return (
                                <div key={i} className="swiper-slide">
                                    {
                                        slide.url != undefined ?
                                            <a href={slide.url}>
                                                <img src={slide.pic} style={style}/>
                                            </a>:
                                            <img src={slide.pic} style={style}/>
                                    }
                                </div>
                            )
                        })
                    }
                </div>
                <div className="swiper-pagination"></div>
            </div>
        )
    }
});

SwiperBox.propTypes = {
	initialSlide: React.PropTypes.number
};

SwiperBox.defaultProps = {
	initialSlide: 1,
    slides:[],
    height:120,
    width:"100%",
    options:{
        simulateTouch:true,
        pagination: '.swiper-pagination',
        paginationClickable: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: true,
        loop:true
    }
};

export default SwiperBox;