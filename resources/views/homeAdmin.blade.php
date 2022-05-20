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
                                    <img src="/assets/img/user.png">
                                </div>
                                <div class="media-body">
                                    <h4 class="text-uppercase mb-0 weight500">{{ count(\App\User::all()) }}</h4>
                                    <span class="text-muted">USERS</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="media d-flex align-items-center  mb-4">
                                <div class="mr-4 sr-icon-box">
                                    <img src="/assets/img/bug.png">
                                </div>
                                <div class="media-body">
                                    <h4 class="text-uppercase mb-0 weight500">{{ count(\App\Report::all()) }}</h4>
                                    <span class="text-muted">REPORTS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/sales statistical overview-->
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
          console.log(globalToken);
          $.ajax({
              url: '{{ route('updateLocation') }}',
              method: 'PATCH',
              data: {lat, lng, globalToken, _token: "{{csrf_token()}}" }
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

    </script>
    <!--chartjs-->
    <script src="/assets/vendor/chartjs/Chart.bundle.min.js"></script>
    <!--chartjs initialization-->

@endsection