@extends("layouts.auto")

@section("title")
    Ajouter des points
@endsection

@section("smalltitle")
    Une équipe est particulièrement efficace ? DONNE LES POINTS !!!
@endsection

@section("content")
    <div class="box box-default">
        <form action="" method="post">
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
        </form>
    </div>
@endsection
