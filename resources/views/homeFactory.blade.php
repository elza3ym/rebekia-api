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
                                    <h4 class="text-uppercase mb-0 weight500">{{ is_array(\Auth::user()->getRequests()) ? count(\Auth::user()->getRequests()) : 0}}</h4>
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
                                    <h4 class="text-uppercase mb-0 weight500">{{ \Auth::user()->on_hold || 0 }} EGP</h4>
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
        <div class="col-xl-12 col-md-12">
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
                                    <img class="rounded-circle mr-3 table-thumb" src="{{ $request->collectionPoint->profile_pic }}" alt=""/>{{ $request->collectionPoint->name }}
                                </td>
                                <td>{{ $request->collectionPoint->id }}</td>
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

@endsection