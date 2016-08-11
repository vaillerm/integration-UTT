@extends('layouts.email')

@section('title')
	Message d'un nouveau
@endsection

@section('content')
	<p>
		<strong>{{$newcomer->first_name}} {{$newcomer->last_name}}</strong>,
		un nouveau qui entre en <strong>{{$newcomer->branch}}</strong>, vous a envoyé un message. Vous pouvez lui répondre à <a href="mailto:{{$email}}">{{$email}}</a>.
	</p>
	<div style="border-bottom: solid #eee 1px;"/></div>
	<p>
		{!! nl2br(e($text)) !!}
	</p>
@endsection
