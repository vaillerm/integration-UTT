@extends('layouts.dashboard')

@section('title')
    Assignations aux bus
@endsection

@section('smalltitle')
    Liste des équipex
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Liste</h3>
        </div>
        <form action="" method="post">
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover trombi">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Nombre de personnes inscrites au WEI</th>
                    <th>Numero du bus</th>
                </tr>
                @foreach ($teams as $team)
                    <tr>
                        <td>{{ $team->id }}</td>
                        <td>{{ $team->name }}</td>
                        <td>{{ $team->newcomers->where('wei', true)->count() }} nouveaux - {{ $team->ce->where('wei', true)->count() }} ce</td>
                        <td><input type="number" class="form-control" name="{{ $team->id }}" value="{{ old($team->id) }}" size="2"></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
            <div class="box-body">
                <div class="col-md-12">
                    <input type="submit" class="btn btn-success form-control" value="Let's do it !" />
                </div>
            </div>
        </form>
    </div>
@endsection
