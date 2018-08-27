@extends('layouts.dashboard')

@section('title')
    Creation d'un évènement
@endsection

@section('content')

    <div class="callout callout-info">
        <h4>Création d'un nouvel évènements</h4>
        <p>
            Pour créér un évènement, choisissez une date de début ainsi qu'une date de fin (date et heure). Renseignez un nom
            et une description qui définissent l'évènement, ainsi que l'endroit où il aura lieu. Pour finir, vous devez selectionner
            les catégories de personnes qui sont invités à cet évènement.
        </p>
    </div>

    <div class="box box-default">
        <div class="box-body table-responsive">
            <form action="{{ url('dashboard/event') }}" method="post">
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <label for="name">Description</label>
                    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="place">Lieu</label>
                    <input type="text" class="form-control" name="place" value="{{ old('place') }}">
                </div>
                <div class="form-group">
                    <label>Début (date et heure)</label>
                    <input type="date" class="form-control" value="{{ old('start_at_date') }}" name="start_at_date">
                    <input type="time" class="form-control" value="{{ old('start_at_hour') }}" name="start_at_hour">
                </div>
                <div class="form-group">
                    <label>Fin (date et heure)</label>
                    <input type="date" class="form-control" value="{{ old('end_at_date') }}" name="end_at_date">
                    <input type="time" class="form-control" value="{{ old('end_at_hour') }}" name="end_at_hour">
                </div>

                <div class="checkbox">
                    <label><input type="checkbox" name="categories[]" value="admin" @if(is_array(old('categories')) && in_array('admin', old('categories'))) checked @endif>Admin</label>
                </div>
                <div class="checkbox">
                  <label><input type="checkbox" name="categories[]" value="newcomerTC" @if(is_array(old('categories')) && in_array('newcomerTC', old('categories'))) checked @endif>Nouveau TC</label>
                </div>
                <div class="checkbox">
                  <label><input type="checkbox" name="categories[]" value="newcomerBranch" @if(is_array(old('categories')) && in_array('newcomerBranch', old('categories'))) checked @endif>Nouveau Branche</label>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" name="categories[]" value="volunteer" @if(is_array(old('categories')) && in_array('volunteer', old('categories'))) checked @endif>Volontaire</label>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" name="categories[]" value="orga" @if(is_array(old('categories')) && in_array('orga', old('categories'))) checked @endif>Orga</label>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" name="categories[]" value="ce" @if(is_array(old('categories')) && in_array('ce', old('categories'))) checked @endif>Chef d'équipe</label>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" name="categories[]" value="referral" @if(is_array(old('categories')) && in_array('referral', old('categories'))) checked @endif>Parrain</label>
                </div>

                <button type="submit" class="btn btn-success">Créer l'évènement</button>
            </form>
        </div>
    </div>

@endsection
