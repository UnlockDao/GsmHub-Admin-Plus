<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>S-Unlock</title>

    <!-- Summernote css -->
    <link href="{{asset('')}}/assets/css/vendor/summernote-bs4.css" rel="stylesheet" type="text/css" />
    <!-- SimpleMDE css -->
    <link href="{{asset('')}}/assets/css/vendor/simplemde.min.css" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="{{asset('')}}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('')}}/assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
    /* for custom scrollbar for webkit browser*/

    ::-webkit-scrollbar {
        width: 3px;
    }
    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 75, 180, 0.3);
    }
    ::-webkit-scrollbar-thumb {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    }
</style>
<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
<script src="{{asset('')}}/assets/js/app.min.js"></script>
<script src="{{ asset('js/jquery.tableCheckbox.js') }}"></script>
<script>
    $('table').tableCheckbox({ /* options */ });
</script>

<!-- Summernote js -->
<script src="{{asset('')}}/assets/js/vendor/summernote-bs4.min.js"></script>
<script>
    !function ($) {
        "use strict";

        var Summernote = function () {
            this.$body = $("body")
        };


        /* Initializing */
        Summernote.prototype.init = function () {
            $('#summernote-basic').summernote({
                placeholder: 'Write something...',
                height: 230,
                callbacks: {
                    // fix broken checkbox on link modal
                    onInit: function onInit(e) {
                        var editor = $(e.editor);
                        editor.find('.custom-control-description').addClass('custom-control-label').parent().removeAttr('for');
                    }
                }
            });
            // air mode on
            $('#summernote-airmode').summernote({
                airMode: true,
                callbacks: {
                    // fix broken checkbox on link modal
                    onInit: function onInit(e) {
                        var editor = $(e.editor);
                        editor.find('.custom-control-description').addClass('custom-control-label').parent().removeAttr('for');
                    }
                }
            });

            // click to edit
            var edit = function edit() {
                $('#summernote-editmode').summernote({
                    focus: true,
                    callbacks: {
                        // fix broken checkbox on link modal
                        onInit: function onInit(e) {
                            var editor = $(e.editor);
                            editor.find('.custom-control-description').addClass('custom-control-label d-block').parent().removeAttr('for');
                        }
                    }
                });
            };
            var save = function save() {
                var makrup = $('#summernote-editmode').summernote('code');
                $('#summernote-editmode').summernote('destroy');
            };

            $('#summernote-edit').on('click', function () {
                edit();
                // toggle buttons
                $(this).hide();
                $('#summernote-save').show();
            });
            $('#summernote-save').on('click', function () {
                save();
                // toggle buttons
                $(this).hide();
                $('#summernote-edit').show();
            });
        },

            //init Summernote
            $.Summernote = new Summernote, $.Summernote.Constructor = Summernote

    }(window.jQuery),

        //initializing Summernote
        function ($) {
            "use strict";
            $.Summernote.init()
        }(window.jQuery);

</script>


</html>
