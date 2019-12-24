@extends('layouts.shop')
@section('content')
<!-- side nav right-->
<div class="side-nav-panel-right">
    <ul id="slide-out-right" class="side-nav side-nav-panel collapsible">
        <li class="profil">
            <img src="/img/profile.jpg" alt="">
            <h2>John Doe</h2>
        </li>
        <li><a href="setting.html"><i class="fa fa-cog"></i>Settings</a></li>
        <li><a href="about-us.html"><i class="fa fa-user"></i>About Us</a></li>
        <li><a href="contact.html"><i class="fa fa-envelope-o"></i>Contact Us</a></li>
        <li><a href="login.html"><i class="fa fa-sign-in"></i>Login</a></li>
        <li><a href="register.html"><i class="fa fa-user-plus"></i>Register</a></li>
    </ul>
</div>
<!-- end side nav right-->



<!-- menu -->
<div class="menus" id="animatedModal2">
    <div class="close-animatedModal2 close-icon">
        <i class="fa fa-close"></i>
    </div>
    <div class="modal-content">
        <div class="container">
            <div class="row">
                <div class="col s4">
                    <a href="index.html" class="button-link">
                        <div class="menu-link">
                            <div class="icon">
                                <i class="fa fa-home"></i>
                            </div>
                            Home
                        </div>
                    </a>
                </div>
                <div class="col s4">
                    <a href="product-list.html" class="button-link">
                        <div class="menu-link">
                            <div class="icon">
                                <i class="fa fa-bars"></i>
                            </div>
                            Product List
                        </div>
                    </a>
                </div>
                <div class="col s4">
                    <a href="shop-single.html" class="button-link">
                        <div class="menu-link">
                            <div class="icon">
                                <i class="fa fa-eye"></i>
                            </div>
                            Single Shop
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col s4">
                    <a href="wishlist.html" class="button-link">
                        <div class="menu-link">
                            <div class="icon">
                                <i class="fa fa-heart"></i>
                            </div>
                            Wishlist
                        </div>
                    </a>
                </div>
                <div class="col s4">
                    <a href="cart.html" class="button-link">
                        <div class="menu-link">
                            <div class="icon">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                            Cart
                        </div>
                    </a>
                </div>
                <div class="col s4">
                    <a href="checkout.html" class="button-link">
                        <div class="menu-link">
                            <div class="icon">
                                <i class="fa fa-credit-card"></i>
                            </div>
                            Checkout
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col s4">
                    <a href="blog.html" class="button-link">
                        <div class="menu-link">
                            <div class="icon">
                                <i class="fa fa-bold"></i>
                            </div>
                            Blog
                        </div>
                    </a>
                </div>
                <div class="col s4">
                    <a href="blog-single.html" class="button-link">
                        <div class="menu-link">
                            <div class="icon">
                                <i class="fa fa-file-text-o"></i>
                            </div>
                            Blog Single
                        </div>
                    </a>
                </div>
                <div class="col s4">
                    <a href="error404.html" class="button-link">
                        <div class="menu-link">
                            <div class="icon">
                                <i class="fa fa-hourglass-half"></i>
                            </div>
                            404
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col s4">
                    <a href="testimonial.html" class="button-link">
                        <div class="menu-link">
                            <div class="icon">
                                <i class="fa fa-support"></i>
                            </div>
                            Testimonial
                        </div>
                    </a>
                </div>
                <div class="col s4">
                    <a href="about-us.html" class="button-link">
                        <div class="menu-link">
                            <div class="icon">
                                <i class="fa fa-user"></i>
                            </div>
                            About Us
                        </div>
                    </a>
                </div>
                <div class="col s4">
                    <a href="contact.html" class="button-link">
                        <div class="menu-link">
                            <div class="icon">
                                <i class="fa fa-envelope-o"></i>
                            </div>
                            Contact
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col s4">
                    <a href="setting.html" class="button-link">
                        <div class="menu-link">
                            <div class="icon">
                                <i class="fa fa-cog"></i>
                            </div>
                            Settings
                        </div>
                    </a>
                </div>
                <div class="col s4">
                    <a href="login.html" class="button-link">
                        <div class="menu-link">
                            <div class="icon">
                                <i class="fa fa-sign-in"></i>
                            </div>
                            Login
                        </div>
                    </a>
                </div>
                <div class="col s4">
                    <a href="register.html" class="button-link">
                        <div class="menu-link">
                            <div class="icon">
                                <i class="fa fa-user-plus"></i>
                            </div>
                            Register
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end menu -->

<!-- cart menu -->
<div class="menus" id="animatedModal">
    <div class="close-animatedModal close-icon">
        <i class="fa fa-close"></i>
    </div>
    <div class="modal-content">
        <div class="cart-menu">
            <div class="container">
                <div class="content">
                    <div class="cart-1">
                        <div class="row">
                            <div class="col s5">
                                <img src="/img/cart-menu1.png" alt="">
                            </div>
                            <div class="col s7">
                                <h5><a href="">Fashion Men's</a></h5>
                            </div>
                        </div>
                        <div class="row quantity">
                            <div class="col s5">
                                <h5>Quantity</h5>
                            </div>
                            <div class="col s7">
                                <input value="1" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s5">
                                <h5>Price</h5>
                            </div>
                            <div class="col s7">
                                <h5>$20</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s5">
                                <h5>Action</h5>
                            </div>
                            <div class="col s7">
                                <div class="action"><i class="fa fa-trash"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="cart-2">
                        <div class="row">
                            <div class="col s5">
                                <img src="/img/cart-menu2.png" alt="">
                            </div>
                            <div class="col s7">
                                <h5><a href="">Fashion Men's</a></h5>
                            </div>
                        </div>
                        <div class="row quantity">
                            <div class="col s5">
                                <h5>Quantity</h5>
                            </div>
                            <div class="col s7">
                                <input value="1" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s5">
                                <h5>Price</h5>
                            </div>
                            <div class="col s7">
                                <h5>$20</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s5">
                                <h5>Action</h5>
                            </div>
                            <div class="col s7">
                                <div class="action"><i class="fa fa-trash"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="total">
                    <div class="row">
                        <div class="col s7">
                            <h5>Fashion Men's</h5>
                        </div>
                        <div class="col s5">
                            <h5>$21.00</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s7">
                            <h5>Fashion Men's</h5>
                        </div>
                        <div class="col s5">
                            <h5>$21.00</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s7">
                            <h6>Total</h6>
                        </div>
                        <div class="col s5">
                            <h6>$41.00</h6>
                        </div>
                    </div>
                </div>
                <button class="btn button-default">Process to Checkout</button>
            </div>
        </div>
    </div>
</div>
<!-- end cart menu -->

<!-- slider -->
<div class="slider">

    <ul class="slides">
        <li>
            <img src="/img/slide1.jpg" alt="">
            <div class="caption slider-content  center-align">
                <h2>WELCOME TO MSTORE</h2>
                <h4>Lorem ipsum dolor sit amet.</h4>
                <a href="" class="btn button-default">SHOP NOW</a>
            </div>
        </li>
        <li>
            <img src="/img/slide2.jpg" alt="">
            <div class="caption slider-content center-align">
                <h2>JACKETS BUSINESS</h2>
                <h4>Lorem ipsum dolor sit amet.</h4>
                <a href="" class="btn button-default">SHOP NOW</a>
            </div>
        </li>
        <li>
            <img src="/img/slide3.jpg" alt="">
            <div class="caption slider-content center-align">
                <h2>FASHION SHOP</h2>
                <h4>Lorem ipsum dolor sit amet.</h4>
                <a href="" class="btn button-default">SHOP NOW</a>
            </div>
        </li>
    </ul>

</div>
<!-- end slider -->

<!-- features -->
<div class="features section">
    <div class="container">
        <div class="row">
            <div class="col s6">
                <div class="content">
                    <div class="icon">
                        <i class="fa fa-car"></i>
                    </div>
                    <h6>免费送货</h6>
                </div>
            </div>
            <div class="col s6">
                <div class="content">
                    <div class="icon">
                        <i class="fa fa-dollar"></i>
                    </div>
                    <h6>退款</h6>
                </div>
            </div>
        </div>
        <div class="row margin-bottom-0">
            <div class="col s6">
                <div class="content">
                    <div class="icon">
                        <i class="fa fa-lock"></i>
                    </div>
                    <h6>安全付款</h6>
                </div>
            </div>
            <div class="col s6">
                <div class="content">
                    <div class="icon">
                        <i class="fa fa-support"></i>
                    </div>
                    <h6>24/7 Support</h6>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end features -->

<!-- quote -->
<div class="section quote">
    <div class="container">
        {{--<h4>FASHION UP TO 50% OFF</h4>--}}
        <h4>最高50%折扣</h4>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid ducimus illo hic iure eveniet</p>
    </div>
</div>
<!-- end quote -->

<!-- product -->
<div class="section product">
    <div class="container">
        <div class="section-head">
            <h4>新  品</h4>
            <div class="divider-top"></div>
            <div class="divider-bottom"></div>
        </div>

        <div class="row margin-bottom">
            @foreach($goodsinfo as $k=>$v)
            <div class="col s6">
                <div class="content">
                    <a href="{{url('detail',$v->goods_id)}}"><img src="/storage/{{$v->img}}"></a>
                    <h6><a href="">Fashion Men's</a></h6>
                    <div class="price">
                        {{--$20 <span>$28</span>--}}
                        {{$v->price}}
                    </div>
                    <button class="btn button-default">点击购买</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- end product -->

<!-- promo -->
<div class="promo section">
    <div class="container">
        <div class="content">
            <h4>PRODUCT BUNDLE</h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit</p>
            <button class="btn button-default">SHOP NOW</button>
        </div>
    </div>
</div>
<!-- end promo -->

<!-- product -->
<div class="section product">
    <div class="container">
        <div class="section-head">
            <h4>热门产品</h4>
            <div class="divider-top"></div>
            <div class="divider-bottom"></div>
        </div>
        <div class="row">
            <div class="col s6">
                <div class="content">
                    <img src="/img/product-new1.png" alt="">
                    <h6><a href="">Fashion Men's</a></h6>
                    <div class="price">
                        $20 <span>$28</span>
                    </div>
                    <button class="btn button-default">ADD TO CART</button>
                </div>
            </div>
            <div class="col s6">
                <div class="content">
                    <img src="/img/product-new2.png" alt="">
                    <h6><a href="">Fashion Men's</a></h6>
                    <div class="price">
                        $20 <span>$28</span>
                    </div>
                    <button class="btn button-default">ADD TO CART</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s6">
                <div class="content">
                    <img src="/img/product-new3.png" alt="">
                    <h6><a href="">Fashion Men's</a></h6>
                    <div class="price">
                        $20 <span>$28</span>
                    </div>
                    <button class="btn button-default">ADD TO CART</button>
                </div>
            </div>
            <div class="col s6">
                <div class="content">
                    <img src="/img/product-new4.png" alt="">
                    <h6><a href="">Fashion Men's</a></h6>
                    <div class="price">
                        $20 <span>$28</span>
                    </div>
                    <button class="btn button-default">ADD TO CART</button>
                </div>
            </div>
        </div>
        <div class="pagination-product">
            <ul>
                <li class="active">1</li>
                <li><a href="">2</a></li>
                <li><a href="">3</a></li>
                <li><a href="">4</a></li>
                <li><a href="">5</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- end product -->

<!-- loader -->
<div id="fakeLoader"></div>
<!-- end loader -->
@endsection
<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script>
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: 'wx4fdcb23b1ce7f2c6', // 必填，公众号的唯一标识
        timestamp: '{{$js_sdk['timestamp']}}', // 必填，生成签名的时间戳
        nonceStr: '{{$js_sdk['nonceStr']}}', // 必填，生成签名的随机串
        signature: '{{$js_sdk['signature']}}',// 必填，签名
        jsApiList: ['updateAppMessageShareData','chooseImage','updateTimelineShareData'] // 必填，需要使用的JS接口列表
    });
    wx.ready(function(){
        //分享朋友圈
        wx.ready(function () {      //需在用户可能点击分享按钮前就先调用
            wx.updateTimelineShareData({
                title: '微商城已上线', // 分享标题
                link: 'http://1905sunhao.comcto.com', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: 'http://1905sunhao.comcto.com/storage/images/微信图片_20191118144643.bmp', // 分享图标
                success: function () {
                    alert('成功')
                }
            })
        });
        wx.onMenuShareAppMessage({
            title: '微商城已上线', // 分享标题
            desc: '点击进入', // 分享描述
            link: 'http://1905sunhao.comcto.com', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: 'http://1905sunhao.comcto.com/storage/images/微信图片_20191118144643.bmp', // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
                alert('分享商城成功')
            }
        });
    });


</script>