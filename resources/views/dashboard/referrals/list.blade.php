@extends('layouts.dashboard')

@section('title')
Parrains
@endsection

@section('smalltitle')
Liste de toutes les personnes qui ont visionné le formulaire au moins une fois.
@endsection

@section('content')

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
                    <th>Semestre</th>
                    <th>Fillots</th>
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
                        <td>{{{ $referral->branch.$referral->level }}}</td>
                        <?php
                            $branch = $referral->branch;
                            $emailEntry = $referral->first_name . ' ' . $referral->last_name.' <'.$referral->email.'>,'."\n";

                            if(empty($levels[$branch])) {
                                $levels[$branch] = 0;
                            }
                            $levels[$branch]++;

                            if(empty($max[$branch])) {
                                $max[$branch] = 0;
                            }
                            $max[$branch] += $referral->referral_max;
                            if($referral->referral_validated) {
                                // Add email to lists
                                $emailsValidated .= $emailEntry;
                                if(strtolower($branch) == 'tc') {
                                    $emailsValidatedTC .= $emailEntry;
                                    if($referral->level >= 4) {
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
                                $maxV[$branch] += $referral->referral_max;
                            }
                        ?>
                        <td>
                            @if (!$referral->referral_validated)
                                <span class="label label-default">
                            @elseif ($referral->newcomers()->count() > 0 && $referral->newcomers()->count() <= $referral->referral_max)
                                <span class="label label-success">
                            @elseif ($referral->newcomers()->count() > 0 && $referral->newcomers()->count() <= 5)
                                <span class="label label-warning">
                            @else
                                <span class="label label-danger">
                            @endif
                                {{ $referral->newcomers()->count() }} / {{ $referral->referral_max }}
                            </span>
                        </td>
                        <td>
                            @if ($referral->referral_validated)
                                <span class="label label-success">Validé</span>
                                <?php $validated++; ?>
                            @elseif (empty($referral->referral_text) || empty($referral->phone) || empty($referral->email))
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
                            <a class="btn btn-xs btn-warning" href="{{ route('dashboard.students.edit', [ 'id' => $referral->id ])}}">Modifier</a>
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
            <tr>
                <td colspan="6">
                    Nouveaux :
                    @foreach ($newcomersCounts as $item)
                        {{ $item->count }} {{ $item->branch }} |
                    @endforeach
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    Nouveaux sans parrains :
                    @foreach ($newcomersWithoutRefCounts as $item)
                        {{ $item->count }} {{ $item->branch }} |
                    @endforeach
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Listes d'adresses mail</h3>
    </div>
    <div class="box-body">
        <h4>Tous les profils validés</h4>
        <textarea class="form-control" readonly>{{ $emailsValidated }}</textarea>

        <h4>Tous les profils incomplets</h4>
        <p><em>Note: Les personnes qui sont dans cette liste ont parfois juste regardé le formulaire.<br/>
        Il ne veulent donc pas forcément devenir parrain. Prenez ça en compte si vous leur écrivez un mail.</em></p>
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
