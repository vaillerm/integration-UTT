@extends('layouts.auto')

@section('title')
Trombi
@endsection

@section('smalltitle')
Des orgas tout plein
@endsection

@section('content')
<div class="box box-default">
    <div class="box-body table-responsive">
        <div class="row">
            @foreach ($users as $user)
                @if (($lastMission ?? '') != $user->mission)
                    </div>
                    <h3 class="clearfix">{{ $user->mission }}</h3>
                    <div class="row">
                @endif
                <?php $lastMission = $user->mission ?>
                <div class="col-sm-3 col-md-2 col-xs-6">
                    <div class="thumbnail">
                        <img src="{{ asset('/uploads/students-trombi/'.$user->student_id.'.jpg') }}" alt="Photo"/>
                        <div class="caption">
                            <h4 style="margin: 0 auto">
                                {{{ $user->first_name . ' ' . $user->last_name }}}<br/>
                                <small>{{ $user->surname }}</small>
                            </h4>
                            <i class="fa fa-mobile" aria-hidden="true"></i> <img src="{{ route('trombi.phone', ['id' => $user->id ]) }}" alt="phone" />
                            @if(substr($user->facebook, 0, 4) == 'http')
                                <br/><i class="fa fa-facebook" aria-hidden="true"></i> <a href="{{ $user->facebook }}">Profil Facebook</a>
                            @endif
                            @if(Auth::user() && Auth::user()->isAdmin())
                                <br/><a class="btn btn-xs btn-warning" href="{{ route('dashboard.students.edit', [ 'id' => $user->id ])}}">Modifier</a>
                            @endif
                            @if($user->mission_respo)
                                <span class="label label-info" style="font-size:1em">Respo</span><br/>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
