@extends('layouts.auto')

@section('title')
Trombi
@endsection

@section('smalltitle')
Des orgas tout plein
@endsection


@section('css')
<style>
    .trombi {
        text-align: center;
        width: 100%;
    }

    .trombi__mission {
        display: inline-block;
        border-radius: 3px;
        background: #ffffff;
        border-top: 3px solid #d2d6de;
        margin: 8px
    }

    .trombi__mission h3 {
        margin-left: 5px;
        margin-right: 5px;
    }

    .trombi_thumbnail-container {
        width: 120px;
        margin: 5px;
        display: inline-block;
        vertical-align: top;
    }

    .trombi_thumbnail-container .thumbnail {
        padding: 2px;
        text-align: left;
        overflow: hidden;
    }

    .trombi_thumbnail-container h4 {
        margin: 0 auto;
    }

    .trombi_thumbnail-container .label {
        font-size: 1em;
    }
</style>
@endsection

@section('content')
<div class="trombi">
    <div>
        @foreach ($roles as $role)
            @if ($role->assignedUsers->count() > 0)
            <div class="trombi__mission">
                <h3>{{ $role->name }}</h3>
                @foreach ($role->assignedUsers as $user)
                    <div class="trombi_thumbnail-container">
                        <div class="thumbnail">
                            <img src="{{ asset('/uploads/students-trombi/'.$user->student_id.'.jpg') }}" alt="Photo" />
                            <div class="caption">
                                <h4>
                                    {{{ $user->first_name . ' ' . $user->last_name }}}<br />
                                    <small>{{ $user->surname }}</small>
                                </h4>
                                <img src="{{ route('trombi.phone', ['id' => $user->id ]) }}" alt="phone" />
                                @if(substr($user->facebook, 0, 4) == 'http')
                                    <br /></i>&nbsp;<a href="{{ $user->facebook }}">Facebook</a>
                                @endif
                                @if(Auth::user() && Auth::user()->isAdmin())
                                    <br /><a class="btn btn-xs btn-warning" href="{{ route('dashboard.students.edit', [ 'id' => $user->id ])}}">Modifier</a>
                                @endif
                                @if($user->pivot->subrole)
                                    <br/><span class="label label-info">{{$user->pivot->subrole}}</span><br />
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
        @endforeach
    </div>
</div>
@endsection