@extends('layouts.default')

@section('content')

<!-- Page Body -->
<div class="hk-pg-body py-0">
    <div class="calendarapp-wrap calendarapp-sidebar-toggle">
        <nav class="calendarapp-sidebar contactapp-sidebar-toggle">

        </nav>
        <div class="calendarapp-content">
            <div id="calendar" class="w-100"></div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        /* Single Date*/
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    });
</script>
@endsection