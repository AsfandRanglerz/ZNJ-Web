@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <body>
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <a class="btn btn-primary mb-3" href="{{ route('venue.show', $venue['user_id']) }}">Back</a>
                    {{-- @dd($venue['venue'][0]['amenities']) --}}
                    <form id="add_student" action="{{ route('venue-providers.venue.update', $venue['venue'][0]['id']) }}"
                        method="POST" enctype="multipart/form-data">

                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Edit Venue</h4>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label> Title </label>
                                                <input type="text" placeholder="example" name="title" id="title"
                                                    Value="{{ $venue['venue'][0]['title'] }}" class="form-control">
                                                @error('title')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-2">
                                            <div class="form-group mb-3">
                                                <label>Category</label>
                                                <select name="category_id" id="category" class="form-control">
                                                    <option value="">Please Select a Category </option>
                                                    @foreach ($venue['venue_categories'] as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ str_contains($venue['venue'][0]['venueCategory']['category'], $category->category) ? 'selected' : '' }}>
                                                            {{ $category->category }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('category')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="row mx-0 px-4" id="edit_venue_row">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Description</label>
                                                <input type="text" name="description" id="description"
                                                    Value="{{ $venue['venue'][0]['description'] }}" class="form-control"
                                                    placeholder="example">
                                                @error('description')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Seats</label>
                                                <input type="number" placeholder="example" name="seats" id="seats"
                                                    Value="{{ $venue['venue'][0]['seats'] }}" class="form-control">
                                                @error('seats')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Opening Time</label>
                                                <input type="time" name="opening time" id="opening_time"
                                                    Value="{{ $venue['venue'][0]['opening_time'] }}" class="form-control">
                                                @error('opening_time')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Closing Time</label>
                                                <input type="time" placeholder="" name="closing_time" id="closing_time"
                                                    Value="{{ $venue['venue'][0]['closing_time'] }}" class="form-control">
                                                @error('closing_time')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Stands</label>
                                                <input type="number" placeholder="" name="stands" id="stands"
                                                    Value="{{ $venue['venue'][0]['stands'] }}" class="form-control">
                                                @error('Stands')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label>Location</label>

                                                <input type="text" name="address" id="autocomplete"
                                                    value="{{ $venue['venue'][0]['address'] }}" class="form-control">

                                                @error('address')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                            </div>


                                        </div>
                                        <div class="form-group" id="latitudeArea">
                                            <input type="text" id="latitude" name="latitude"
                                                value="{{ $venue['venue'][0]['latitude'] }}" class="form-control d-none">
                                        </div>

                                        <div class="form-group" id="longtitudeArea">
                                            <input type="text" name="longitude" id="longitude"
                                                value="{{ $venue['venue'][0]['longitude'] }}" class="form-control d-none">
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Feature Ads</label>
                                                @if ($venue['venue'][0]['feature_status'] === '0')
                                                    <input type="checkbox" name="feature_ads" data-toggle="toggle"
                                                        data-on="Featured" data-toggle="tooltip" data-off="Unfeatured"
                                                        data-onstyle="success" data-offstyle="danger">
                                            </div>
                                        </div>
                                    @else
                                        <input type="checkbox" name="feature_ads" data-toggle="toggle"
                                            data-on="Featured" data-toggle="tooltip" data-off="Unfeatured"
                                            data-onstyle="success" data-offstyle="danger" checked>
                                    </div>
                                </div>
                                <div class="col-sm-6 pl-sm-0 pr-sm-3" id='edit_feature_packages'>
                                    <div class="form-group mb-2">
                                        <label>Select Feature Package</label>
                                        <select name="venue_feature_ads_packages_id" class="form-control">
                                            <option value=''>Please Select Package</option>
                                            @foreach ($venue['venue_feature_ads_packages'] as $feature)
                                                <option value="{{ $feature->id }}"
                                                    {{ str_contains($venue['venue'][0]['venue_feature_ads_packages_id'], $feature->id) ? 'selected' : '' }}>
                                                    {{ $feature->title }} - $ {{ $feature->price }} -
                                                    {{ $feature->validity }}</option>
                                            @endforeach
                                        </select>

                                        @error('venue_feature_ads_packages_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror

                                    </div>
                                </div>

                                @endif
                            </div>
                            <div class="row mx-0 px-4 mt-3">
                                <?php $arr = ['Free Parking', 'Food & Drinks', 'Pets Allowed', 'Bar', 'Security alam', 'Safety deposit box', 'Cattering', 'Designated smooking area', 'Non-Smoking', 'Business center', 'Terrace', 'CCTV outside', 'Amoke alarms', 'Lift', 'Car hire', 'ATM/Cash machine', 'Ticket service', 'Velet Parking', 'Wheel chair accessible', 'Shops(on site)', 'Free Wifi', 'Garden', 'Kids Friendly buffet', 'Fire extinguisher', '24-hour security', 'Restaurant', 'Air conditioning', 'Fax/Photocopy', 'Outdoor pool', 'on-side cafe house', 'Special diet', 'CCTV in common area', 'Heating', 'Lockers', 'V.I.P room facilities', 'Luggage', 'Currency exchange', 'Tour desk', 'Baby sitting service', 'Barber/Beauty shop']; ?>
                                @foreach ($arr as $amenities)
                                    <div class="col-sm-3 pl-sm-0 pr-sm-2">
                                        <div class="form-check">
                                            <input class="form-check-input" name="amenities[]"type="checkbox"
                                                value="{{ $amenities }}"
                                                {{ str_contains($venue['venue'][0]['amenities'], $amenities) ? 'checked' : '' }}
                                                id="{{ $amenities }}">
                                            <label class="form-check-label" for="{{ $amenities }}">
                                                {{ $amenities }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Price</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon2">PKR</span>
                                                    </div>
                                                    <input type="text" class="form-control" placeholder="Price" aria-label="Price" name="price" aria-describedby="basic-addon2">
                                                    @error('price')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                </div>
                                            </div>
                                        </div> --}}


                            {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Choose Images</label>
                                                <input type="file" placeholder="" name="stands" id="stands"  class="form-control"
                                                    >
                                                @error('Stands')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div> --}}
                            {{-- <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Offer Cattering</label>
                                                <p>
                                                    <label>Yes</label>
                                                    <input type="radio" name="offer cattering" value="Yes"
                                                    @if ($venue['offer_cattering'] == 'Yes')  checked @endif id="">
                                                    <label>No</label>
                                                    <input type="radio" name="offer cattering" value="No" @if ($venue['offer_cattering'] == 'No')  checked  @endif  id="">
                                                </p>

                                                @error('offer cattering')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div> --}}

                            <div class="card-footer text-center row">
                                <div class="col">
                                    <button type="submit" class="btn btn-success mr-1 btn-bg"
                                        id="submit">Update</button>
                                </div>
                            </div>

                        </div>



                        {{-- <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Confirm Password</label>
                                                <input type="password" placeholder="Example" name="password_confirmation" id="password" class="form-control"
                                                    >
                                                @error('password_confirmation')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                    </div> --}}

                </div>
        </div>
        </div>
        </form>
        </div>
        </section>
        </div>
    </body>
@endsection

@section('scripts')
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.toggle').click(function(e) {
                e.preventDefault();
                if ($('.toggle').hasClass('btn-danger')) {
                    swal({
                            title: `Are you sure you want to Feature this Ad?`,
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willFeature) => {
                            if (willFeature) {
                                $(this).removeClass("btn-danger");
                                $(this).removeClass("off");
                                $(this).addClass("btn-success");
                                $('#edit_feature_packages').remove();
                                $('#edit_venue_row').append(`<div class="col-sm-6 pl-sm-0 pr-sm-3"  id='edit_feature_packages'>
                                            <div class="form-group mb-2">
                                                <label>Select Feature Package</label>
                                                <select name="venue_feature_ads_packages_id" class="form-control">
                                                    <option value=''>Please Select Package</option>
                                                    @foreach ($venue['venue_feature_ads_packages'] as $feature)
                                                    <option value="{{ $feature->id }}" {{ str_contains($venue['venue'][0]['venue_feature_ads_packages_id'], $feature->id) ? 'selected' : '' }}>{{ $feature->title }} - $ {{ $feature->price }} - {{ $feature->validity }}</option>
                                                    @endforeach
                                               </select>

                                                @error('venue_feature_ads_packages_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                            </div>
                                        </div>`)
                            } else {
                                $('#edit_feature_packages').remove();
                                $(this).removeClass("btn-success");
                                $(this).addClass("btn-danger");
                                $(this).addClass("off");
                            }
                        });
                } else {
                    swal({
                            title: `Are you sure you want to Unfeature this Ad?`,
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willUnfeature) => {
                            if (willUnfeature) {
                                $(this).removeClass("btn-success");
                                $(this).addClass("btn-danger");
                                $(this).addClass("off");
                                $('#edit_feature_packages').remove();
                            } else {
                                $(this).removeClass("btn-danger");
                                $(this).removeClass("off");
                                $(this).addClass("btn-success");
                                $('#edit_feature_packages').remove();
                                $('#edit_venue_row').append(`<div class="col-sm-6 pl-sm-0 pr-sm-3" id='edit_feature_packages'>
                                            <div class="form-group mb-2">
                                                <label>Select Feature Package</label>
                                                <select name="venue_feature_ads_packages_id" class="form-control">
                                                    <option value=''>Please Select Package</option>
                                                    @foreach ($venue['venue_feature_ads_packages'] as $feature)
                                                    <option value="{{ $feature->id }}" {{ str_contains($venue['venue'][0]['venue_feature_ads_packages_id'], $feature->id) ? 'selected' : '' }}>{{ $feature->title }} - $ {{ $feature->price }} - {{ $feature->validity }}</option>
                                                    @endforeach
                                               </select>

                                                @error('venue_feature_ads_packages_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                            </div>
                                        </div>`)
                            }
                        });


                }

            });
        });
    </script>

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
