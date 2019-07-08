@extends('layouts.dashboard')

@section('title')
    Creation d'une permanence
@endsection

@section('content')

    <div class="callout callout-info">
    <h4>Création d'une permanence</h4>
        <p>
            Choisissez un type de permanence
        </p>
    </div>

    <div class="box box-default">
        <div class="box-body table-responsive">
            <form action="{{ url('dashboard/perm/create') }}" method="post">
                
                <select id="permType" name="permType" class="form-control">
                  @foreach ($permTypes as $permType)
                    <option value="{{ $permType->id }}" @if (old('permType') == 0) selected="selected" @endif >{{ $permType->name }}</option>                      
                  @endforeach
                </select>
                <button type="submit" class="btn btn-success" id="formSubmit">Sélectionner le type de permanence</button>
            </form>
        </div>
    </div>

@endsection
