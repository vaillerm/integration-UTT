@extends("layouts.auto")

@section("title")
    LE CLASSEMENT
@endsection

@section("smalltitle")
    C'EST LE CLAAAAAASSEMENT
@endsection

@section("content")
    @if (Auth::user()->isAdmin())
        <div class="box-header with-border">
            <h3 class="box-title">Ajouter une faction</h3>
            <a href="{{ route('faction.create') }}" class="btn btn-box-tool">
                <i class="fa fa-plus"></i>
            </a>
        </div>
    @endif

    <div class="box box-default">
        <table class="table">
            <thead>
                <tr>
                    <th>Faction</th>
                    <th>Points</th>
                    @if (Auth::user()->isAdmin())
                        <th> Actions </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($factions as $faction) 
                    <tr>
                        <td>{{ $faction->name }}</td>
                        <td>{{ $faction->score() }}</td>
                        @if (Auth::user()->isAdmin())
                        <!-- I've got no idea what property I should choose/change for this to be clean, so meanwhile... -->
                            <td style="width: 15%;"> 
                                <a href="{{ route('faction.edit', ['id' => $faction->id]) }}" class="btn btn-xs btn-warning">Modifier</a>
                             </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
