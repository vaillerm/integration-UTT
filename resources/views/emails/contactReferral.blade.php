@extends('emails.master_layout')

@section('title')
@if($newcomer->sex)
	Ta fillotte souhaite que tu la contactes !
@else
	Ton fillot souhaite que tu le contactes !
@endif
@endsection

@section('content')
	<p>
		Bonjour {{$referral->first_name}},<br/>
		{{($newcomer->sex?'Ta fillotte':'Ton fillot')}} a demandé à ce que tu le contactes.
		Il te transmet donc ses informations de contact :
	</p>
	<p style="text-align:center;">
		<strong>{{$newcomer->first_name}} {{$newcomer->last_name}}</strong><br/>
		{{$newcomer->phone}}<br/>
		{{$newcomer->email}}
	</p>
	<p>
		Envoie lui rapidement un message pour pouvoir répondre à toutes ses questions. :)
	</p>
@endsection
