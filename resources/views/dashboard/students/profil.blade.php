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
                    <span>En devenant bénévole, vous serez ajouté à la liste d'email à contacter pour demander des coups de main  pour l'intégration (stupre-liste).<br/>
                        Si tu veux donner plus qu'un coup de main pour l'intégration et que tu es dispo fin août, envoi un email à <a href="mailto:integration@utt.fr">integration@utt.fr</a>. Promis, on est gentil :)</span>
                    <select id="volunteer" name="volunteer" class="form-control" class="">()
                        <option value="0" @if ($student->volunteer == 0) selected="selected" @endif >Je ne veux pas être bénévole :/</option>
                        <option value="1" @if ($student->volunteer == 1) selected="selected" @endif >Je suis bénévole et chaud pour cette intégration !</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="volunteer" class="col-lg-2 control-label">Esprit intégration</label>
                <div class="col-lg-10">
                    <p>
                        Le but de l'intégration est de faire découvrir aux nouveaux le monde étudiant, le fonctionnement et les locaux de l'école,
                        de faire former des groupes de potes chez les nouveaux, mais aussi et surtout d'<strong>envoyer du rêve</strong>.
                        Cette intégration, nous l'organisons <strong>pour les nouveaux et pas contre eux</strong>.
                    </p>
                    <p>
                        En étant bénévole pour cette intégration, vous aurez automatiquement une sorte de superiorité vis-à-vis des nouveaux.
                        C'est normal, vous avez déjà passé un peu de temps dans cette école et donc vous en savez plus.
                        Cependant, c'est dans cette situation que vous pouvez être amené à <strong>bizuter</strong>, <strong>sans même parfois vous en rendre compte</strong>.</p>
                    </p>

                    <ul>
                        <li>Si un nouveau ne veut pas faire quelquechose, vous ne devez pas insister pour qu'il le fasse.</li>
                        <li>Ce n'est pas parce qu'on vous l'a fait à votre intégration que c'est acceptable.</li>
                        <li>Si vous êtes témoins de bizutage pendant l'intégration, ou que vous avez un doute si quelquechose est acceptable, contactez l'un des organisateur de l'intégration.</li>
                        <li>Pendant l'intégration, nous vous communiquerons un numéro d'urgence permettant de joindre 24h/24 un responsable de l'intégration.
                    </ul>

                    <p><strong>Le bizutage est un <em>délit</em>, puni par la <em>loi</em>. Ceux qui le pratiquent risquent des peines de prison ou de fortes amendes.
                        Ils seront immédiatement et définitivement exclus de l’École sans attendre les poursuites judiciaires éventuelles.</strong></p>

                    <div class="well" style="font-family:Courier New, Courier, monospace">
                        <p>Extrait de la loi n°98-468 du 17 juin 1998<br/>
                        Section 3 bis – Du bizutage</p>

                        <p>«&nbsp;Article 225-16-1. Hors les cas de violences, de menaces ou d'atteintes sexuelles,
                        le fait pour une personne d'amener autrui, contre son gré ou non,
                        à subir ou à commettre des actes humiliants ou dégradants lors de manifestations ou
                        de réunions liées aux milieux scolaire et socio-éducatif est puni
                        de six mois d'emprisonnement et de 7 500 euros d'amende.&nbsp;»</p>

                        <p>«&nbsp;Article 225-16-2. L'infraction définie à l'article 225-16-1 est punie d'un an
                        d'emprisonnement et de 15 000 euros d'amende lorsqu'elle est commise sur
                        une personne dont la particulière vulnérabilité, due à son âge, à une maladie, à
                        une infirmité, à une déficience physique ou psychique ou à un état de
                        grossesse, est apparente ou connue de son auteur.&nbsp;»</p>

                        <p>«&nbsp;Article 225-16-3. Les personnes morales déclarées responsables pénalement, dans
                        les conditions prévues parl'article 121-2, des infractions définies aux
                        articles 225-16-1 et 225-16-2encourent, outre l'amende suivant les modalités
                        prévues par l'article 131-38, les peines prévues par les 4° et 9° del'article 131-39.&nbsp;»</p>
                    </div>
                    <select id="convention" name="convention" class="form-control" class="">()
                        <option value="0" selected="selected" >Je refuse de suivre l'esprit de cette intégration.</option>
                        <option value="1">Je comprend l'objectif de l'intégration et je comprend que mes actions peuvent être puni par une peine d'emprisonnement et 15 000€ d'amende.</option>
                    </select>
                </div>
            </div>

            <input type="submit" class="btn btn-success form-control" value="Valider !">
        </form>
    </div>
</div>


@endsection
