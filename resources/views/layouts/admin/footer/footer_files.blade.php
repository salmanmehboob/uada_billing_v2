<!-- Core JS -->
<!-- build:js assets/vendor/js/theme.js -->

<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/@algolia/autocomplete-js.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>

<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>


<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>
 <script src="{{ asset('assets/vendor/libs/cleave-zen/cleave-zen.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>




<script>
    var urlPath = '<?php echo url(""); ?>';
    var CSRF_TOKEN = '<?php echo csrf_token(); ?>';

    window.sessionMessages = {
        success: @json(session('success')),
        warning: @json(session('warning')),
        error: @json(session('error')),
        info: @json(session('info'))
    };
</script>
<!-- Page JS -->

@stack('page_js')

<!-- Main JS -->
<script src="{{ asset('assets/js/main.js') }}"></script>

<!-- Page JS -->
{{--<script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>--}}

<script src="{{ asset('assets/vendor/libs/notyf/notyf.js') }}"></script>
<script src="{{ asset('assets/js/forms-extras.js') }}"></script>
<script src="{{ asset('assets/js/ui-toasts.js') }}"></script>
<script src="{{ asset('assets/js/ui-modals.js') }}"></script>

