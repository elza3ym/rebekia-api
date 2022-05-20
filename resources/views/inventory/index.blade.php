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
                            @foreach($max as $key => $value)
                                @php($item = \App\Item::where('id', $key)->get()->first())
                                <tr>
                                    <td>
                                        <img class="rounded-circle mr-3 table-thumb" src="{{ $item->icon }}" alt=""/>{{ $item->name }}
                                    </td>
                                    <td><span class="badge badge-primary badge-lg h5">{{ $value }} KG</span></td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--/employee data table-->
@endsection