@extends('layouts.dashboard')

@section('title')
    Checkins
@endsection

@section('smalltitle')
    Liste de tous les checkins <b>pré-remplis</b>.
@endsection

@section('content')

    <div class="callout callout-info">
        <h4>Liste des checkins</h4>
        <p>
            Un checkin pré-rempli contient une liste d'étudiants qui devront être validés.
        </p>
    </div>

    <div class="box-header with-border">
        <h3 class="box-title">Création d'un nouveau checkin</h3>
        <a href="{{ url('dashboard/checkin/create') }}" class="btn btn-box-tool">
            <i class="fa fa-plus"></i>
        </a>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Liste des checkins</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>Nom</th>
                        <th>Actions</th>
                    </tr>
                    @foreach ($checkins as $checkin)
                        <tr>
                            <td>{{ $checkin->name }}</td>
                            <td>
                                <a class="btn btn-xs btn-warning" href="{{ url('dashboard/checkin/edit/'.$checkin->id) }}">Modifier</a>
                                <form action="{{ url('dashboard/checkin/'.$checkin->id) }}" method="post">
                                    {{ method_field('DELETE') }}
                                    <button class="btn btn-xs btn-danger" type="submit">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
