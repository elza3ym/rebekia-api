@extends('layouts.master')

@section('content')
    <!--page title-->
    <div class="page-title mb-4 d-flex align-items-center">
        <div class="mr-auto">
            <h4 class="weight500 d-inline-block pr-3 mr-3 border-right">Request Info</h4>
            <nav aria-label="breadcrumb" class="d-inline-block ">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Requests</a></li>

                    <li class="breadcrumb-item active" aria-current="page">Request Info</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--/page title-->
    <!--employee data table-->
    <form action="{{ route('newExport')  }}" method="post">
        @csrf
        <div class="row">
        <div class="col-xl-12">
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
                                <th scope="col">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(\App\Item::all() as $item)
                                <tr>
                                    <td>
                                        <img class="rounded-circle mr-3 table-thumb" src="{{ $item->icon }}"
                                             alt=""/>{{ $item->name }}
                                    </td>
                                    <td><input class="form-control mr-3" style="display: inline; width: 10%!important;"
                                               name="amount[{{$item->id}}]" value="0" type="number" min="0" max="{{ isset($max[$item->id]) ? $max[$item->id] : 0 }}"><span
                                                class="badge badge-primary badge-lg h5" > KG</span></td>
                                </tr>
                            @endforeach
                            <input type="hidden" name="lat" value="0">
                            <input type="hidden" name="lng" value="0">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="float-right">
                        <button type="submit" class="btn btn-success">Create</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>

    <!--/employee data table-->
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
            $('input[name="lat"]').attr("value",  position.coords.latitude);
            $('input[name="lng"]').attr("value",  position.coords.longitude);
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
@endsection