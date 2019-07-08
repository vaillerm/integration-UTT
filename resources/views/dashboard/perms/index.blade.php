@extends('layouts.dashboard')

@section('title')
    Permanences
@endsection

@section('smalltitle')
    Liste de toutes les permanences.
@endsection

@section('content')

    <div class="callout callout-info">
        <h4>Liste de toutes les permanences</h4>
        <p>
            Une permanence a TODO
        </p>
    </div>

    <div class="box-header with-border">
        <h3 class="box-title">Création d'une permanence</h3>
        <a href="{{ url('dashboard/perm/create') }}" class="btn btn-box-tool">
            <i class="fa fa-plus"></i>
        </a>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Liste des permanences</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>Nom</th>
                        <th>Lieu</th>
                        <th>Date</th>
                        <th>Permanenciers</th>
                        <th>Description</th>
                        <th>Responsables</th>
                        <th>Points</th>
                        <th>Actions</th>
                    </tr>
                    @foreach ($perms as $perm)
                        <tr>
                            <td>{{ $perm->type->name }}</td>
                            <td>{{ $perm->place }}</td>
                            <td>Le {{ date('d/m', $perm->start).' de '.date('H:i', $perm->start).' à '.date('H:i', $perm->end) }}</td>
                            <td>{{ $perm->permanenciers->count().'/'.$perm->nbr_permanenciers }}</td>
                            <td>{{ $perm->description }}</td>
                            <td>
                              @foreach ($perm->respos as $respo)
                                  <span class="label label-info">{{ $respo->first_name.' '.$respo->last_name }}</span>
                              @endforeach
                            </td>
                            <td>{{ $perm->type->points }}</td>
                            <td>
                                <a class="btn btn-xs btn-info" href="{{ url('dashboard/perm/'.$perm->id.'/users') }}">Liste des permanenciers</a>
                                <a class="btn btn-xs btn-warning" href="{{ url('dashboard/perm/edit/'.$perm->id) }}">Modifier</a>
                                <form action="{{ url('dashboard/perm/'.$perm->id) }}" method="post">
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
