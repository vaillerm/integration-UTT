@extends('layouts.dashboard')

@section('title')
    Week-end d'intégration
@endsection

@section('smalltitle')
    Stats d'inscriptions
@endsection

@section('content')

    <div class="box box-info">
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




@endsection
@section('js')
    @parent
    <script src="{{ @asset('js/Chart.min.js') }}" type="text/javascript"></script>
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