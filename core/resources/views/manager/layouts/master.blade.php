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
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fcadmin/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/bootstrap-icons.css') }}">
    <link type="text/css" rel="stylesheet" media="all" href="{{ asset('assets/css/main.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor/jquery/bootstrap-select.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/fcadmin/css/style.css') }}">
    @stack('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/fcadmin/css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fcadmin/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dropify/css/dropify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/templates/basic/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/daterangepicker.css') }}">
    <script src="{{ asset('assets/global/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery/modernizr.min.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_VVwtAhchqsINCTqin22MG1AzMn7d6gk"></script> 

    @stack('style')
    <style>
        span.select2.select2-container {
            z-index: 15 !important;
        }

        .error {
            color: red;
            font-weight: normal;
        }

        #cooperativeModel .close {
            display: none;
        }

        .swal2-container {
            display: flex;
            position: fixed;
            z-index: 1111111 !important;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            padding: 0.625em;
            overflow-x: hidden;
            transition: background-color .1s;
            -webkit-overflow-scrolling: touch;
        }

        #flocal>div:nth-child(21)>div>span>span.selection>span {

            box-sizing: border-box;
            font-family: inherit;
            text-transform: none;
            word-wrap: normal;
            cursor: pointer;
            background-clip: padding-box;
            display: block;
            font-size: 12rem;
            line-height: 1.5;
            width: 100%;
            background-color: #fff;
            border: 1px solid #e8eef3;
            border-radius: .25rem;
            box-shadow: none;
            color: #e0e0e0;
            font-weight: 400;
            padding: 1px 0px 2px 0px;
            position: relative;
            transition: all .3s ease;
            height: 85px;
            appearance: auto;
        }

        #flocal>div:nth-child(21)>div>span>span.selection>span>span.select2-selection__arrow>b::before {
            display: block;
            margin: -6px !important;
        }

        #flocal>div:nth-child(21)>div>span>span.selection>span>span.select2-selection__arrow>b[role=presentation] {
            font-size: 1px;
            margin: -6px !important;
        }

        #flocal>div:nth-child(21)>div>span>span.selection>span {
            height: 45px !important;
        }

        hr {
            margin-top: 20px;
            margin-bottom: 20px;
            border-top: 1px solid #ddd;
        }
        .navbar__action-list li label { 
    display: none;
}
.grid-item {
  margin-bottom: 10px;
  width: 30%; 
  min-height: 200px;
}
    </style>
</head>

<body>

    @yield('content')


    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/fcadmin/js/vendor/bootstrap-toggle.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/jquery.validate.js') }}"></script>
    <script src="{{ asset('assets/global/js/messages_fr.js') }}"></script>
    <script src="{{ asset('assets/global/js/jquery.mask.js') }}"></script>
    <script src="{{ asset('assets/fcadmin/js/vendor/jquery.slimscroll.min.js') }}"></script>
    @include('partials.plugins')
    @include('partials.notify')
    @stack('script-lib')

    <script src="{{ asset('assets/fcadmin/js/nicEdit.js') }}"></script>
    <script src="{{ asset('assets/fcadmin/js/printThis.js') }}"></script>
    <script src="{{ asset('assets/fcadmin/js/vendor/select2.min.js') }}"></script>
    <script src="{{ asset('assets/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/fcadmin/js/jquery.chained.js') }}"></script>
    <script src="{{ asset('assets/fcadmin/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/fcadmin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/fcadmin/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery/daterangepicker.min.js') }}" defer=""></script>
    <script src="{{ asset('assets/vendor/jquery/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/global/js/masonry.pkgd.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/fcadmin/js/app.js') }}"></script>

 
<script>
$('.grid').masonry({   
    itemSelector: '.grid-item',  
    gutter: 10, 
    columnWidth: 20
     });
</script>
    @include('sections.modals')
    {{-- LOAD NIC EDIT --}}
    <script>
        
        (function ($) {
            "use strict"; 
            @if($general->ln) 
             
                $(".langChanage").on("change", function () {
                    // alert("{{ route('manager.lang') }}/" + $('#lang').val())
                  window.location.href = "{{ route('manager.lang') }}/" + $('#lang').val();
                });
            @endif 
        })(jQuery);
    </script>
    <script>
        $(document).ready(function() {
           // $('#producteurs').select2();
            $(".close-task-detail").click(function() {
                $(".task-detail-panel").removeClass("in")
                $(".task-overlay").removeClass("in")
                $(".close-task-detail").removeClass("in")
            });

            //Datemask dd/mm/yyyy
            $('.heure').mask('00:00', {
                placeholder: "__:__"
            });
            $('.date').mask('00/00/0000', {
                placeholder: "__/__/____"
            });
            $('.years').mask('0000', {
                placeholder: "____"
            });
            $('.phone').mask('0000000000', {
                placeholder: "__ __ __ __ __"
            });
            $('.age').mask('00', {
                placeholder: "__"
            });
            $('.text2').mask('00', {
                placeholder: "__"
            });
            $('.text11').mask('00000000000', {
                placeholder: "__ __ __ __ __ __ __ __ __ __ __"
            });
            $('.text4').mask('0000', {
                placeholder: "____"
            });

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
        "use strict";

        $('.select-picker').selectpicker('refresh');
        const datepickerConfig = {
            formatter: (input, date, instance) => {
                input.value = moment(date).format('YYYY-MM-DD')
            },
            showAllDates: true,
            customDays: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            customMonths: ["January", "February", "March", "April", "May", "June", "July", "August", "September",
                "October", "November", "December"
            ],
            customOverlayMonths: ["January", "February", "March", "April", "May", "June", "July", "August", "September",
                "October", "November", "December"
            ],
            overlayButton: "Submit",
            overlayPlaceholder: "4-digit year",
            startDay: parseInt("1")
        };

        const daterangeConfig = {
            "Today": [moment(), moment()],
            "Last 30 Days": [moment().subtract(29, 'days'), moment()],
            "This Month": [moment().startOf('month'), moment().endOf('month')],
            "Last Month": [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                'month')],
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
            "monthNames": ["January", "February", "March", "April", "May", "June", "July", "August", "September",
                "October", "November", "December"
            ],
            "daysOfWeek": ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
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
        document.loading = 'loading';
        const MODAL_DEFAULT = '#myModalDefault';
        const MODAL_LG = '#myModal';
        const MODAL_XL = '#myModalXl';
        const MODAL_HEADING = '#modelHeading';
        const RIGHT_MODAL = '#task-detail-1';
        const RIGHT_MODAL_CONTENT = '#right-modal-content';
        const RIGHT_MODAL_TITLE = '#right-modal-title';


        $('body').on('click', '.img-lightbox', function() {
            const imageUrl = $(this).data('image-url');
            const url = "";
            $(MODAL_XL + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_XL, url);
        });
    </script>

    <script>
        $('body').on('click', '#pause-timer-btn, .pause-active-timer', function() {
            const id = $(this).data('time-id');
            let url = "{{ route('manager.hr.timelogs.pause_timer', ':id') }}";
            url = url.replace(':id', id);
            const token = '{{ csrf_token() }}';

            let currentUrl = $(this).data('url');

            $.easyAjax({
                url: url,
                blockUI: true,
                type: "POST",
                disableButton: true,
                buttonSelector: "#pause-timer-btn",
                data: {
                    timeId: id,
                    currentUrl: currentUrl,
                    _token: token
                },
                success: function(response) {
                    if (response.status === 'success') {
                        if ($('#myActiveTimer').length > 0) {
                            $(MODAL_XL + ' .modal-content').html(response.html);

                            if ($('#allTasks-table').length) {
                                window.LaravelDataTables["allTasks-table"].draw(false);
                            }
                        }

                        if ($('#allTasks-table').length) {
                            window.LaravelDataTables["allTasks-table"].draw(false);
                        }

                        if (response.reload === 'yes') {
                            window.location.reload();
                        } else {
                            $('#timer-clock').html(response.clockHtml);
                        }
                    }
                }
            })
        });

        $('body').on('click', '#resume-timer-btn, .resume-active-timer', function() {
            const id = $(this).data('time-id');
            let url = "{{ route('manager.hr.timelogs.resume_timer', ':id') }}";
            url = url.replace(':id', id);
            const token = '{{ csrf_token() }}';

            let currentUrl = $(this).data('url');

            $.easyAjax({
                url: url,
                blockUI: true,
                type: "POST",
                disableButton: true,
                buttonSelector: "#resume-timer-btn",
                data: {
                    timeId: id,
                    currentUrl: currentUrl,
                    _token: token
                },
                success: function(response) {

                    if (response.status === 'success') {
                        if ($('#myActiveTimer').length > 0) {
                            $(MODAL_XL + ' .modal-content').html(response.html);
                        }

                        $('#timer-clock').html(response.clockHtml);
                        if ($('#allTasks-table').length) {
                            window.LaravelDataTables["allTasks-table"].draw(false);
                        }

                        if (response.reload === 'yes') {
                            window.location.reload();
                        }
                    }
                }
            })
        });

        $('body').on('click', '.stop-active-timer', function() {
            const id = $(this).data('time-id');
            let url = "{{ route('manager.hr.timelogs.stop_timer', ':id') }}";
            url = url.replace(':id', id);
            const token = '{{ csrf_token() }}';

            let currentUrl = $(this).data('url');

            $.easyAjax({
                url: url,
                type: "POST",
                data: {
                    timeId: id,
                    currentUrl: currentUrl,
                    _token: token
                },
                success: function(response) {
                    if ($('#myActiveTimer').length > 0) {
                        $(MODAL_XL + ' .modal-content').html(response.html);
                    }

                    if (response.activeTimerCount > 0) {
                        $('#show-active-timer .active-timer-count').html(response.activeTimerCount);
                    } else {
                        $('#show-active-timer .active-timer-count').addClass('d-none');
                    }

                    $('#timer-clock').html('');
                    if ($('#allTasks-table').length) {
                        window.LaravelDataTables["allTasks-table"].draw(false);
                    }

                    if (response.reload === 'yes') {
                        window.location.reload();
                    }

                }
            })

        });

        function geoFindMe() {
  const status = document.querySelector("#status");
  function success(position) {
    const latitude = position.coords.latitude;
    const longitude = position.coords.longitude;

$('input[name=longitude]').val(longitude);
$('input[name=latitude]').val(latitude);
$("input[name=longitude], input[name=latitude]").attr({"readonly": 'readonly'})
  }
  function error() {
    status.textContent = "Unable to retrieve your location";
  }
  if (!navigator.geolocation) {
    status.textContent = "Geolocation is not supported by your browser";
  } else {
    // status.textContent = "Locating…";
    navigator.geolocation.getCurrentPosition(success, error);
  }
}
document.querySelector("#find-me").addEventListener("click", geoFindMe);
    </script>
    
    @stack('script')
</body>

</html>
