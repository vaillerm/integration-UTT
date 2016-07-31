@extends('layouts.email')

@section('title')
@if($newcomer->sex)
	Ta fillotte souhaite que tu la contacte !
@else
	Ton fillot souhaite que tu le contacte !
@endif
@endsection

@section('content')
	<p>
		Bonjour {{$referral->first_name}},<br/>
		{{($newcomer->sex?'Ta fillotte':'Ton fillot')}} a demandé à ce que tu {{($newcomer->sex?'la':'le')}} contacte. {{($newcomer->sex?'Elle':'Il')}}
		te transmet donc ses informations de contact :
	</p>
	<p style="text-align:center;">
		<strong>{{$newcomer->first_name}} {{$newcomer->last_name}}</strong><br/>
		{{$newcomer->phone}}<br/>
		{{$newcomer->email}}
	</p>
	<p>
		Envoi lui rapidement un message pour pouvoir répondre à toute ses questions :)
	</p>
@endsection
