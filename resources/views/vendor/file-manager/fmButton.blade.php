<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'File Manager') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12" id="fm-main-block">
            <div id="fm"></div>
        </div>
    </div>
</div>

<!-- File manager -->
<script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
<script>

    document.addEventListener('DOMContentLoaded', function () {
        // set fm height
        document.getElementById('fm-main-block').setAttribute('style', 'height:' + window.innerHeight + 'px');
        try {
            document.querySelector('.bi.bi-grid').parentElement.click()
        } catch (e) {}
        // Add callback to file manager
        fm.$store.commit('fm/setFileCallBack', function (fileUrl) {
            if (window.opener.hasOwnProperty('fmSetLink')) {
                window.opener.fmSetLink(fileUrl, window.opener.payload.target, window.opener.payload.name);
                if (!window.opener.payload.multiple) {
                    window.close();
                }
            }
        });
    });
</script>
</body>
</html>

