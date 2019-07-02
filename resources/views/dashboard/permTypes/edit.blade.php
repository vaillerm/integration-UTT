@extends('layouts.dashboard')

@section('title')
    Modification d'un type de permanence
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/student_autocomplete.css') }}">
@endsection

@section('smalltitle')
    {{ $permType->name }}
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-body table-responsive">
            <form autocomplete="off" action="{{ url('dashboard/permType/'.$permType->id) }}" method="post" id="form">
                {{ method_field('PUT') }}
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" class="form-control" name="name" value="{{ $permType->name }}">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control">{{ $permType->description }}</textarea>
                </div>
                <div class="form-group">
                    <label for="points">Points</label>
                    <input type="number" name="points" class="form-control" value="{{ $permType->points }}">
                </div>

                <div class="form-group" id="student_autocomplete_container">
                    <label>Ajouter des respos par nom/prénom (il faut rentrer tout les respos à chaque modification)</label>
                    <input type="text" class="form-control" id="student_autocomplete">
                </div>
                <div id="student_autocomplete_matches_container">
                    <div id="student_autocomplete_matches"></div>
                </div>
                <ul class="list-group" id="student_autocomplete_selected_container">
                    @foreach ($permType->respos as $respo)
                        <li class="list-group-item" data-id="{{ $respo->id }}">
                            {{ $respo->first_name.' '.$respo->last_name }}
                            <span class="autocomplete_remove">x</span>
                        </li>
                    @endforeach
                </ul>

                <button type="submit" class="btn btn-success" id="formSubmit">Modifier le type de permanence</button>
            </form>
        </div>
    </div>

@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/student_autocomplete.js') }}"></script>
@endsection