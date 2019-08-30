@extends('layouts.dashboard')

@section('title')
    Calendrier
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/student_autocomplete.css') }}">
@endsection

@section('content')

    <div class="box box-yellow">
        <div class="box-header with-border">
            <h3 class="box-title">Calendrier</h3>
        </div>
        <div class="box-body">
            {!! $calendar->calendar() !!}
        </div>
        <!-- /.box-body -->
    </div>

@endsection

@section('js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
    {!! $calendar->script() !!}
@endsection
