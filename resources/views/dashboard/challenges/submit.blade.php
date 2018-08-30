@extends('layouts.auto')

@section('title')
    Valider un défis
@endsection

@section("smalltitle")
    Faire valider la réussite de ton équipe par un admin 😀
@endsection

@section("content")
    <div class="box box-default">
        <div class="box-header with-border">
            <h2>Demander une validation pour : {{$challenge->name}}</h2>
            <h3>{{ $challenge->description }}</h3>
        </div>
        <form action="{{ route("validation.create",[
            "teamId" => Auth::user()->team_id,
            "challengeId" => $challenge->id,
        ])  }}" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="file">Preuve de la réussite</label>
            <input id="file" name="proof" class="form-control-file" type="file" accept="image/*">
        </div>
        <input class="btn btn-primary form-control" type="submit" value="Envoyer">
        </form>
    </div>
@endsection
