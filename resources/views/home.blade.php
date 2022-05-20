@extends('layouts.master')

@section('content')
    <!--sales statistical overview-->
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-shadow mb-4 pt-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="custom-title-wrap border-0 mt-2 mb-4">
                                <div class="custom-title">Statistics</div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="media d-flex align-items-center  mb-4">
                                <div class="mr-4 sr-icon-box">
                                    <img src="/assets/img/request.png">
                                </div>
                                <div class="media-body">
                                    <h4 class="text-uppercase mb-0 weight500">{{ is_array(\Auth::user()->getRequests()) ? sizeof(\Auth::user()->getRequests()) : 0 }}</h4>
                                    <span class="text-muted">REQUESTS</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="media d-flex align-items-center  mb-4">
                                <div class="mr-4 sr-icon-box">
                                    <img src="/assets/img/onhold.png">
                                </div>
                                <div class="media-body">
                                    <h4 class="text-uppercase mb-0 weight500">{{ \Auth::user()->on_hold || 0}} EGP</h4>
                                    <span class="text-muted">ON HOLD</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="media d-flex align-items-center  mb-4">
                                <div class="mr-4 sr-icon-box">
                                    <img src="/assets/img/balance.png">
                                </div>
                                <div class="media-body">
                                    <h4 class="text-uppercase mb-0 weight500">{{ \Auth::user()->balance }} EGP</h4>
                                    <span class="text-muted">BALANCE</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/sales statistical overview-->
    <div class="row">
        <div class="col-xl-6 col-md-6">
            <div class="card card-shadow mb-4">
                <div class="card-header border-0">
                    <div class="custom-title-wrap bar-danger">
                        <div class="custom-title">
                            Requests Chart
                        </div>
                    </div>
                </div>
                <div class="card-body pt-5 pb-4">
                    <div class="tab-content" id="pills-tabContent2">
                        <div class="tab-pane fade show active" id="pills-weekly2" role="tabpanel" aria-labelledby="pills-today-tab">
                            <div class="row">
                                <div class="col-12 col-xl-7 col-md-6 text-center">
                                    <canvas id="doughnut_chart" class="mb-4" ></canvas>
                                    <small class="text-muted">REQUESTS CHART</small>
                                </div>
                                <div class="col-12 col-xl-4 col-md-6 text-muted mt-xl-4">
                                    <ul class="list-unstyled f12">
                                        <li class="list-widget-border mb-3 pb-3">
                                            <i class="fa fa-circle pr-2" style="color: #fcdd82"></i> {{ $stats['pending'] }}%
                                            <span class="float-right">PENDING</span>
                                        </li>
                                        <li class="list-widget-border mb-3 pb-3">
                                            <i class="fa fa-circle pr-2" style="color: #acf5fe"></i> {{ $stats['accepted'] }}%
                                            <span class="float-right">ACCEPTED</span>
                                        </li>
                                        <li class="list-widget-border mb-3 pb-3">
                                            <i class="fa fa-circle pr-2 " style="color: #cae59b"></i> {{ $stats['confirmed'] }}%
                                            <span class="float-right">CONFIRMED</span>
                                        </li>
                                        <li class="list-widget-border mb-3 pb-3">
                                            <i class="fa fa-circle pr-2 " style="color: #f79490"></i> {{ $stats['done'] }}%
                                            <span class="float-right">Done</span>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6">
            <div class="card card-shadow mb-4 ">
                <div class="card-header border-0">
                    <div class="custom-title-wrap bar-warning">
                        <div class="custom-title">Requests Data Table</div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-custom">
                            <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Gov ID</th>
                                <th scope="col">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($latest_requests as $request)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox my-1 mr-sm-2 float-left">
                                        <input type="checkbox" class="custom-control-input" id="customControlInline">
                                        <label class="custom-control-label" for="customControlInline"> </label>
                                    </div>
                                    <img class="rounded-circle mr-3 table-thumb" src="{{ $request->collector->profile_pic }}" alt=""/>{{ $request->collector->name }}
                                </td>
                                <td>{{ $request->collector->id }}</td>
                                <td><span class="badge {{ \App\collectorRequest::getColor($request->status) }} text-light  form-pill px-3 py-1">{{ $request->status }}</span></td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }
        function showPosition(position) {
          var lat = position.coords.latitude;
          var lng = position.coords.longitude;

          $.ajax({
              url: '{{ route('updateLocation') }}',
              method: 'PATCH',
              data: {lat, lng, _token: "{{csrf_token()}}" }
          })
        }
        function showError(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        }
        getLocation();
    </script>
    <!--chartjs-->
    <script src="/assets/vendor/chartjs/Chart.bundle.min.js"></script>
    <!--chartjs initialization-->
    <script>

        // chartjs initialization

        $(function () {
            "use strict";


// doughnut_chart

            var ctx = document.getElementById("doughnut_chart");
            var data = {
                labels: [
                    "PENDING", "ACCEPTED", "CONFIRMED", "DONE "
                ],
                datasets: [{
                    data: [{{$stats['pending']}}, {{$stats['accepted']}}, {{$stats['confirmed']}}, {{$stats['done']}}],
                    backgroundColor: [
                        "#fcdd82",
                        "#f79490",
                        "#fcdd82",
                        "#cae59b"
                    ],
                    borderWidth: [
                        "0px",
                        "0px",
                        "0px",
                        "0px"
                    ],
                    borderColor: [
                        "#acf5fe",
                        "#f79490",
                        "#fcdd82",
                        "#cae59b"
                    ]
                }]
            };

            var myDoughnutChart = new Chart(ctx, {
                type: 'doughnut',
                data: data,
                options: {
                    legend: {
                        display: false
                    }
                }
            });


        });
    </script>
@endsection