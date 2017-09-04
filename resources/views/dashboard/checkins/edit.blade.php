@extends('layouts.dashboard')

@section('title')
    Modification d'un checkin
@endsection

@section('smalltitle')
    {{ $checkin->name }}
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-body table-responsive">
            <form action="{{ url('dashboard/checkin/'.$checkin->id) }}" method="post">
                {{ method_field('PUT') }}
                <div class="form-group">
                    <label for="name">Nom du checkin</label>
                    <input type="text" class="form-control" name="name" value="{{ $checkin->name }}">
                </div>

                <button type="submit" class="btn btn-success">Modifier le checkin</button>
            </form>
        </div>
    </div>

@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/student_autocomplete.js') }}"></script>
@endsection
