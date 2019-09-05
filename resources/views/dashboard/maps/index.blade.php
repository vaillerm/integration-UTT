@extends('layouts.dashboard')

@section('title')
    Carte
@endsection

@section('css')
    @mapstyles
    <style>
        .gnw-map-service {
            height: 750px;
        }
    </style>
@endsection

@section('content')

    <div class="box box-yellow">
        <div class="box-header with-border">
            <h3 class="box-title">Position des bus</h3>
        </div>
        <div class="box-body">
            @map([
                'lat' => '47.8463',
                'lng' => '3.5815',
                'zoom' => '9',
                'markers' => $pts,
            ])

        </div>
        <!-- /.box-body -->
    </div>

@endsection

@section('js')
    @mapscripts
@endsection
