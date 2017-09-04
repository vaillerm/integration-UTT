@extends('layouts.dashboard')

@section('title')
    Creation d'un checkin pré-rempli
@endsection

@section('content')

    <div class="callout callout-info">
        <h4>Création d'un nouveau checkin</h4>
        <p>
            Pour un créer un checkin, renseignez un nom à celui et choisissez les étudiants qui feront partit de ce checkin.
        </p>
    </div>

    <div class="box box-default">
        <div class="box-body table-responsive">
            <form action="{{ url('dashboard/checkin') }}" method="post">
                <div class="form-group">
                    <label for="name">Nom du checkin</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                </div>

                <button type="submit" class="btn btn-success">Créer le checkin</button>
            </form>
        </div>
    </div>

@endsection
