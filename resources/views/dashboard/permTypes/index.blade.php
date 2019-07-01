@extends('layouts.dashboard')

@section('title')
    Types de permanences
@endsection

@section('smalltitle')
    Liste de tous les types de permanences.
@endsection

@section('content')

    <div class="callout callout-info">
        <h4>Liste des types de permanences</h4>
        <p>
            Un type de permanence a fpjzpfzfzg todo
        </p>
    </div>

    <div class="box-header with-border">
        <h3 class="box-title">Création d'un nouveau type de permanence</h3>
        <a href="{{ url('dashboard/permType/create') }}" class="btn btn-box-tool">
            <i class="fa fa-plus"></i>
        </a>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Liste des types de permanences</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Points</th>
                        <th>Responsables</th>
                        <th>Actions</th>
                    </tr>
                    @foreach ($permTypes as $permType)
                        <tr>
                            <td>{{ $permType->name }}</td>
                            <td>{{ $permType->description }}</td>
                            <td>{{ $permType->points }}</td>
                            <td>
                              @foreach ($permType->respos as $respo)
                                  <span class="label label-info">{{ $respo->first_name.' '.$respo->last_name }}</span>
                              @endforeach
                            </td>
                            <td>
                                <a class="btn btn-xs btn-warning" href="{{ url('dashboard/permType/edit/'.$permType->id) }}">Modifier</a>
                                <form action="{{ url('dashboard/permType/'.$permType->id) }}" method="post">
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
