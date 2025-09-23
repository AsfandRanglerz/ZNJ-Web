@extends('admin.layout.app')
@section('title', 'Profile')
@section('content')
    <!-- Main Content -->
    <div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row mt-sm-4">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="padding-20">
                            <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                {{-- <li class="nav-item">
                                    <a class="nav-link" id="home-tab2" data-toggle="tab" href="#about" role="tab" aria-selected="false">About</a>
                                </li> --}}
                                <li class="nav-item">
                                    <a class="nav-link active" id="profile-tab2" data-toggle="tab" href="#settings" role="tab" aria-selected="true">Settings</a>
                                </li>
                            </ul>
                            <div class="tab-content tab-bordered" id="myTab3Content">
                                <div class="tab-pane fade" id="about" role="tabpanel" aria-labelledby="home-tab2">
                                    <div class="row mx-0 pt-3">
                                        <div class="col-md-3 col-6 b-r">
                                            <strong>Full Name</strong>
                                            <br>
                                            <p class="text-muted">{{$data->name}}</p>
                                        </div>
                                        <div class="col-md-3 col-6 b-r">
                                            <strong>Mobile</strong>
                                            <br>
                                            <p class="text-muted">{{$data->phone}}</p>
                                        </div>
                                        <div class="col-md-3 col-6 b-r">
                                            <strong>Email</strong>
                                            <br>
                                            <p class="text-muted">{{$data->email}}</p>
                                        </div>

                                    </div>
                                </div>
                                <div class="tab-pane fade active show" id="settings" role="tabpanel" aria-labelledby="profile-tab2">
                                    <form method="post" action="{{url('admin/update-profile')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-header">
                                            <h4>Profile</h4>
                                        </div>
{{--                                        @if(\Illuminate\Support\Facades\Session::has('errors'))--}}
{{--                                            {{dd($errors)}}--}}
{{--                                            @endif--}}
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-md-6 col-12">
                                                    <label>Name</label>
                                                    <input type="text" name="name" value="{{$data->name}}" class="form-control" >
                                                    @error('name')
                                                    <div class="text-danger">
                                                        Please fill in the  Name
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label>Email</label>
                                                    <input type="email" name="email" value="{{$data->email}}" class="form-control">
                                                    @error('email')
                                                    <div class="text-danger">
                                                        Please fill in the email
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6 col-12">
                                                    <label>Profile Image</label>
                                                    <div class="custom-file">
                                                        <input type="file" name="image" class="custom-file-input" id="customFile">
                                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                                    </div>

                                                    <div class="invalid-feedback">

                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label>Phone</label>
                                                    <input type="tel" name="phone" value="{{$data->phone}}"  class="form-control" value="">
                                                    @error('phone')
                                                    <div class="text-danger">
                                                        Please fill in the email
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>

                                        <div class="row justify-content-center">
                                            <button type="submit" class="btn btn-primary">Update Profile</button>
                                        </div>

                                    </div>
                                    </form>

                                        <form method="post" action="{{ route('profile.change-password',[$data->id,$data->slug]) }}" enctype="multipart/form-data">
                                            @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-md-6 col-12">
                                                    <label>Old Password</label>
                                                    <input type="password" placeholder="old password" name="current_password" id="current_password" value="" class="form-control" >
                                                    {{-- @error('current_password')
                                                    <div class="text-danger">
                                                        The old password is incorrect
                                                    </div>
                                                    @enderror --}}
                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label>New Password</label>
                                                    <input type="password" name="new_password" placeholder="new password" id="new_password" value="" class="form-control">
                                                    @error('new_password')
                                                    <div class="text-danger">
                                                        Please fill the new password
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-4 justify-content-center">
                                            <button type="submit"  class="btn btn-primary">Update Password</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>

@endsection

@section('js')
    @if(\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{\Illuminate\Support\Facades\Session::get('message')}}');
        </script>
    @endif
@endsection
