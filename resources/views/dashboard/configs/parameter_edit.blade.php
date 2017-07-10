@extends('layouts.dashboard')

@section('title')
    Paramètres
@endsection

@section('smalltitle')
    Edition d'un paramètre
@endsection

@section('content')
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Edition</h3>
        </div>
        <div class="box-body">
            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label for="student_id" class="col-lg-2 text-right">Nom</label>
                    <div class="col-lg-10">
                        {{ $name }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="student_id" class="col-lg-2 text-right">Valeur</label>
                    <div class="col-lg-10">
                        <input class="form-control" type="text" name="value" id="value" value="{{ Config::get($name) }}">
                    </div>
                </div>
                <input type="submit" class="btn btn-success form-control" value="Valider" />
            </form>
        </div>
    </div>
@endsection
