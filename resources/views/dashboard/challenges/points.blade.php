@extends("layouts.auto")

@section("title")
    Ajouter des points
@endsection

@section("smalltitle")
    Une équipe est particulièrement efficace ? DONNE LES POINTS !!!
@endsection

@section("content")
    <div class="box box-default">
        <form action="{{ route("points.add") }}" method="post">
            <div class="form-group">
                <label for="rsn" >Motif</label>
                <input id="rsn" name="reason" class="form-control" type="text">
            </div>
            <div class="form-group">
                <label for="pts">Nombre de points</label>
                <input id="pts" class="form-control" type="number" name="amount">
            </div>
            <div class="form-group">
                <label for="team">Équipe</label>
                <select class="form-control" id="team" name="team_id">
                    @foreach($teams as $id => $team)
                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="name">Auteur</label>
                <input id="name" class="form-control" name="don't try to edit dis shit, it just won't work lmfao" type="text" value="{{ Auth::user()->first_name." ".Auth::user()->last_name }}" disabled>
            </div>

            <input class="form-control btn btn-success" type="submit" value="Ajouter">
        </form>
        <h1>Historique</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Auteur</th>
                    <th>Nombre</th>
                    <th>Équipe</th>
                    <th>Raison</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                @forelse($points as $point)
                    <tr>
                        <td>{{ $point->created_at }}</td>
                        <td>{{ $point->author->first_name}} {{  $point->author->last_name }}</td>
                        <td>{{ $point->amount }}</td>
                        <td>{{ $point->team->name }}</td>
                        <td>{{ $point->reason }}</td>
                        <td>
                            <form method="post" action="{{route("points.delete", ["id" => $point->id])}}">
                                <input class="btn btn-danger" type="submit" value="Supprimer">
                                {{ method_field("delete") }}
                            </form>
                        </td>
                    </tr>
                @empty
                    Aucun point bonus n'a été donné.
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
