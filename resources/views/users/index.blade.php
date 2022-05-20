@extends('layouts.master')

@section('content')
    <!--page title-->
    <div class="page-title mb-4 d-flex align-items-center">
        <div class="mr-auto">
            <h4 class="weight500 d-inline-block pr-3 mr-3 border-right">Users</h4>
            <nav aria-label="breadcrumb" class="d-inline-block ">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
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
                        <div class="custom-title">Users Data Table</div>
                        <div class=" widget-action-link">
                            <a href="{{ route('admin.users.create', $access_level) }}" class="btn btn-success btn-sm text-white" >
                                Create User
                            </a>

                        </div>
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
                                <th scope="col">Balance</th>
                                <th scope="col">On Hold</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($results  as $result)
                                <tr>
                                    <td>
                                        <img class="rounded-circle mr-3 table-thumb" src="{{ $result->profile_pic }}" alt=""/>{{ $result->name }}
                                    </td>
                                    <td>{{ $result->email }}</td>
                                    <td>{{ $result->id }}</td>
                                    <td>{{ $result->balance }}</td>
                                    <td>{{ $result->on_hold }}</td>
                                    <td>
                                        <a class="btn btn-outline-primary" href="{{ route('admin.users.editView', $result->id) }}"><i class="fa fa-pencil-square-o"></i> </a>
                                        <form method="post" action="{{ route('admin.users.delete', $result->id) }}" style="display: inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-outline-danger" ><i class="fa fa-trash-o"></i> </button>

                                        </form>
                                    </td>
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