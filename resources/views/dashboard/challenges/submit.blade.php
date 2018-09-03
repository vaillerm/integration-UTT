@extends('layouts.auto')

@section('title')
    Valider un défi
@endsection

@section('smalltitle')
    Faire valider la réussite de ton équipe par un admin 😀
@endsection

@section('js')
    <script>
        let activated = false;
$('#proofTypeToggle').click(() =>{
    let button = $('#proofTypeToggle')
    let label = $('#proofLabel')
    switch(activated) {
        case false:
            activated=true;
            $('#proof').attr('type', 'text')
            $('#proof').attr('name', 'urlProof');
            button.text("Naan une photo c'est cool 📷")
            label.text('Url de la vidéo (google drive uniquement)')
            break;
        case true: 
            activated=false;
            $('#proof').attr('type', 'file')
            $('#proof').attr('name', 'picProof');
            label.text('Photo au format jpg, png, etc..')
            button.text('Publier une prouesse en vidéo 🙈 ')
            break;
    }
})
    </script>
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
            <label id="proofLabel" for="proof">Photo au format jpg, png, etc..</label>
            <input id="proof" name="picProof" class="form-control-file" type="file" accept="image/*">
        </div>

        <div class="form-group">
            <button id="proofTypeToggle" type='button' class='btn btn-primary'>Publier une prouesse en vidéo 🙈 </button>
        </div>
        <input class="btn btn-primary form-control" type="submit" value="Envoyer">
        </form>
    </div>
@endsection
