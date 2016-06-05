@extends('layouts.dashboard')

@section('title')
<h1>
    Parrains
    <small>Liste de toutes les personnes qui ont validé le formulaire au moins une fois</small>
</h1>
@endsection

@section('content')

@include('display-errors')
<div class="callout callout-info">
    <h4>Labels</h4>
    <p>
        <b>Les personnes avec le label <span class="label label-success">Validé</span> sont les personnes pour qui le message a été validé et qui ne peuvent plus changer leurs informations.</b><br>
        <b>Les personnes avec le label <span class="label label-warning">En attente</span> sont les personnes pour qui le message n'a pas encore été validé.</b><br>
        <b>Les personnes avec le label <span class="label label-danger">Incomplet</span> sont les personnes pour qui il manque des informations (texte, téléphone ou mail).</b>
    </p>
</div>



<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Liste des parrains</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>Numéro étudiant</th>
                    <th>Nom complet</th>
                    <th>Adresse</th>
                    <th>Niveau</th>
                    <th>Labels</th>
                    <th>Actions</th>
                </tr>
                <?php
                    $validated = 0;
                    $waiting = 0;
                    $incomplete = 0;
                    $levels = [];
                ?>
                @foreach ($referrals as $referral)
                    <tr>
                        <td>{{ $referral->student_id }}</td>
                        <td>{{{ $referral->first_name . ' ' . $referral->last_name }}}</td>
                        <td>{{{ $referral->email }}}</td>
                        <td>{{{ $referral->level }}}</td>
                        <?php
                            $branch = preg_replace('/[^A-Z]+/', '', $referral->level);
                            if(empty($levels[$branch])) {
                                $levels[$branch] = 0;
                            }
                            $levels[$branch]++;
                        ?>
                        <td>
                            @if ($referral->validated)
                                <span class="label label-success">Validé</span>
				<?php $validated++; ?>
                            @elseif (empty($referral->free_text) || empty($referral->phone) || empty($referral->email))
                                <span class="label label-danger">Incomplet</span>
				<?php $incomplete++; ?>
                            @else
                                <span class="label label-warning">En attente</span>
				<?php $waiting++; ?>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('dashboard.referrals') }}" method="post">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="student-id" value="{{ $referral->student_id }}">
                                <input type="submit" class="btn btn-xs btn-danger" value="Supprimer">
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tr>
                <td colspan="6">
                    {{ $waiting + $incomplete + $validated }} Parrains
                    ({{ $validated }} validés,
                    {{ $waiting }} en attente
                    et {{ $validated }} incomplets)</td>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    @foreach ($levels as $level => $count)
                        {{ $count }} {{ $level }} |
                    @endforeach
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection
