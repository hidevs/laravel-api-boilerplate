<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js">

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }}</title>
    <meta name="description" content="Zmat API Webservice">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Webina Team">

    <!-- ============== Resources style ============== -->
    <link rel="stylesheet" href="{{ asset('welcome/css/style.css') }}" />

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Modernizr runs quickly on page load to detect features -->
    <script src="{{ asset('welcome/js//modernizr.custom.js') }}"></script>
</head>

<body>

<!-- Page preloader -->
<div id="loading">
    <div id="preloader">
        <span></span>
        <span></span>
    </div>
</div>

<!-- Overlay and Star effect -->
<div class="global-overlay">
    <div class="overlay skew-part">

        <div id='stars'></div>
        <div id='stars2'></div>
        <div id='stars3'></div>

    </div>
</div>

<!-- START - Home/Left Part -->
<section id="left-side">

    <!-- Your logo -->
    <img style="min-width: 70%;" src="{{ asset('welcome/img/logo.png') }}" alt="" class="brand-logo" />

    <div class="content">

        <h1 class="text-intro opacity-0">Hey Guys! 👋<br>
            We're Coming Soon... 😎</h1>

        <h2 class="text-intro opacity-0">
            {{ config('app.name') }} API Webservice by <b><a class="text-intro" href="https://hidevs.team/">HiDevs Team 🌐</a></b>
        </h2>

        <nav>
            <ul>
                <li>
                    <a href="https://github.com/hidevs/laravel-api-boilerplate" class="action-btn trigger text-intro opacity-0" style="color: #252525">
                        <i class="fa fa-github"></i>
                        Github Repository
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Social icons -->
    <div class="social-icons">

        <a href="#"><i class="fa fa-facebook"></i></a>
        <a href="#"><i class="fa fa-twitter"></i></a>
        <a href="#"><i class="fa fa-google-plus"></i></a>
        <a href="#"><i class="fa fa-linkedin"></i></a>

    </div>

</section>
<!-- END - Home/Left Part -->

<!-- Cloud animation -->
<div id="cloud-animation">

    <img src="{{ asset('welcome/img/cloud-01.png') }}" alt="" id="cloud1">
    <img src="{{ asset('welcome/img/cloud-02.png') }}" alt="" id="cloud2">
    <img src="{{ asset('welcome/img/cloud-03.png') }}" alt="" id="cloud3">
    <img src="{{ asset('welcome/img/cloud-04.png') }}" alt="" id="cloud4">

</div>

<!-- Root element of PhotoSwipe, the gallery. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

    <!-- Background of PhotoSwipe.
        It's a separate element as animating opacity is faster than rgba(). -->
    <div class="pswp__bg"></div>

    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">

        <!-- Container that holds slides.
            PhotoSwipe keeps only 3 of them in the DOM to save memory.
            Don't modify these 3 pswp__item elements, data is added later on. -->
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <!--  Controls are self-explanatory. Order can be changed. -->

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--share" title="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                <!-- element will get class pswp__preloader--active when preloader is running -->
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                        <div class="pswp__preloader__cut">
                            <div class="pswp__preloader__donut"></div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div>
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

        </div>

    </div>

</div>
<!-- /. Root element of PhotoSwipe. Must have class pswp. -->

<!-- * Libraries jQuery, Easing and Bootstrap - Be careful to not remove them * -->
<script src="{{ asset('welcome/js//jquery.min.js') }}"></script>
<script src="{{ asset('welcome/js//jquery.easings.min.js') }}"></script>
<script src="{{ asset('welcome/js//bootstrap.min.js') }}"></script>

<!-- PhotoSwipe Core JS file -->
<script src="{{ asset('welcome/js//velocity.min.js') }}"></script>

<!-- PhotoSwipe UI JS file -->
<script src="{{ asset('welcome/js//velocity.ui.min.js') }}"></script>

<!-- Slideshow/Image plugin -->
<script src="{{ asset('welcome/js//vegas.js') }}"></script>

<!-- Scroll plugin -->
<script src="{{ asset('welcome/js//jquery.mousewheel.js') }}"></script>

<!-- Custom Scrollbar plugin -->
<script src="{{ asset('welcome/js//jquery.mCustomScrollbar.js') }}"></script>

<!-- PhotoSwipe Core JS file -->
<script src="{{ asset('welcome/js//photoswipe.js') }}"></script>

<!-- PhotoSwipe UI JS file -->
<script src="{{ asset('welcome/js//photoswipe-ui-default.js') }}"></script>

<!-- Main JS File -->
<script src="{{ asset('welcome/js//main.js') }}"></script>

<!--[if lt IE 10]>
<script type="text/javascript" src="{{ asset('welcome/js//placeholder.js') }}"></script>
<![endif]-->

</body>

</html>
