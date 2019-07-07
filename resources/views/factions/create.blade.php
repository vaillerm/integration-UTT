@extends('layouts.dashboard')

@section('title')
    Création d'une faction
@endsection

@section('content')

    <div class="callout callout-info">
        <h4>Création d'une nouvelle faction</h4>
        <p>
            Veuillez simplement renseigner le nom de la faction que vous désirez créer.
        </p>
    </div>

    <div class="box box-default">
        <div class="box-body table-responsive">
            <form action="{{ url('faction/create') }}" method="post">
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                </div>

                <button type="submit" class="btn btn-success">Créer la faction</button>
            </form>
        </div>
    </div>

@endsection
