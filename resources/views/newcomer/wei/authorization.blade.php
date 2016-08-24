@extends('layouts.newcomer')

@section('title')
Autorisation parentale
@endsection

@section('smalltitle')
Le Week-End d'Intégration
@endsection

@section('content')
		<div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Tu as besoin d'une autorisation parentale</h3>
            </div>
		    <div class="box-body text-center">
                <p>Comme tu es mineur, nous avons besoin de l'autorisation de tes parents pour pouvoir t'enmener dans une base nautique a 15 minutes de troyes le jeudi et au Week-End.</p>
                <p>Télécharge l'autorisation parentale, fait la signer et met là dans ton sac pour ne pas l'oublier !</p>
                <a href="{{@asset('docs/autorisation.png')}}" class="btn btn-primary">Télécharger l'autorisation parentale</a><br/>
                <br/>

					Il y aura un stand dans l'UTT à la rentrée pour que tu puisses nous la donner ;-)<br/>
					<a href="{{ route('newcomer.wei')}}" class="btn btn-success">C'est bon ! L'autorisation est signée et déjà dans le sac !</a>
				<p>
			</div>
        </div>
@endsection
