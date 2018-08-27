@extends('layouts.auto')

@section('title')
    Défis
@endsection

@section("smalltitle")
    La liste de tous les défis.
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Liste des défis</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover trombi">
                <tbody>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Solo ?</th>
                        <th>nombre de points</th>
                        <th>deadline</th>
                    </tr>

                    @foreach($challenges as $challenge)
                        <tr>
                            <td>{{ $challenge->name }}</td>
                            <td>{{ $challenge->description }}</td>
                            <td>
                                @if($challenge->for_newcomer)
                                    <span class="label label-success">oui</span>
                                @else
                                    <span class="label label-warning">non</span>
                                @endif
                            </td>
                            <td>{{ $challenge->points }}</td>
                            <td>{{ $challenge->deadline }}</td>
                            <td>
                                @if(Auth::user()->admin)
                                    <form action={{ route("challenges.delete", ["id"=>$challenge->id]) }} method="POST">
                                        {{ method_field('DELETE') }}
                                        <input class="btn btn-danger btn-xs" type="submit" value="Supprimer">
                                    </form>
                                    <div class="btn-group" role="group">
                                        <a href={{ route("challenges.modifyForm", ["challengeId" => $challenge->id]) }}><button class="btn btn-xs btn-primary">Modifier</button></a>
                                    @endif
                                    @if($team != null)
                                        @if($challenge->teamValidable($team) || $challenge->newComerValidable(Auth::user()))
                                            <a href={{ route("challenges.submitForm", ["id" => $challenge->id]) }}><button class="btn btn-xs btn-primary">valider un défis</button></a>
                                        @endif
                                    @endif
                                    </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
