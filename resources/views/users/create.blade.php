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
    <form action="{{ route('admin.users.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" value="{{$access_level}}" name="access_level">
        <div class="row">
            <div class="col-xl-12">
                <div class="card card-shadow mb-4 ">
                    <div class="card-header border-0">
                        <div class="custom-title-wrap bar-warning">
                            <div class="custom-title">Create User</div>
                        </div>
                    </div>
                    <div class="card-body p-0 row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="gov_id" class="col-sm-12 col-form-label" style="max-width: 100%;">GOV ID</label>
                                <div class="col-sm-12">
                                    <input type="number" class="form-control" id="gov_id" name="gov_id" placeholder="GOV ID"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="col-sm-12 col-form-label" style="max-width: 100%;">Name</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Name"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="gender" class="col-sm-12 col-form-label">Gender</label>
                                <div class="col-sm-12">
                                    <select name="gender" id="gender" class="form-control" required>
                                        <option value="0">Female</option>
                                        <option value="1">Male</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email" class="col-sm-12 col-form-label">Email</label>
                                <div class="col-sm-12">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password" class="col-sm-12 col-form-label">Password</label>
                                <div class="col-sm-12">
                                    <input type="password" class="form-control" id="password" name="password"
                                           placeholder="Password" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="col-sm-12 col-form-label">Confirm
                                    Password</label>
                                <div class="col-sm-12">
                                    <input type="password" class="form-control" id="password_confirmation"
                                           name="password_confirmation" placeholder="Password" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="dob" class="col-sm-12 col-form-label">DOB</label>
                                <div class="col-sm-12">
                                    <div class="input-group date dpYears" data-date-viewmode="years" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control" aria-label="Right Icon" aria-describedby="dp-ig" name="dob">
                                        <div class="input-group-append">
                                            <button id="dp-ig" class="btn btn-outline-secondary" type="button"><i class="fa fa-calendar f14"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="dob" class="col-sm-12 col-form-label">Profile Picture</label>
                                <div class="col-sm-12">
                                    <div class="input-group mb-3 pl-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Upload</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="inputGroupFile01"
                                                   name="profile_pic" required>
                                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

@section('styles')
    <!--date picker-->
    <link href="/assets/vendor/date-picker/css/bootstrap-datepicker.min.css" rel="stylesheet">

@endsection

@section('scripts')
    <!--date picker-->
    <script src="/assets/vendor/date-picker/js/bootstrap-datepicker.min.js"></script>
    <!--init date picker-->
    <script src="/assets/vendor/js-init/pickers/init-date-picker.js"></script>
@endsection