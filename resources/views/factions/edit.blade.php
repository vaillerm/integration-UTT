@extends('layouts.dashboard')

@section('title')
Équipes
@endsection

@section('smalltitle')
Gestion des équipes
@endsection

@section('content')

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Modification de la faction
            @if($faction->name != null)
            <strong>{{{ $faction->name }}}</strong></a>
            @else
            <strong>Faction sans nom {{{ $faction->id }}}</strong></a>
            @endif
        </h3>
    </div>
    <div class="box-body text-center">
        <form class="form-horizontal" action="{{ route('faction.edit.submit', $faction->id) }}" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name" class="col-lg-2 control-label">Nom de la faction</label>
                <div class="col-lg-10">
                    <input class="form-control" type="text" id="name" name="name" value="{{{ old('name') ?? $faction->name }}}">
                </div>
            </div>
            <input type="submit" class="btn btn-success form-control" value="Mettre à jour les informations" />
        </form>
    </div>
</div>
@endsection
