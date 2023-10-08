@extends('staff.layouts.master')

@section('content')
    <!-- page-wrapper start -->
    <div class="page-wrapper default-version">
        @include('staff.partials.sidenav')
        @include('staff.partials.topnav')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                @include('staff.partials.breadcrumb')
                @yield('panel')
            </div><!-- bodywrapper__inner end -->
        </div><!-- body-wrapper end -->
    </div>
@endsection
