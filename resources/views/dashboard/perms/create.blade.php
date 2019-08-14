@extends('layouts.dashboard')

@section('title')
    Creation d'une permanence de type {{ $permType->name }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/student_autocomplete.css') }}">
@endsection

@section('content')

    <div class="callout callout-info">
    <h4>Création d'une permanence de type {{ $permType->name }}</h4>
        <p>
            Pour créér une permanence... TODO
        </p>
    </div>

    <div class="box box-default">
        <div class="box-body table-responsive">
            <form autocomplete="off" action="{{ url('dashboard/perm') }}" method="post" id="form">
                <input type="hidden" value="{{ $permType->id }}" name="perm_type_id">
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control">{{ old('description') ?? $permType->description }}</textarea>
                </div>
                <div class="form-group">
                    <label for="place">Lieu</label>
                    <input type="text" name="place" class="form-control" value="{{ old('place') }}">
                </div>
                <div class="form-group">
                    <label for="nbr_permanenciers">Nombre de permanenciers (sans compter les respos)</label>
                    <input type="number" name="nbr_permanenciers" class="form-control" value="{{ old('nbr_permanenciers') }}">
                </div>
                <div class="form-group">
                    <label>Début (date et heure)</label>
                    <input type="date" class="form-control" value="{{ old('start_date') }}" name="start_date">
                    <input type="time" class="form-control" value="{{ old('start_hour') }}" name="start_hour">
                </div>
                <div class="form-group">
                    <label>Fin (date et heure)</label>
                    <input type="date" class="form-control" value="{{ old('end_date') }}" name="end_date">
                    <input type="time" class="form-control" value="{{ old('end_hour') }}" name="end_hour">
                </div>
                <div class="form-group">
                    <label>Date d'ouverture (Vous pouvez laisser vide, les permanenciers devront être ajoutés à la main)</label>
                    <input type="date" class="form-control" value="{{ old('open_date') }}" name="open_date">
                    <input type="time" class="form-control" value="{{ old('open_hour') }}" name="open_hour">
                </div>

                <div class="form-group" id="student_autocomplete_container">
                    <label for="name">Ajouter des respos par nom/prénom</label>
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

                <button type="submit" class="btn btn-success" id="formSubmit">Créer la permanence</button>
            </form>
        </div>
    </div>

@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/student_autocomplete.js') }}"></script>
@endsection