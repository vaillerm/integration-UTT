@extends('layouts.dashboard')

@section('title')
<h1>
    Équipes
    <small>Gestion des équipes</small>
</h1>
@endsection

@section('content')

@include('display-errors')

<div class="callout callout-danger">
    <h4>Suppression ou modification des équipes</h4>
    <p>
        Ne <b>PAS</b> modifier ou supprimer les équipes une fois que les nouveaux
        ont reçu leurs parrains. <b>Contactez-moi directement si vous avez des modifications
        à effectuer !</b>
    </p>
</div>

<div class="callout callout-info">
    <h4>Création d'équipes</h4>
    <p>
        Merci de redimensionner (<b>210</b>px par <b>180</b>px) les images avant de les mettre en ligne (par exemple sur <a href="http://imgur.com">imgur</a>).<br>
        Et il faut cliquer sur le petit <i class="fa fa-plus"></i> :-)
    </p>
</div>

<div class="box box-default collapsed-box">
    <div class="box-header with-border">
        <h3 class="box-title">Ajouter une équipe</h3>
        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
    </div>
    <div class="box-body text-center">
        <form class="" action="{{ route('dashboard.teams') }}" method="post">
            <input type="text" name="name" value="" class="form-control text-center" placeholder="Nom de l'équipe" required>
            <input type="text" name="img_url" value="" class="form-control text-center" placeholder="Lien DIRECT vers l'image (http://imgur.com/______.png)">
            <textarea name="description" class="form-control"  placeholder="Message de l'équipe (max 500 caractères)." cols="30" rows="10"></textarea>
            <input type="submit" class="btn btn-success form-control" value="Ajouter">
        </form>
    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Liste des équipes</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
                @foreach ($teams as $team)
                    <tr>
                        <td>{{{ $team->name }}}</td>
                        <td>{{{ $team->description }}}</td>
                        @if ($team->img_url)
                            <td><a href="{{ $team->img_url }}"><img src="{{ $team->img_url }}" width="100px" height="100px"></a></td>
                        @else
                            <td>Aucune !</td>
                        @endif
                        <td>
                            <a href="{{ route('dashboard.teams.members', ['id' => $team->id ]) }}" class="btn btn-xs btn-success">Membres</a>
                            <a href="{{ route('dashboard.teams.edit', ['id' => $team->id ]) }}" class="btn btn-xs btn-warning">Modifier</a>
                            <a href="{{ route('dashboard.teams.destroy', ['id' => $team->id ]) }}" class="btn btn-xs btn-danger">Supprimer</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
