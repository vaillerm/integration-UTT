@extends('layouts.dashboard')

@section('title')
Mon profil bénévole
@endsection

@section('smalltitle')
Parce que l'intégration, c'est surtout vous !
@endsection

@section('content')

@include('display-errors')



<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Modification de mon profil</h3>
    </div>
    <div class="box-body">
        <form class="form-horizontal" action="{{ route('dashboard.students.profil') }}" method="post">

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label for="name" class="col-lg-2 control-label">Nom complet</label>
                <div class="col-lg-10">
                        <input class="form-control" type="text" id="name" name="name" disabled value="{{{ $student->first_name . ' ' . $student->last_name }}}">
                </div>
            </div>

            <div class="form-group">
                <label for="surname" class="col-lg-2 control-label">Surnom</label>
                <div class="col-lg-10">
                    <input class="form-control" type="text" name="surname" id="surname" value="{{{ $student->surname }}}">
                </div>
            </div>

            <div class="form-group">
                <label for="sex" class="col-lg-2 control-label">Sexe</label>
                <div class="col-lg-10">
                    <select id="sex" name="sex" class="form-control" class="">()
                        <option value="0" @if ($student->sex == 0) selected="selected" @endif >Homme</option>
                        <option value="1" @if ($student->sex == 1) selected="selected" @endif >Femme</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="col-lg-2 control-label">Email</label>
                <div class="col-lg-10">
                    <input class="form-control" name="email" id="email" placeholder="Adresse email" type="text" value="{{{ $student->email }}}">
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-lg-2 control-label">Portable</label>
                <div class="col-lg-10">
                    <input class="form-control" name="phone" id="phone" placeholder="Portable" type="text" value="{{{ $student->phone }}}">
                </div>
            </div>


            <div class="form-group">
                <label for="semester" class="col-lg-2 control-label">Semestre en cours</label>
                <div class="col-lg-10">
                    <input class="form-control" name="semester" id="semester" type="text" value="{{{ $student->branch . $student->level }}}" disabled>
                </div>
            </div>

            <div class="form-group">
                <label for="volunteer" class="col-lg-2 control-label">Bénévole</label>
                <div class="col-lg-10">
                    <select id="volunteer" name="volunteer" class="form-control" class="">()
                        <option value="0" @if ($student->volunteer == 0) selected="selected" @endif >Je ne veux pas être bénévole :/</option>
                        <option value="1" @if ($student->volunteer == 1) selected="selected" @endif >Je suis bénévole et chaud pour cette intégration !</option>
                    </select>
                    <span>En devenant bénévole, vous serez ajouté à la liste d'email à contacter pour demander des coups de main  pour l'intégration (stupre-liste).<br/>
                        Si tu veux donner plus qu'un coup de main pour l'intégration et que tu es dispo fin août, envoi un email à <a href="mailto:integration@utt.fr">integration@utt.fr</a>. Promis, on est gentil :)</span>
                </div>
            </div>

            <input type="submit" class="btn btn-success form-control" value="Valider !">
        </form>
    </div>
</div>


@endsection
