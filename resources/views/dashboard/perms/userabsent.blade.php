@extends('layouts.dashboard')

@section('title')
    Absence de  {{ $user->first_name.' '.$user->last_name }} à la perm {{ $perm->type->name }} du {{ date('d/m', $perm->start).' de '.date('H:i', $perm->start).' à '.date('H:i', $perm->end) }}
@endsection


@section('content')
    <div class="box box-default">
        <div class="box-body table-responsive">
            <form autocomplete="off" action="{{ url('dashboard/perm/'.$perm->id.'/users/'.$user->id.'/absent') }}" method="post" id="form">
                {{ method_field('PUT') }}
                <div class="form-group">
                    <label for="absence_reason">Raison de l'absence</label>
                    <textarea name="absence_reason" class="form-control">{{ old('absence_reason') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="commentary">Commentaire</label>
                    <textarea name="commentary" class="form-control">{{ old('commentary') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="pointsPenalty">Points de pénalité (une personne absente ne gagne de base aucuns points, mais vous pouvez en plus lui en retirer)</label>
                    <input type="number" name="pointsPenalty" class="form-control" value="0">
                </div>
                <button type="submit" class="btn btn-success" id="formSubmit">Ajouter l'absence</button>
            </form>
        </div>
    </div>

@endsection
