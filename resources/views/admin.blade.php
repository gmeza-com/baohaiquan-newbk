@extends('layout')

@section('layout')
    <div id="page-wrapper" class="page-loading">

        <!-- Preloader -->
        <div class="preloader themed-background">
            <div class="inner">
                <h3 class="text-light visible-lt-ie10"><strong>Loading..</strong></h3>
                <div class="preloader-spinner hidden-lt-ie10"></div>
            </div>
        </div>

        <!-- page container -->
        <div id="page-container" class="sidebar-partial sidebar-visible-lg sidebar-no-animations">

            @include('partial.sidebar')

            <!-- Main Container -->
            <div id="main-container">

                @include('partial.navbar')

                <!-- Page content -->
                <div id="page-content">
                    @include('partial.breadcrumb')

                    <!-- Blank Header -->
                    <div class="content-header">
                        <div class="header-section">
                            @yield('page_header')
                        </div>
                    </div>




                    <!-- END Blank Header -->

                    @yield('content')

                </div>
                <!-- END Page Content -->

                @include('partial.footer')

            </div>
            <!-- END Main Container -->
        </div>

    </div>

    <a href="#" id="to-top"><i class="fa fa-angle-double-up"></i></a>

@stop
