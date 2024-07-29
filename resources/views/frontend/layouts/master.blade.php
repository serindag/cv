<!DOCTYPE html>
<html lang="en">

@include('frontend.layouts.partials.head')

<body class="homepage2-body">
    <!--===== PRELOADER STARTS =======-->
   @include('frontend.layouts.partials.preloader')
    <!--===== PRELOADER ENDS =======-->

    <!--===== PROGRESS STARTS=======-->
    <div class="paginacontainer">
        <div class="progress-wrap">
            <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
                <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
            </svg>
        </div>
    </div>
    <!--===== PROGRESS ENDS=======-->

    <div class="cursor cursor2"></div>
    <!--===== MOBILE HEADER STARTS =======-->

    @include('frontend.layouts.partials.mobile_header')

    <!--===== MOBILE HEADER STARTS =======-->

    <!--===== HEADER STARTS =======-->
    <div class="header-section-area"
        style="background-image: url(assets/img/bg/pagebg2.png); background-position: center; background-repeat: no-repeat; background-size: cover; background-attachment: fixed;">
        <div class="container">
            <div class="row">
                <div class="col-lg-2">
                    @include('frontend.layouts.partials.header')
                </div>
                <div class="col-lg-10">
                    <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-smooth-scroll="true"
                        class="scrollspy-example" tabindex="0">
                        @yield('content')

                    </div>

                    <!--===== SIDEBAR STARTS=======-->
                    @include('frontend.layouts.partials.blog_sliderbar')
                    <!--===== SIDEBAR ENDS STARTS=======-->
                </div>
            </div>
        </div>
    </div>
    <!--===== HEADER ENDS=======-->
    @include('frontend.layouts.partials.script')

</body>

</html>
