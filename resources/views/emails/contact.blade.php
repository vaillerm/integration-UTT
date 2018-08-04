@extends('layouts.email')

@section('title')
	Message d'{{ $userStatus }}
@endsection

@section('content')
	<p>
		<strong>{{ $name }}</strong>,
		{{ $userStatus }} {!! $user ? 'de <strong>'.$user->branch.'</strong> (<a href="'.route('dashboard.students.edit', ['id' => $user->id]).'">profil</a>)' : ''!!}, vous a envoyé un message. Vous pouvez lui répondre à <a href="mailto:{{$email}}">{{$email}}</a>.
	</p>
	<div style="border-bottom: solid #eee 1px;"/></div>
	<p>
		{!! nl2br(e($text)) !!}
	</p>
@endsection
