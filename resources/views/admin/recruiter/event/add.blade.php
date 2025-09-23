@extends('admin.layout.app')
@section('css')
@section('title', 'Dashboard')
@section('content')

    <body>
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <a class="btn btn-primary mb-2" href="{{ route('recruiter.show', $data['user_id']) }}">Back</a>
                    <form id="add_student" action="{{ route('recruiter.event.store', $data['user_id']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Add Event</h4>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label> Title </label>
{{-- <<<<<<< Updated upstream --}}
                                                <input type="text" name="title" id="name" placeholder="title" value="{{ old('title') }}" class="form-control">
{{-- =======
                                                <input type="text" name="title" id="name" class="form-control">
>>>>>>> Stashed changes --}}
                                                @error('title')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-2">
                                            <div class="form-group mb-3">
                                                <label>About Event</label>
{{-- <<<<<<< Updated upstream --}}
                                                <input type="text" name="about_event" placeholder="About Event" value="{{ old('about_event') }}"  class="form-control" />
{{-- =======
                                                <input type="text" name="about_event" class="form-control" />
>>>>>>> Stashed changes --}}
                                            </div>
                                            @error('about_event')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="row mx-0 px-4" id="event_row_2">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Description</label>
{{-- <<<<<<< Updated upstream --}}
                                                <input type="text" name="description" id="phone" placeholder="Description" value="{{ old('description') }}" class="form-control"
                                                placeholder="example" >
{{-- =======
                                                <input type="text" name="description" id="phone" class="form-control"
                                                    placeholder="example">
>>>>>>> Stashed changes --}}
                                                @error('description')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Price</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon2">$</span>
                                                    </div>
{{-- <<<<<<< Updated upstream --}}
                                                    <input type="number" class="form-control" placeholder="Price" aria-label="Price" name="price" value="{{ old('price') }}" aria-describedby="basic-addon2">
{{-- =======
                                                    <input type="number" class="form-control" placeholder="Price"
                                                        aria-label="Price" name="price" aria-describedby="basic-addon2">
>>>>>>> Stashed changes --}}

                                                </div>
                                                @error('price')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Event Type</label>

                                                <div>
                                                    <label>Public</label>

{{-- <<<<<<< Updated upstream --}}
                                                        <input type="radio" name="event_type" value="Public" >
                                                        &nbsp;
                                                    <label>Private</label>
                                                        <input type="radio" value="Private" name="event_type">
                                                        @error('event_type')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Joining Type</label>

                                                <div>
                                                    <label>Free</label>

{{-- <<<<<<< Updated upstream --}}
                                                        <input type="radio"value="Free" name="joining_type" value="{{ old('joining_type') }}" >
                                                        &nbsp;
                                                    <label>Private</label>
                                                        <input type="radio" value="Paid" name="joining_type" value="{{ old('joining_type') }}" >
                                                        @error('joining_type')
{{-- =======
                                                    <input type="radio"value="Free" name="joining_type">
                                                    &nbsp;
                                                    <label>Private</label>
                                                    <input type="radio" value="Paid" name="joining_type">
                                                    @error('joining_type')
>>>>>>> Stashed changes --}}
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3"> --}}
                                            {{-- <div class="form-group mb-2">
                                                <label>Hiring Entertainers Status</label> --}}

                                                {{-- <div>
                                                    <label>Hired</label>


                                                        <input type="radio"value="hired" name="hiring_entertainers_status" value="{{ old('hiring_entertainers_status') }}" >
                                                        &nbsp;
                                                    <label>Open For Hiring</label>
                                                        <input type="radio" value="open for hiring" name="hiring_entertainers_status" value="{{ old('hiring_entertainers_status') }}" >
                                                        @error('hiring_entertainers_status')

                                                    <input type="radio"value="hired" name="hiring_entertainers_status">
                                                    &nbsp;
                                                    <label>Open For Hiring</label>
                                                    <input type="radio" value="open for hiring"
                                                        name="hiring_entertainers_status">
                                                    @error('hiring_entertainers_status')

                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                </div> --}}


                                            {{-- </div> --}}
                                        {{-- </div> --}}
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
{{-- <<<<<<< Updated upstream --}}
                                            <div>
                                                <label for="seats">Seats</label>
                                                <input type="number" name="seats" id="seats" value="{{ old('seats') }}"
                                             class="form-control" placeholder="example">
                                            </div>
{{-- =======
                                                <div>
                                                    <label for="seats">Seats</label>
                                                    <input type="number" name="seats" id="seats" class="form-control"
                                                        placeholder="example">
                                                </div>
>>>>>>> Stashed changes --}}
                                                @error('seats')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Date</label>

{{-- <<<<<<< Updated upstream --}}
                                                <input type="date" name="date" id="date" value="{{ old('date') }}"  class="form-control"
                                                placeholder="example" >
{{-- =======
                                                <input type="date" name="date" id="date" class="form-control"
                                                    placeholder="example">
>>>>>>> Stashed changes --}}

                                                @error('date')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror



                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>From</label>

{{-- <<<<<<< Updated upstream --}}
                                                <input type="time" name="from" id="from" value="{{ old('from') }}" class="form-control"
                                                placeholder="example" >
{{-- =======
                                                <input type="time" name="from" id="from" class="form-control"
                                                    placeholder="example">
>>>>>>> Stashed changes --}}

                                                @error('from')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>To</label>

{{-- <<<<<<< Updated upstream --}}
                                                <input type="time" name="to" id="to" value="{{ old('to') }}" class="form-control"
                                                 >
{{-- =======
                                                <input type="time" name="to" id="to"
                                                    class="form-control">
>>>>>>> Stashed changes --}}

                                                @error('to')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Feature Ads</label>
                                                <input type="checkbox"  name="featured_ads" data-toggle="toggle"
                                                    data-on="Featured"
                                                    data-toggle="tooltip" data-off="Unfeatured" data-onstyle="success"
                                                    data-offstyle="danger">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Image</label>
                                                <input type="file" name="cover_image" class="form-control">
                                            </div>
                                        </div>
                                        {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Hiring Entertainer Status</label>
                                                <input type="text" name="hiring_entertainer_status" id="hiring_entertainer_status" Value="{{ $event['hiring_entertainer_status'] }}" class="form-control"
                                                placeholder="example" >
                                                @error('hiring_entertainer_status')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div> --}}
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


                                    </div>
                                    <div class="card-footer text-center row">
                                        <div class="col">
                                            <button type="submit" class="btn btn-success mr-1 btn-bg"
                                                id="submit">Add</button>
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
@section('scripts')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">
        $(document).on('click','#show_confirm',(function(event) {
        console.log('sss');
        }));
    </script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.toggle').click(function (e) {
                e.preventDefault();
        if($('.toggle').hasClass('btn-danger')){
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
                $('#feature_packages').remove();
                $('#event_row_2').append(` <div class="col-sm-6 pl-sm-0 pr-sm-3"  id='feature_packages'>
                                            <div class="form-group mb-2">
                                                <label>Select Feature Package</label>
                                                <select name="event_feature_ads_packages_id" class="form-control">
                                                <option>Please Select Package</option>
                                                @foreach($data['Event_feature_ads_packages'] as $feature)
                                                <option value="{{ $feature->id }}">{{ $feature->title }} - $ {{ $feature->price }} - {{ $feature->validity }}</option>
                                                @endforeach

                                               </select>
                                                @error('venue_feature_ads_packages_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                            </div>
                                        </div>`)
            }else{
                $('#feature_packages').remove();
                $(this).removeClass("btn-success");
                $(this).addClass("btn-danger");
                $(this).addClass("off");
            }
          });
        }else{
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
                $('#feature_packages').remove();
            }else{
                $(this).removeClass("btn-danger");
                $(this).removeClass("off");
                $(this).addClass("btn-success");
                $('#feature_packages').remove();
                $('#event_row_2').append(` <div class="col-sm-6 pl-sm-0 pr-sm-3" id='feature_packages'>
                                            <div class="form-group mb-2">
                                                <label>Select Feature Package</label>
                                                <select name="event_feature_ads_packages_id" class="form-control">
                                                <option>Please Select Package</option>
                                                @foreach($data['Event_feature_ads_packages'] as $feature)
                                                <option value="{{ $feature->id }}">{{ $feature->title }} - $ {{ $feature->price }} - {{ $feature->validity }}</option>
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
@endsection
