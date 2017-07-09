@extends('layouts.newcomer')

@section('title')
{{ (Auth::user()->godFather && Auth::user()->godFather->sex)?'Ta marraine':'Ton parrain' }}
@endsection

@section('smalltitle')
La personne qui te guidera tout au long de ta vie à l'UTT
@endsection

@section('content')
<div class="box box-default">

    @if(!Auth::user()->godFather)
        <div class="box-header with-border">
            <h3 class="box-title">Pourquoi avoir un parrain ?</h3>
        </div>
        <div class="box-body">
        <p>
            Ton parrain est un étudiant de l'UTT depuis un an ou plus, il a donc lui aussi vécu ce que tu vis actuellement.
            Il peut répondre à toutes tes questions : où est-ce qu'on mange le midi, est-ce qu'un cours est difficile, etc.
            Mais surtout il peut t'accueillir dès ton arrivé à Troyes, c'est toujours sympa de passer un peu de temps avec son parrain avant la rentrée !
        </p>
        <p>
            <strong>Tu rencontrera ton parrain à la rentrée lors de la cérémonie du parrainage le premier jour :)</strong>
        </p>
        <div class="text-center">
            <a class="btn btn-primary" href="{{{ route('newcomer.'.Auth::user()->getNextCheck()['page']) }}}">Prochaine action à faire<br/><strong>{{{ Auth::user()->getNextCheck()['action'] }}}</strong></a>
        </div>
    @else
        <div class="box-header with-border">
            <h3 class="box-title">{{ Auth::user()->godFather->first_name }} {{ Auth::user()->godFather->last_name }}, {{ (Auth::user()->godFather->sex)?'ta marraine':'ton parrain' }} !</h3>
        </div>
        <div class="box-body">
            <div class="thumbnail col-lg-2">
                <img src="{{ asset('/uploads/students-trombi/'.Auth::user()->referral_id.'.jpg') }}" alt="Photo"/>
            </div>
                <div class="caption col-lg-10">
                    <div style="position:relative;margin-bottom:5px;">
                        <i class="fa fa-mobile" aria-hidden="true"  style="position:absolute;top:3px;left:3px;vertical-align:bottom;"></i>
                        <span style="margin-left:25px;text-align:justify;">{{ Auth::user()->godFather->phone }}</span>
                    </div>
                    <div style="position:relative;margin-bottom:5px;">
                        <i class="fa fa-at" aria-hidden="true" style="position:absolute;top:3px;vertical-align:bottom;"></i>
                        <span style="margin-left:25px;text-align:justify;">{{ Auth::user()->godFather->email }}</span>
                    </div>
                    @if(substr(Auth::user()->godFather->facebook, 0, 4) == 'http')
                        <div style="position:relative;margin-bottom:5px;">
                            <i class="fa fa-facebook" aria-hidden="true" style="position:absolute;top:3px;vertical-align:bottom;"></i>
                            <span style="margin-left:25px;text-align:justify;"><a href="{{ Auth::user()->godFather->facebook }}">Profil facebook</a></span>
                        </div>
                    @endif
                    <div style="position:relative;margin-bottom:5px;">
                        <i class="fa fa-comment" aria-hidden="true" style="position:absolute;top:3px;vertical-align:bottom;"></i>
                        <p style="margin-left:25px;margin-right:5px;text-align:justify;">{!! nl2br(e(Auth::user()->godFather->referral_text)) !!}</p></div>
                </div>
                <hr style="margin-top:0px;"/>
                <div class="clearfix"></div>
                <h4>Mais pourquoi avoir un parrain ?</h4>
                <p>
                    Ton parrain est un étudiant de l'UTT depuis un an ou plus, il a donc lui aussi vécu ce que tu vis actuellement.
                    Il peut répondre à toutes tes questions : où est-ce qu'on mange le midi, est-ce qu'un cours est difficile, etc.
                    Mais surtout il peut t'accueillir dès ton arrivé à Troyes, c'est toujours sympa de passer un peu de temps avec son parrain avant la rentrée !
                </p>
                <p>
                    Cependant, ton parrain ne sait pas qui tu es, il sait juste qu'il a un fillot ! C'est donc à tois de faire le premier pas et de lui
                    envoyer un doux message. Pas besoin de faire compliqué, un simple <em>&laquo;&nbsp;Salut, je suis ton fillot !&nbsp;&raquo;</em> suffira ;)
                </p>
                <hr/>

                <div class="text-center">
                @if(Auth::user()->isChecked('referral'))
                    <h4 id="question">Félécitation, tu as pris contact avec {{ (Auth::user()->godFather->sex)?'ta marraine':'ton parrain' }} !</h4>
                    <a href="{{ route('newcomer.referral', [ 'step' => 'cancel']) }}#question" class="btn btn-danger">Ce n'est pas le cas ?</a>
                    <div class="text-center">
                        <a class="btn btn-primary" href="{{{ route('newcomer.'.Auth::user()->getNextCheck()['page']) }}}">Prochaine action à faire<br/><strong>{{{ Auth::user()->getNextCheck()['action'] }}}</strong></a>
                    </div>
                @elseif($step == 'contacted')
                    <h4 id="question">As-t-il répondu ?</h4>
                    <a href="{{ route('newcomer.referral', [ 'step' => 'answered']) }}#question" class="btn btn-primary">Oui</a>
                    <a href="{{ route('newcomer.referral', [ 'step' => 'notAnswered']) }}#question" class="btn btn-danger">Non</a>
                @elseif($step == 'notAnswered')
                    <h4 id="question">Ah :/</h4>
                    <p>{{ (Auth::user()->godFather->sex)?'Ta marraine':'Ton parrain' }} est peut-être en vacances ou à l'étranger..</p>
                    <p>Tente de {{ (Auth::user()->godFather->sex)?'la':'le' }} contacter par tous les moyens disponibles : sms, email, facebook..</p>
                    @if(!Auth::user()->referral_emailed)
                        <p>Si tu le souhaites, nous pouvons lui envoyer tes coordonées par email, pour qu'{{ (Auth::user()->godFather->sex)?'elle':'il' }} puisse te contacter dès que possible.</p>
                        <div class="row">
                            <div class="col-lg-6 col-lg-offset-3">
                                <form action="{{ route('newcomer.referral.submit') }}"method="post">
                                    <input class="form-control" name="email" id="email" placeholder="Email" type="text" value="{{{ old('email') ?? Auth::user()->email }}}">
                                    <input class="form-control" name="phone" id="phone" placeholder="Numéro de téléphone" type="text" value="{{{ old('phone') ?? Auth::user()->phone }}}">
                                    <input class="form-control btn btn-primary" type="submit" value="Envoyer mes coordonées à mon parrain">
                                </form>
                            </div>
                        </div>
                    @else
                        <em>Un email avec tes coordonées a déjà été envoyé à {{ (Auth::user()->godFather->sex)?'ta marraine':'ton parrain' }}.</em>
                    @endif
                @elseif($step == 'notContacted')
                    <h4 id="question">Bah alors ?</h4>
                    <p>Essaye de lui envoyer un petit SMS, email ou un message Facebook rapidement !</p>
                    @if(!Auth::user()->referral_emailed)
                        <p>Si tu le souhaites, nous pouvons aussi lui envoyer tes coordonées par email, pour qu'{{ (Auth::user()->godFather->sex)?'elle':'il' }} puisse te contacter de lui même.</p>
                        <div class="row">
                            <div class="col-lg-6 col-lg-offset-3">
                                <form action="{{ route('newcomer.referral.submit') }}"method="post">
                                    <input class="form-control" name="email" id="email" placeholder="Email" type="text" value="{{{ old('email') ?? Auth::user()->email }}}">
                                    <input class="form-control" name="phone" id="phone" placeholder="Numéro de téléphone" type="text" value="{{{ old('phone') ?? Auth::user()->phone }}}">
                                    <input class="form-control btn btn-primary" type="submit" value="Envoyer mes coordonées à mon parrain">
                                </form>
                            </div>
                        </div>
                    @else
                        <em>Un email avec tes coordonées a déjà été envoyé à ton parrain.</em>
                    @endif
                @else
                    <h4 id="question">As-tu déjà essayé de contacter {{ (Auth::user()->godFather->sex)?'ta marraine':'ton parrain' }} ?</h4>
                    <a href="{{ route('newcomer.referral', [ 'step' => 'contacted']) }}#question" class="btn btn-primary">Oui</a>
                    <a href="{{ route('newcomer.referral', [ 'step' => 'notContacted']) }}#question" class="btn btn-danger">Non</a><br/>
                    <a href="{{ route('newcomer.referral', [ 'step' => 'answered']) }}#question" class="btn btn-success">C'est lui qui m'a contacté</a>
                @endif
                </div>
        </div>
    @endif
</div>
@endsection
