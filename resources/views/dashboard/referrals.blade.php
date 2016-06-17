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
                    <th>Max</th>
                    <th>Labels</th>
                    <th>Actions</th>
                </tr>
                <?php
                    $validated = 0;
                    $waiting = 0;
                    $incomplete = 0;
                    $levels = [];
                    $max = [];
                    $levelsV = [];
                    $maxV = [];

                    $emailsValidated = '';
                    $emailsIncomplete = '';
                    $emailsWaiting = '';
                    $emailsValidatedTC = '';
                    $emailsValidatedTC4 = '';
                    $emailsValidatedBranch = '';
                ?>
                @foreach ($referrals as $referral)
                    <tr>
                        <td>{{ $referral->student_id }}</td>
                        <td>{{{ $referral->first_name . ' ' . $referral->last_name }}}</td>
                        <td>{{{ $referral->email }}}</td>
                        <td>{{{ $referral->level }}}</td>
                        <td>{{{ $referral->max }}}</td>
                        <?php
                            $branch = preg_replace('/[^A-Z]+/', '', $referral->level);
                            $emailEntry = $referral->first_name . ' ' . $referral->last_name.' <'.$referral->email.'>,'."\n";

                            if(empty($levels[$branch])) {
                                $levels[$branch] = 0;
                            }
                            $levels[$branch]++;

                            if(empty($max[$branch])) {
                                $max[$branch] = 0;
                            }
                            $max[$branch] += $referral->max;
                            if($referral->validated) {
                                // Add email to lists
                                $emailsValidated .= $emailEntry;
                                if(strtolower($branch) == 'tc') {
                                    $emailsValidatedTC .= $emailEntry;
                                    if(in_array(strtolower($referral->level), ['tc4', 'tc5', 'tc6'])) {
                                        $emailsValidatedTC4 .= $emailEntry;
                                    }
                                }
                                else {
                                    $emailsValidatedBranch .= $emailEntry;
                                }

                                // Increase number of referrals in the branch
                                if(empty($levelsV[$branch])) {
                                    $levelsV[$branch] = 0;
                                }
                                $levelsV[$branch]++;

                                // Increase number of fillots in the branch
                                if(empty($maxV[$branch])) {
                                    $maxV[$branch] = 0;
                                }
                                $maxV[$branch] += $referral->max;
                            }
                        ?>
                        <td>
                            @if ($referral->validated)
                                <span class="label label-success">Validé</span>
                                <?php $validated++; ?>
                            @elseif (empty($referral->free_text) || empty($referral->phone) || empty($referral->email))
                                <span class="label label-danger">Incomplet</span>
                				<?php
                                $incomplete++;
                                $emailsIncomplete .= $emailEntry;
                                ?>
                            @else
                                <span class="label label-warning">En attente</span>
	                            <?php
                                $waiting++;
                                $emailsWaiting .= $emailEntry;
                                ?>
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
                    et {{ $incomplete }} incomplets)</td>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    Parrains :
                    @foreach ($levels as $level => $count)
                        {{ $count }} {{ $level }} |
                    @endforeach
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    Maximum de fillots attentus :
                    @foreach ($max as $branch => $count)
                        {{ $count }} {{ $branch }} |
                    @endforeach
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    Parrains validés :
                    @foreach ($levelsV as $level => $count)
                        {{ $count }} {{ $level }} |
                    @endforeach
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    Maximum de fillots attentus par les parrains validés :
                    @foreach ($maxV as $branch => $count)
                        {{ $count }} {{ $branch }} |
                    @endforeach
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Listes d'adresses email</h3>
    </div>
    <div class="box-body">
        <h4>Tous les profils validés</h4>
        <textarea class="form-control" readonly>{{ $emailsValidated }}</textarea>

        <h4>Tous les profils incomplets</h4>
        <p><em>Note: Les personnes qui sont dans cette liste ont parfois juste regardé le formulaire.<br/>
        Il ne veulent donc pas forcément devenir parrain. Prennez celà en compte si vous leur écrivez un email.</em></p>
        <textarea class="form-control" readonly>{{ $emailsIncomplete }}</textarea>

        <h4>Tous les profils en attente</h4>
        <textarea class="form-control" readonly>{{ $emailsWaiting }}</textarea>

        <h4>Tous les profils validés de TC</h4>
        <textarea class="form-control" readonly>{{ $emailsValidatedTC }}</textarea>

        <h4>Tous les profils validés de branche</h4>
        <textarea class="form-control" readonly>{{ $emailsValidatedBranch }}</textarea>

        <h4>Tous les profils validés de TC4, TC5, TC6</h4>
        <textarea class="form-control" readonly>{{ $emailsValidatedTC4 }}</textarea>
    </div>
</div>
@endsection
