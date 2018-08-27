@extends("layouts.auto")

@section("title")
    Défis envoyés
@endsection

@section("smalltitle")
    La liste des défis réalisé par l'équipe
@endsection

@section("content")
    <div class="box box-default">
        <h1>Score : {{ $score }}</h1>
        <table class="table">
            <thead>
                <tr >
                    <td scope="col">Nom</td>
                    <td scope="col">Statut</td>
                    <td scope="col">Envoyé par</td>
                    <td scope="col">Message</td>
                    @if(Auth::user()->ce)
                        <td scope="col">Action</td>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($validations as $validation)
                    <tr scope="row">
                        <td>{{ $validation->challenges()->first()->name }}</td>
                        <td class="{{ $validation->prettyStatus()["css"] }}">{{ $validation->prettyStatus()["content"] }}</td>
                        <td>{{$validation->user()->first()->first_name}}</td>
                        <td>{{ $validation->message }}</td>
                        <td>
                            @if($validation->validated == -1 && ($validation->challenges()->first()->teamValidable(Auth::user()->team()->first()) ||
                                $validation->challenges()->first()->newcomerValidable(Auth::user())))
                                <a href="{{ route("challenges.submitForm", ["id" => $validation->challenge_id]) }}"><button class="btn btn-primary">Réessayer !</button></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
