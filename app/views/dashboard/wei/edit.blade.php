@extends('layouts.dashboard')

@section('title')
<h1>
    Week-end d'intégration
    <small>Inscription et gestion des participants</small>
</h1>
@endsection


@section('css')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.1/skins/square/blue.css">
@endsection

@section('content')

@include('display-errors')

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Édition de l'inscription de {{ $registration->first_name.' '.strtoupper($registration->last_name) }}</h3>
    </div>
    <div class="box-body">
        <form class="" action="{{ route('dashboard.wei.update', [$registration->id]) }}" method="post">

            <h2>Informations</h3>

            <label>Nom complet</label>
            <input type="text" value="{{ $registration->first_name.' '.ucfirst($registration->last_name)}}" class="form-control" disabled>
            <br>
            <label for="phone">Téléphone</label>
            <input type="text" name="phone" id="phone" value="{{ $registration->phone }}" class="form-control">
            <br>
            <label for="email">Email</label>
            <input type="text" name="email" id="email" value="{{ $registration->email }}" class="form-control">
            <br>
            <label for="birthdate">Date de naissance (A/M/J)</label>
            <input type="hidden" id="start" value="{{ Config::get('wei.dates.start') }}">
            <br>
            <div class="input-group date" id="picker">
                    <input type="text" name="birthdate" class="form-control">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
            </div>
            <br>
            <div id="parental-authorization" style="display:none">
                <label for="gave_parental_authorization"><i class="fa fa-exclamation"></i> MINEUR <i class="fa fa-exclamation"></i> Autorisation parentale déposée : </label>
                <input type="checkbox" name="gave_parental_authorization" id="gave_parental_authorization" @if ($registration->gave_parental_authorization) checked @endif>
                <br>
            </div>

            <h2>Paiements</h3>

            <h4>Paiement du week-end</h4>
            <label for="">Moyen de paiement</label>
            <select id="mean_of_payment" name="mean_of_payment" class="form-control" name="">
                <option value="none" @if ($registration->payment && $registration->payment === null) selected @endif>En attente</option>
                <option value="check" @if ($registration->payment && $registration->payment->mean === 'check') selected @endif>Chèque</option>
                <option value="card" @if ($registration->payment && $registration->payment->mean === 'card') selected @endif>Carte bancaire</option>
                <option value="cash" @if ($registration->payment && $registration->payment->mean === 'cash') selected @endif>Liquide</option>
                <option value="free" @if ($registration->payment && $registration->payment->mean === 'free') selected @endif>Offert</option>
            </select>
            <br>
            <div id="payment" style="display:none">
                <input type="text" name="payment_bank" class="form-control" placeholder="Banque" value="@if($registration->payment && $registration->payment->mean === 'check'){{$registration->payment->bank}}@endif">
                <input type="text" name="payment_emitter" class="form-control" placeholder="Émetteur" value="@if($registration->payment && $registration->payment->mean === 'check'){{$registration->payment->emitter}}@endif">
                <input type="text" name="payment_number" class="form-control" placeholder="Numéro de chèque" value="@if($registration->payment && $registration->payment->mean === 'check'){{$registration->payment->number}}@endif">
            </div>

            <h4>Caution</h4>
            <input type="text" name="deposit_bank" class="form-control" placeholder="Banque" value="@if($registration->deposit){{$registration->deposit->bank}}@endif">
            <input type="text" name="deposit_emitter" class="form-control" placeholder="Émetteur" value="@if($registration->deposit){{$registration->deposit->emitter}}@endif">
            <input type="text" name="deposit_number" class="form-control" placeholder="Numéro de chèque" value="@if($registration->deposit){{$registration->deposit->number}}@endif">
            <br>
            <input type="submit" class="btn btn-success form-control" value="Modifier">

        </form>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.1/icheck.min.js"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript">
    $(function () {
        $('#picker').datetimepicker({
            defaultDate: "{{ $registration->birthdate }}",
            format: "YYYY/MM/DD"
        });

        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%'
        });

        $('#picker').on('dp.change', function(e) {
            var birthdate = $('#picker').data('DateTimePicker').date();
            var start = moment($('#start').val(), 'YYYY/MM/DD');
            var diff = start.diff(birthdate, 'years', true);
            $('#parental-authorization').hide();
            if (diff < 18)
            {
                $('#parental-authorization').show();
            }
        });

        $('#picker').trigger('dp.change');

        $('#mean_of_payment').on('change', function() {
            $(this).val() === 'check' ? $('#payment').show() : $('#payment').hide();
        });
        $('#mean_of_payment').trigger('change');

    });
</script>
@endsection
