@extends('layout')


@push('scripts')
<script src="/assets/plugins/morris/raphael.min.js"></script>
<script src="/assets/plugins/morris/morris.js"></script>
<script src="/assets/plugins/jquery-jvectormap/jquery-jvectormap.min.js"></script>
<script src="/assets/plugins/jquery-jvectormap/jquery-jvectormap-world-merc-en.js"></script>
<script src="/assets/plugins/bootstrap-calendar/js/bootstrap_calendar.min.js"></script>
<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
<script src="/assets/js/dashboard-v2.min.js"></script>
<script src="/assets/js/apps.min.js"></script>
@endpush

@section('extraScript')
    <script>
        $(document).ready(function() {
//            DashboardV2.init();
        });
    </script>
@endsection
