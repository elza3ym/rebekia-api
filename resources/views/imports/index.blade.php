@extends('layouts.master')

@section('content')
        <!--page title-->
        <div class="page-title mb-4 d-flex align-items-center">
            <div class="mr-auto">
                <h4 class="weight500 d-inline-block pr-3 mr-3 border-right">Requests</h4>
                <nav aria-label="breadcrumb" class="d-inline-block ">
                    <ol class="breadcrumb p-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Requests</li>
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
                                    <th scope="col">Email</th>
                                    <th scope="col">Gov ID</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($results  as $result)
                                    <tr>
                                        <td>
                                            <img class="rounded-circle mr-3 table-thumb" src="{{ $result->collector->profile_pic }}" alt=""/>{{ $result->collector->name }}
                                        </td>
                                        <td>{{ $result->collector->email }}</td>
                                        <td>{{ $result->collector->id }}</td>
                                        <td><span class="badge {{ \App\collectorRequest::getColor($result->status) }} text-light  form-pill px-3 py-1">{{ $result->status }}</span></td>
                                        <td><a class="btn btn-outline-success" href="{{ route('collectionImport', $result->id) }}"><i class="fa fa-sign-in"></i> </a> </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>No Content</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div style="float: right;">
                            {{ $results->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/employee data table-->
@endsection