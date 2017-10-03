<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title') - Local Food Nodes</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.png" />
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="{{ mix('css/public.css') }}">
        <link rel="stylesheet" href="{{ mix('css/account.css') }}">

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

        <script src="/js/tinymce/tinymce.min.js"></script>
        <script>tinymce.init({
            selector: '.wysiwyg',
            menubar: false,
            plugins: 'code',
            toolbar: 'formatselect bold italic alignleft aligncenter alignright alignjustify code',
            block_formats: 'Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6',
            inline_styles: false,
            valid_classes: '',
            valid_elements : 'h1,h2,h3,h4,h5,h6,p,a[href|target=_blank],strong/b,div,span,br',
            paste_word_valid_elements: 'h1,h2,h3,h4,h5,h6,p,a[href|target=_blank],strong/b,div,span,br',
            paste_data_images: false,
            paste_as_text: true,
        });</script>

        <!-- Flatpickr -->
        <link rel="stylesheet" href="https://unpkg.com/flatpickr/dist/flatpickr.min.css">
        <script src="https://unpkg.com/flatpickr"></script>
        <script type="text/javascript">
            $(function() {
                var bindDatepicker = function() {
                    // Datetime picker
                    $('.picker.datetime:not(.bound)').addClass('bound').flatpickr({
                        enableTime: true,
                        time_24hr: true,
                        dateFormat: 'Y-m-d H:i',
                        locale: {firstDayOfWeek: 1},
                    });

                    // Date picker
                    $('.picker.date:not(.bound)').addClass('bound').flatpickr({
                        dateFormat: 'Y-m-d',
                        locale: {firstDayOfWeek: 1},
                    });

                    // Time picker
                    $('.picker.time:not(.bound)').addClass('bound').flatpickr({
                        noCalendar: true,
                        enableTime: true,
                        time_24hr: true,
                        dateFormat: 'H:i',
                    });
                };

                bindDatepicker();

                $(document).on('bindDatepicker', function(event) {
                    bindDatepicker();
                });

                // Enable tooltip
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
    </head>
    <body class="admin {{ $viewName }} logged-in">
        <div class="page">
            @include('account.user-nav')
            @include('account.user-nav-mobile')
            <div class="content">
                <div class="container-fluid">
                    @include('shared.errors')
                    @yield('content')
                </div>
            </div>
        </div>

        @yield('modal')

        <script>
            jQuery(document).ready(function() {
                // Hide card block on load if class is chevron-down
                $('.card-header.toggle .fa-chevron-down').closest('.card').find('.card-body').hide();

                $('.card-header.toggle').on('click', function() {
                    var cardBlock = $(this).closest('.card').find('.card-body');
                    var toggleIcon = $(this).find('.fa.toggle');

                    if (cardBlock.is(':visible')) {
                        toggleIcon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                        cardBlock.hide();
                    } else {
                        toggleIcon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                        cardBlock.show();
                    }
                });
            });
        </script>
        <script src="https://embed.small.chat/T0Z3AQJK1G5Q08NBRS.js" async></script>
    </body>
</html>
