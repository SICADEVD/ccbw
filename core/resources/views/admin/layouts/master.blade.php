<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $general->siteName($pageTitle ?? '') }}</title>

    <link rel="shortcut icon" type="image/png" href="{{getImage(getFilePath('logoIcon') .'/favicon.png')}}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/fcadmin/css/vendor/bootstrap-toggle.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/global/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/global/css/line-awesome.min.css')}}">

    @stack('style-lib')

    <link rel="stylesheet" href="{{asset('assets/fcadmin/css/vendor/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/fcadmin/css/app.css')}}">
<link rel="stylesheet" href="{{asset('assets/templates/basic/css/custom.css')}}">
<style>
.navbar__action-list li label { 
    display: none;
}
</style>
    @stack('style')
</head>
<body>
@yield('content')
<script src="{{asset('assets/global/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('assets/global/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/fcadmin/js/vendor/bootstrap-toggle.min.js')}}"></script>
<script src="{{asset('assets/fcadmin/js/vendor/jquery.slimscroll.min.js')}}"></script>

@include('partials.plugins')
@include('partials.notify')
@stack('script-lib')

<script src="{{ asset('assets/fcadmin/js/nicEdit.js') }}"></script>
<script src="{{ asset('assets/fcadmin/js/printThis.js') }}"></script>

<script src="{{asset('assets/fcadmin/js/vendor/select2.min.js')}}"></script>
<script src="{{asset('assets/fcadmin/js/app.js')}}"></script>

{{-- LOAD NIC EDIT --}}
<script>
    "use strict";
    bkLib.onDomLoaded(function() {
        $( ".nicEdit" ).each(function( index ) {
            $(this).attr("id","nicEditor"+index);
            new nicEditor({fullPanel : true}).panelInstance('nicEditor'+index,{hasPanel : true});
        });
    });
    (function($){
        $( document ).on('mouseover ', '.nicEdit-main,.nicEdit-panelContain',function(){
            $('.nicEdit-main').focus();
        });
    })(jQuery);
</script>

@stack('script')
<script>
        (function ($) {
            "use strict"; 
            
            @if($general->ln) 
                $(".langChanage").on("change", function () {
                    window.location.href = "{{ route('admin.lang') }}/" + $('#lang').val();
                });
            @endif 
        })(jQuery);
    </script>

</body>
</html>
