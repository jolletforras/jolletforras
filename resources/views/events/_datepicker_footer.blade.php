@section('footer')
    <script src="{{ url('/') }}/js/datepicker.js"></script>
    <script src="{{ url('/') }}/js/i18n/datepicker.en.js"></script>
    <script src="{{ url('/') }}/js/i18n/datepicker.hu.js"></script>

    <script type="text/javascript">
        $('.datepicker-event').datepicker({
            language: 'hu',
            timepicker: true,
            timeFormat: 'hh:ii',
        });

        $('.datepicker-expiration').datepicker({
            language: 'hu',
        });
    </script>
@endsection