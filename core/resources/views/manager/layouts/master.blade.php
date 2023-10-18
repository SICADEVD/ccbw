<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $general->siteName($pageTitle ?? '') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ getImage(getFilePath('logoIcon') . '/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fcadmin/css/vendor/bootstrap-toggle.min.css') }}">
    <!-- Datatable CSS -->
	<link rel="stylesheet" href="{{ asset('assets/fcadmin/css/dataTables.bootstrap4.min.css') }}">
    <!-- Main CSS -->
     <!-- Datepicker -->
 
	<link rel="stylesheet" href="{{ asset('assets/fcadmin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">
    @stack('style-lib')
    <link type="text/css" rel="stylesheet" media="all" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fcadmin/css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fcadmin/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dropify/css/dropify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/templates/basic/css/custom.css') }}"> 

  
<!-- TimePicker -->
<link rel="stylesheet" href="{{ asset('assets/vendor/css/bootstrap-timepicker.min.css') }}">
<!-- Latest compiled and minified CSS --> 
<link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/css/datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fcadmin/js/toastr/toastr.css') }}">
	<script src="{{ asset('assets/fcadmin/js/toastr/jquery.min.js') }}"></script> 
    <script src="{{ asset('assets/fcadmin/js/toastr/toastr.min.js') }}"></script> 
  
    @stack('style')
    <style>
        hr {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .error {
            color: red;
            font-weight: normal;
        }

        #cooperativeModel .close {
            display: none;
        }
    </style>
</head>

<body>
    
    @yield('content')

    <script src="{{ asset('assets/global/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/fcadmin/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/fcadmin/js/vendor/bootstrap-toggle.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/jquery.validate.js') }}"></script>
    <script src="{{ asset('assets/global/js/messages_fr.js') }}"></script>
    <script src="{{ asset('assets/fcadmin/js/vendor/jquery.slimscroll.min.js') }}"></script>
    @include('partials.plugins')
    @include('partials.notify')
    @stack('script-lib')
    <script src="{{ asset('assets/fcadmin/js/nicEdit.js') }}"></script>
    <script src="{{ asset('assets/fcadmin/js/printThis.js') }}"></script>
    <script src="{{ asset('assets/fcadmin/js/vendor/select2.min.js') }}"></script>
    <script src="{{ asset('assets/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/fcadmin/js/jquery.chained.js') }}"></script>
    <script src="{{ asset('assets/fcadmin/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('assets/fcadmin/js/dataTables.bootstrap4.min.js') }}"></script> 
    <script src="{{ asset('assets/vendor/jquery/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery/daterangepicker.min.js')}}" defer=""></script> 
 <script src="{{ asset('assets/vendor/jquery/datepicker.min.js') }}"></script>
<!-- Latest compiled and minified JavaScript --> 
<script src="{{ asset('assets/vendor/jquery/bootstrap-select.min.js') }}"></script> 
<script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/fcadmin/js/app.js') }}"></script>


    {{-- LOAD NIC EDIT --}}
    <script>
        "use strict";
        const datepickerConfig = {
        formatter: (input, date, instance) => {
            input.value = moment(date).format('YYYY-MM-DD')
        },
        showAllDates: true,
        customDays: ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],
        customMonths: ["January","February","March","April","May","June","July","August","September","October","November","December"],
        customOverlayMonths: ["January","February","March","April","May","June","July","August","September","October","November","December"],
        overlayButton: "Submit",
        overlayPlaceholder: "4-digit year",
        startDay: parseInt("1")
    };

    const daterangeConfig = {
        "Today": [moment(), moment()],
        "Last 30 Days": [moment().subtract(29, 'days'), moment()],
        "This Month": [moment().startOf('month'), moment().endOf('month')],
        "Last Month": [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        "Last 90 Days": [moment().subtract(89, 'days'), moment()],
        "Last 6 Months": [moment().subtract(6, 'months'), moment()],
        "Last 1 Year": [moment().subtract(1, 'years'), moment()]
    };

    const daterangeLocale = {
        "format": "DD-MM-YYYY",
        "customRangeLabel": "Custom Range",
        "separator": " To ",
        "applyLabel": "Apply",
        "cancelLabel": "Cancel",
        "monthNames": ["January","February","March","April","May","June","July","August","September","October","November","December"],
        "daysOfWeek": ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],
        "firstDay": parseInt("1")
    };

        bkLib.onDomLoaded(function() {
            $(".nicEdit").each(function(index) {
                $(this).attr("id", "nicEditor" + index);
                new nicEditor({
                    fullPanel: true
                }).panelInstance('nicEditor' + index, {
                    hasPanel: true
                });
            });
        });
        (function($) {
            $(document).on('mouseover ', '.nicEdit-main,.nicEdit-panelContain', function() {
                $('.nicEdit-main').focus();
            });
        })(jQuery);
    </script>
    <script>
        $(document).ready(function() {
            // $('#save-data-form select').select2();
            $('.select-picker').selectpicker('refresh');
            // Basic
            $('.dropify').dropify();

            // Translated
            $('.dropify-fr').dropify({
                messages: {
                    default: 'Glissez-déposez un fichier ici ou cliquez',
                    replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                    remove: 'Supprimer',
                    error: 'Désolé, le fichier trop volumineux'
                }
            });

            // Used events
            var drEvent = $('#input-file-events').dropify();

            drEvent.on('dropify.beforeClear', function(event, element) {
                return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
            });

            drEvent.on('dropify.afterClear', function(event, element) {
                alert('File deleted');
            });

            drEvent.on('dropify.errors', function(event, element) {
                console.log('Has Errors');
            });

            var drDestroy = $('#input-file-to-destroy').dropify();
            drDestroy = drDestroy.data('dropify')
            $('#toggleDropify').on('click', function(e) {
                e.preventDefault();
                if (drDestroy.isDropified()) {
                    drDestroy.destroy();
                } else {
                    drDestroy.init();
                }
            })
        });
      
        $(document).ready(function() {
            $("#flocal").validate();
        });
        
    </script>
  
    <script>
        document.loading = 'loading';
        const MODAL_DEFAULT = '#myModalDefault';
        const MODAL_LG = '#myModal';
        const MODAL_XL = '#myModalXl';
        const MODAL_HEADING = '#modelHeading';
        const RIGHT_MODAL = '#task-detail-1';
        const RIGHT_MODAL_CONTENT = '#right-modal-content';
        const RIGHT_MODAL_TITLE = '#right-modal-title';
        
    </script> 
    <script>
        $("input:checkbox").on('click', function()
        {
            var $box = $(this);
            if ($box.is(":checked"))
            {
                var group = "input:checkbox[class='" + $box.attr("class") + "']";
                $(group).prop("checked", false);
                $box.prop("checked", true);
            }
            else
            {
                $box.prop("checked", false);
            }
        });
        $('#country').change(function(){
            var phonecode = $(this).find(':selected').data('phonecode');
            console.log(phonecode);
            $('#country_phonecode').val(phonecode);
            $('.select-picker').selectpicker('refresh'); 
        }); 
        // select auto id and email
        $('#name').on('change',function()
        {
            $('#employee_id').val($(this).find(':selected').data('employee_id'));
            $('#email').val($(this).find(':selected').data('email'));
        });
        $('#marital_status').change(function(){
            var value = $(this).val();
            if(value == 'marie') {
                $('.marriage_date').removeClass('d-none');
            }
            else {
                $('.marriage_date').addClass('d-none');
            }
        });
        $('#employment_type').change(function(){
            var value = $(this).val();
            if(value == 'contractuel') {
                $('.contract-date').removeClass('d-none');
            }
            else {
                $('.contract-date').addClass('d-none');
            }

            if(value == 'interimaire') {
                $('.internship-date').removeClass('d-none');
            }
            else {
                $('.internship-date').addClass('d-none');
            }
        });
     
    datepicker('#date_of_birth', {
            position: 'bl', 
            maxDate: new Date(),  
            ...datepickerConfig
        });
        datepicker('#marriage_anniversary_date', {
            position: 'bl',
            maxDate: new Date(), 
            ...datepickerConfig
        });
        datepicker('#contract_end_date', {
            position: 'bl',
            ...datepickerConfig 
        });
        datepicker('#internship_end_date', {
            position: 'bl',
            ...datepickerConfig 
        });
        datepicker('#joining_date', {
            position: 'bl',
            ...datepickerConfig 
        });
    </script>
 
</body>

</html>
