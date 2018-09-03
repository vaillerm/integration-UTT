@extends("layouts.auto")

@section("title")
    LE CLASSEMENT
@endsection

@section("smalltitle")
    C'EST LE CLAAAAAASSEMENT
@endsection

@section("content")
    <div class="box box-default">
        <table class="table">
            <thead>
                <tr>
                    <th>Faction</th>
                    <th>Points</th>
                </tr>
            </thead>
            <tbody>
                @foreach($factions as $faction) 
                    <tr>
                        <td>{{ $faction->name }}</td>
                        <td>{{ $faction->score() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
