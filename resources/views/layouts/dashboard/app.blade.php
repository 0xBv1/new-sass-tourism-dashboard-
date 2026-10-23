<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Perfect Solutions 4U Dashboard">
    <meta name="keywords" content="admin dashboard, perfect solutions 4u, web app">
    <meta name="author" content="Abdelrahman Adly">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--    <link rel="icon" href="assets/images/dashboard/favicon.png" type="image/x-icon">--}}
    {{--    <link rel="shortcut icon" href="assets/images/dashboard/favicon.png" type="image/x-icon">--}}
    <title>{{ config('app.name') }} | Dashboard</title>

    <!-- Google font-->
    <link rel="stylesheet"  href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,500;1,600;1,700;1,800;1,900&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/admin.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/custom.css') }}?ver=1.0.2">
    <!-- Quill Editor CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <!-- Highlight.js CSS for syntax highlighting -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/default.min.css" rel="stylesheet">
    <!-- Easy Editor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/easy-editor.css') }}?ver=1.0.0">
    @stack('css')
</head>

<body class="{{ admin()->theme }}">
<!-- page-wrapper Start-->
<div class="page-wrapper">

    @include('layouts.dashboard.navbar')

    <!-- Page Body Start -->
    <div class="page-body-wrapper">
        @include('layouts.dashboard.sidebar')
        <div class="page-body">
        @yield('content')
        </div>
        @include('layouts.dashboard.footer')
    </div>
</div>
<!-- page-wrapper end-->
@stack('js-upper')
<!--script admin-->
<script>window.supportedLocales = {!! collect(config('translatable.locales'))->toJson() !!} </script>
<!-- Highlight.js for syntax highlighting (must be loaded before Quill) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<!-- Register Quill modules before Easy Editor -->
<script>
    // Note: Image resize module removed due to CDN availability issues
    // The editor will work without it - images can still be inserted, just not resized
    // If needed, you can add a local copy of quill-image-resize-module
</script>
<!-- Easy Editor JS -->
<script src="{{ asset('assets/admin/js/easy-editor.js') }}?ver=1.0.1"></script>
<script src="{{ asset('assets/admin/js/admin.js') }}?ver=1.1.2"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ext-language_tools.js"></script>

@isset($dataTable)
    <x-dashboard.partials.delete-resource-modal />
    {!! $dataTable->scripts() !!}
@endisset
<script>
    // Easy Editor initialization function (replaces TinyMCE)
    const easyEditorInitEditor = (selector) => {
        if (typeof EasyEditor !== 'undefined') {
            try {
                return new EasyEditor(selector);
            } catch (error) {
                console.error('Easy Editor initialization error:', error);
                return null;
            }
        } else {
            console.warn('Easy Editor not loaded');
            return null;
        }
    }

    // Initialize Easy Editor for all code editors
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize existing editors
        if (typeof EasyEditor !== 'undefined') {
            setTimeout(() => {
                const editors = document.querySelectorAll('.code-editor-tiny, .code-editor');
                if (editors.length > 0) {
                    window.easyEditorInstance = new EasyEditor('.code-editor-tiny, .code-editor');
                }
            }, 100);
        }
    });

    $('.auto-translate').each(function () {
        $(this).on('click', function () {
            if(confirm('This will overwrite any translated property!')) {
                $(this).addClass('disabled')
                let $icon = $(this).find('.icon')
                $icon.removeClass('fa-language')
                $icon.addClass('fa-spinner fa-spin')
                axios.post("{{ route('dashboard.model.auto.translate') }}", {
                    model: $(this).data('model'),
                    id: $(this).data('id'),
                })
                    .then(response=> toastr.success(response.data.message))
                    .catch(error=> toastr.error(error?.response?.data?.message || "Unexpected Error!"))
                    .finally(()=> {
                        $(this).removeClass('disabled')
                        $icon.addClass('fa-language')
                        $icon.removeClass('fa-spinner fa-spin')
                    })
            }
        })
    })

    $('.page-main-header .switch').on('click', function(){
        $('.dataTables_wrapper table.dataTable').css({width: '100% !important'})
    })

    // $(`#en-title,#en-name`).on('keyup', function () {
    //     let slug = slugify($(this).val())
    //     $(`#slug`).val(slug)
    // })

    $('.clear-app-cache').on('click', function (e) {
        e.preventDefault()
        if(confirm('Are you sure purge cache?')) {
            axios.post($(this).attr('href'), {
                _token: '{{ csrf_token() }}'
            }).then(response => {
                toastr.success(response.data.message)
            }).catch(error=> {
                toastr.success(error.response.data.message)
            })
        }
    })

    // Fix for "An invalid form control with name='' is not focusable" error
    // This handles required fields in hidden tab panes before form submission
    $(document).on('submit', 'form', function(e) {
        const form = this;
        
        // Find all tab panes that are not active (hidden)
        const hiddenTabPanes = $(form).find('.tab-pane:not(.active)');
        
        // Handle all required fields in hidden tabs
        hiddenTabPanes.find('input[required], textarea[required], select[required]').each(function() {
            const $field = $(this);
            const fieldType = $field.prop('tagName').toLowerCase();
            const fieldValue = $field.val();
            
            // If field is empty, set it to null/empty string
            if (!fieldValue || fieldValue.trim() === '') {
                if (fieldType === 'select') {
                    // For select, set to empty or first option
                    $field.val('');
                } else if (fieldType === 'input' && $field.attr('type') === 'checkbox') {
                    // For checkboxes, uncheck them
                    $field.prop('checked', false);
                } else {
                    // For text inputs and textareas, set to empty string
                    $field.val('');
                }
            }
            
            // Remove required attribute
            $field.removeAttr('required');
        });
        
        // Also handle fields that might be in containers with display:none
        $(form).find('input[required], textarea[required], select[required]').each(function() {
            const $field = $(this);
            const $parent = $field.closest('.tab-pane, [style*="display: none"], [style*="display:none"]');
            
            // Check if field or its parent is hidden
            if ($parent.length > 0 && !$parent.hasClass('active') && !$parent.is(':visible')) {
                const fieldType = $field.prop('tagName').toLowerCase();
                const fieldValue = $field.val();
                
                // If field is empty, set it to null/empty string
                if (!fieldValue || (typeof fieldValue === 'string' && fieldValue.trim() === '')) {
                    if (fieldType === 'select') {
                        $field.val('');
                    } else if (fieldType === 'input' && $field.attr('type') === 'checkbox') {
                        $field.prop('checked', false);
                    } else {
                        $field.val('');
                    }
                }
                
                $field.removeAttr('required');
            } else if (!$field.is(':visible')) {
                // Field itself is hidden
                const fieldType = $field.prop('tagName').toLowerCase();
                const fieldValue = $field.val();
                
                if (!fieldValue || (typeof fieldValue === 'string' && fieldValue.trim() === '')) {
                    if (fieldType === 'select') {
                        $field.val('');
                    } else if (fieldType === 'input' && $field.attr('type') === 'checkbox') {
                        $field.prop('checked', false);
                    } else {
                        $field.val('');
                    }
                }
                
                $field.removeAttr('required');
            }
        });
    });
</script>
@stack('js')
</body>
</html>
