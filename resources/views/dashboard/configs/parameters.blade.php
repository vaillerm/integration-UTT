@extends('layouts.dashboard')

@section('title')
    Paramètres
@endsection

@section('smalltitle')
    Edition des paramètres par défaut
@endsection

@section('content')

    <div class="callout callout-info">
        <h4>Informations</h4>
        <p>
            Cette page vous permet d'interagir avec l'ensemble des paramètres disponibles. La fonctionnalité est réservée à un public averti ! La modification n'étant pas anodine.
        </p>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Liste des paramètres</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody>
                <tr>
                    <th>Nom</th>
                    <th>Valeur</th>
                    <th>Action</th>
                </tr>
                @foreach ($configs as $key=>$value)
                    <tr>
                        <td>{{ $key }}</td>
                        <td> @if(is_string($value))
                            {{ $value }}
                                 @else
                                 <i>null</i>
                                 @endif
                        </td>
                        <td><a href="{{ route('dashboard.configs.parameters.edit', ['settings_name' => $key]) }}" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-pencil"></i></a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
