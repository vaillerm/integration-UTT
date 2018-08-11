@extends('layouts.auto')

@section('title')
Contacter l'équipe d'intégration
@endsection

@section('smalltitle')
Une question, une remarque, un mot d'amour...
@endsection

@section('content')
<div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">Envoi d'un message</h3>
        </div>
        <div class="box-body">
        <form class="form-horizontal" action="{{ route('contact') }}" method="post">
                <p class="text-center">
                    Si tu as la moindre question, remarque ou autre message, n'hésite pas !<br/>Envoie-nous un petit message, on mord pas, on te répondra par email ;)
                </p>

                <div class="form-group">
                    <label for="name" class="col-lg-2 control-label">De la part de</label>
                    <div class="col-lg-10">
                        <input class="form-control" type="text" id="name" placeholder="Prénom NOM" name="name" {{ Auth::user() ? 'disabled' : '' }} value="{{{ old('email') ?? (Auth::user() ? Auth::user()->first_name . ' ' . Auth::user()->last_name : '') }}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-lg-2 control-label">Mail</label>
                    <div class="col-lg-10">
                        <input class="form-control" name="email" id="email" placeholder="mail@domain.tld" type="text" value="{{{ old('email') ?? Auth::user()->email ?? '' }}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-lg-2 control-label">Message</label>
                    <div class="col-lg-10">
                        <textarea class="form-control" name="message" id="message" rows="10" placeholder="Bonjour,">{{{ old('message') }}}</textarea>
                    </div>
                </div>

                <input type="submit" class="btn btn-success form-control" value="Valider !">
        </div>
</div>
@endsection
