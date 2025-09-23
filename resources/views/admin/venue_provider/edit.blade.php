@extends('admin.layout.app')

@section('title', 'Dashboard')

@section('content')

    <body>

        <div class="main-content">

            <section class="section">

                <div class="section-body">

                    <a class="btn btn-primary mb-3"

                    href="{{route('admin.user.index')}}">Back</a>

                    <form id="add_student" action="{{ route('venue.update',$venue->id) }}" method="POST" enctype="multipart/form-data">

                        @method('PATCH')

                        @csrf

                        <div class="row">

                            <div class="col-12 col-md-12 col-lg-12">

                                <div class="card">

                                    <h4 class="text-center my-4">Edit Venue</h4>

                                    <div class="row mx-0 px-4">

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label> Name </label>

                                                <input type="text" name="name" id="name" Value="{{ $venue['name'] }}"class="form-control">

                                                @error('name')

                                                    <div class="text-danger">{{ $message }}</div>

                                                @enderror

                                            </div>

                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-2">

                                            <div class="form-group mb-3">

                                                <label>Email</label>

                                                <input type="email" name="email" id="email" Value="{{ $venue['email'] }}"class="form-control" />

                                            </div>

                                            @error('email')

                                            <div class="text-danger">{{ $message }}</div>

                                        @enderror

                                        </div>



                                    </div>

                                    <div class="row mx-0 px-4">

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label>Phone</label>

                                                <input type="tel" name="phone" id="phone" Value="{{ $venue['phone'] }}"class="form-control"

                                                placeholder="92 XXXXXXXXXX (Mobile Number)" >

                                                @error('phone')

                                                    <div class="text-danger">{{ $message }}</div>

                                                @enderror

                                            </div>

                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Image</label>
                                                <input type="file" name="image" id="image"
                                                    Value="{{ $venue['image'] }}" class="form-control">
                                            </div>
                                        </div>





                                    <div class="row mx-0 px-4">



                                        </div>



                                    </div>

                                    <div class="card-footer text-center row">

                                        <div class="col">

                                            <button type="submit" class="btn btn-success mr-1 btn-bg"

                                                id="submit">Update</button>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </section>

        </div>

    </body>

@endsection

