@extends('layouts.dashboard')

@section('title')
    Week-End d'Intégration
@endsection

@section('smalltitle')
    Stats d'inscriptions
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ $sum['paid'] }}</h3>

                    <p>Inscriptions</p>
                </div>
                <div class="icon">
                    <i class="fa fa-ticket"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ $sum['caution'] }}</h3>

                    <p>Cautions</p>
                </div>
                <div class="icon">
                    <i class="fa fa-euro"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $sum['food'] }}</h3>

                    <p>Repas</p>
                </div>
                <div class="icon">
                    <i class="fa fa-gittip"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-yellow">
        <div class="box-header with-border">
            <h3 class="box-title">Graphique de remplissage</h3>
        </div>
        <div class="box-body">
            <div class="chart">
                <canvas id="myChart" width="400" height="100"></canvas>

            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-eur"></i> Récapitulatif inscriptions</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover" id="basket">
                    <thead>
                    <tr>
                        <th>Dénomination</th>
                        <th>WEI</th>
                        <th>Caution</th>
                        <th>Repas</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr class="vert-align">
                            <td>
                                <strong>CE</strong>
                            </td>
                            <td>{{ $global['ce']['wei'] }}</td>
                            <td>{{ $global['ce']['guarantee'] }}</td>
                            <td>{{ $global['ce']['sandwitch'] }}</td>
                        </tr>
                        <tr class="vert-align">
                            <td>
                                <strong>Orga</strong>
                            </td>
                            <td>{{ $global['orga']['wei'] }}</td>
                            <td>{{ $global['orga']['guarantee'] }}</td>
                            <td>{{ $global['orga']['sandwitch'] }}</td>
                        </tr>
                        <tr class="vert-align">
                            <td>
                                <strong>Bénévoles/Anciens</strong>
                            </td>
                            <td>{{ $global['vieux']['wei'] }}</td>
                            <td>{{ $global['vieux']['guarantee'] }}</td>
                            <td>{{ $global['vieux']['sandwitch'] }}</td>
                        </tr>
                        <tr class="vert-align">
                            <td>
                                <strong>Nouveaux</strong>
                            </td>
                            <td>{{ array_sum(array_column($global['newcomers'], 'wei')) }}</td>
                            <td>{{ array_sum(array_column($global['newcomers'], 'guarantee')) }}</td>
                            <td>{{ array_sum(array_column($global['newcomers'], 'sandwitch')) }}</td>
                        </tr>
                    @foreach($global['newcomers'] as $branch=>$newcomer)
                        <tr class="vert-align">
                            <td>
                                <i>{{ $branch }}</i>
                            </td>
                            <td>{{ $newcomer['wei'] }}</td>
                            <td>{{ $newcomer['guarantee'] }}</td>
                            <td>{{ $newcomer['sandwitch'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                <tfoot>

                </tfoot>
            </table>
        </div>
    </div>




@endsection
@section('js')
    @parent
    <script src="{{ asset('js/Chart.min.js') }}" type="text/javascript"></script>
    <script>
        var ctx = document.getElementById("myChart");
        var data = {
            labels: [
                @foreach($graphPaid as $item)
                        "{{ $item->day }}" ,
                @endforeach
            ],
            datasets: [
                {
                    label: "Paiement WEI",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(75,192,192,0.4)",
                    borderColor: "rgba(75,192,192,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(75,192,192,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(75,192,192,1)",
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [
                        @foreach($graphPaid as $item)
                                "{{ $item->sum }}" ,
                        @endforeach
                    ],
                    spanGaps: false,
                },
                {
                    label: "Repas",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(192,75,192,0.4)",
                    borderColor: "rgba(192,75,192,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(192,75,192,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(192,75,192,1)",
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [
                        @foreach($graphFood as $item)
                                "{{ $item->sum }}" ,
                        @endforeach
                    ],
                    spanGaps: false,
                },
                {
                    label: "Caution",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(92,192,75,0.4)",
                    borderColor: "rgba(92,192,75,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(92,192,75,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(92,192,75,1)",
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [
                        @foreach($graphCaution as $item)
                                "{{ $item->sum }}" ,
                        @endforeach
                    ],
                    spanGaps: false,
                }
            ]
        };
        var myChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:false
                        }
                    }]
                }
            }
        });
    </script>
@endsection
