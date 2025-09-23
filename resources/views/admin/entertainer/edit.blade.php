@extends('admin.layout.app')

@section('title', 'Dashboard')

@section('content')

    <body>

        <div class="main-content">

            <section class="section">

                <div class="section-body">

                    {{-- @dd('ss') --}}

                    <a class="btn btn-primary mb-3" href="{{ route('admin.user.index') }}">Back</a>

                    <form id="add_student" action="{{ route('entertainer.update', $entertainer->id) }}" method="POST"
                        enctype="multipart/form-data">

                        @method('PATCH')

                        @csrf

                        <div class="row">

                            <div class="col-12 col-md-12 col-lg-12">

                                <div class="card">

                                    <h4 class="text-center my-4">Edit Entertainer</h4>

                                    <div class="row mx-0 px-4">

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label> Name </label>

                                                <input type="text" name="name" id="name"
                                                    Value="{{ $entertainer['name'] }}" class="form-control">

                                                @error('name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                            </div>

                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-2">

                                            <div class="form-group mb-3">

                                                <label>Email</label>

                                                <input type="email" name="email" id="email"
                                                    Value="{{ $entertainer['email'] }}" class="form-control" />

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

                                                <input type="tel" name="phone" id="phone"
                                                    Value="{{ $entertainer['phone'] }}" class="form-control"
                                                    placeholder="92 XXXXXXXXXX (Mobile Number)">

                                                @error('phone')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                            </div>

                                        </div>



                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label>DOB</label>

                                                <input type="date" placeholder="Date of birth" name="dob"
                                                    value="{{ date('Y-m-d', strtotime($entertainer['dob'])) }}"
                                                    class="form-control">


                                                @error('dob')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                            </div>

                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label>Country</label>

                                                <input type="text" placeholder="Country" name="country"
                                                    value="{{ $entertainer['country'] }}" class="form-control">

                                                @error('country')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                            </div>

                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label>City</label>

                                                <input type="text" placeholder="city" name="city"
                                                    value="{{ $entertainer['city'] }}" class="form-control">

                                                @error('city')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                            </div>

                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label>Gender</label>

                                                <div>

                                                    <label>Male</label>

                                                    <input type="radio"value="Male" name="gender" value="Male"
                                                        {{ $entertainer['gender'] === 'Male' ? 'checked' : '' }}>

                                                    &nbsp;

                                                    <label>Female</label>

                                                    <input type="radio" name="gender" value="Female"
                                                        {{ $entertainer['gender'] === 'Female' ? 'checked' : '' }}>

                                                    <label>Others</label>

                                                    <input type="radio" value="Others" name="gender" value="Others"
                                                        {{ $entertainer['gender'] === 'Others' ? 'checked' : '' }}>

                                                    @error('gender')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                </div>

                                            </div>

                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label>Nationality</label>

                                                <input type="text" placeholder="Nationality" name="nationality"
                                                    value="{{ $entertainer['nationality'] }}" class="form-control">

                                                @error('nationality')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                            </div>

                                        </div>

                                        {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label>Company</label>

                                                <input type="text" name="company" id="company" Value="{{ $entertainer['company'] }}" class="form-control"

                                                    >

                                                @error('company')

                                                    <div class="text-danger">{{ $message }}</div>

                                                @enderror

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row mx-0 px-4">

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label>Designation</label>

                                                <input type="text" name="designation" id="designation" Value="{{ $entertainer['designation'] }}" class="form-control"

                                                    >

                                                @error('designation')

                                                    <div class="text-danger">{{ $message }}</div>

                                                @enderror

                                            </div>

                                        </div> --}}

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Image</label>
                                                <input type="file" name="image" id="image"
                                                    Value="{{ $entertainer['image'] }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label>Location</label>

                                                <input type="text" name="address" id="autocomplete"
                                                    value="{{ $entertainer['address'] }}" class="form-control">

                                                @error('address')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                                <div class="form-group" id="latitudeArea">
                                                    <input type="text" id="latitude" name="latitude"
                                                        value="{{ $entertainer['latitude'] }}"
                                                        class="form-control d-none">
                                                </div>

                                                <div class="form-group" id="longtitudeArea">
                                                    <input type="text" name="longitude" id="longitude"
                                                        value="{{ $entertainer['longitude'] }}"
                                                        class="form-control d-none">
                                                </div>

                                            </div>
                                        </div>
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
@section('js')
    <script type="text/javascript"
        src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places"></script>
    <script>
        $(document).ready(function() {
            $("#latitudeArea").addClass("d-none");
            $("#longtitudeArea").addClass("d-none");
        });
    </script>
    <script>
        google.maps.event.addDomListener(window, 'load', initialize);

        function initialize() {
            var input = document.getElementById('autocomplete');
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                $('#latitude').val(place.geometry['location'].lat());
                $('#longitude').val(place.geometry['location'].lng());

                $("#latitudeArea").removeClass("d-none");
                $("#longtitudeArea").removeClass("d-none");
            });
        }
    </script>
@endsection
