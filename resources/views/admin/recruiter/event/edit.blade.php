@extends('admin.layout.app')

@section('title', 'Dashboard')

@section('content')

    <body>

        <div class="main-content">

            <section class="section">

                <div class="section-body">

                    <a class="btn btn-primary mb-2"

                    href="{{route('recruiter.show',$data['user_id'])}}">Back</a>



                    <form id="add_student" action="{{ route('recruiter.event.update',$data['recruiter_event']['id']) }}" method="POST" enctype="multipart/form-data">



                        @csrf

                        <div class="row">

                            <div class="col-12 col-md-12 col-lg-12">

                                <div class="card">

                                    <h4 class="text-center my-4">Edit Event</h4>

                                    <div class="row mx-0 px-4">

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label> Title </label>

                                                <input type="text" name="title" id="name" Value="{{ $data['recruiter_event']['title'] }}" class="form-control">

                                                @error('title')

                                                    <div class="text-danger">{{ $message }}</div>

                                                @enderror

                                            </div>

                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-2">

                                            <div class="form-group mb-3">

                                                <label>About Event</label>

                                                <input type="text" name="about_event"  Value="{{ $data['recruiter_event']['about_event'] }}" class="form-control" />

                                            </div>

                                            @error('about_event')

                                            <div class="text-danger">{{ $message }}</div>

                                        @enderror

                                        </div>



                                    </div>

                                    <div class="row mx-0 px-4" id="edit_event_row_2">

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label>Description</label>

                                                <input type="text" name="description" id="phone" Value="{{ $data['recruiter_event']['description'] }}" class="form-control"

                                                placeholder="example" >

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

                                                    <input type="number" class="form-control" placeholder="Price" aria-label="Price" value="{{ $data['recruiter_event']['price'] }}" name="price" aria-describedby="basic-addon2">

                                                    @error('price')

                                                    <div class="text-danger">{{ $message }}</div>

                                                @enderror

                                                </div>

                                            </div>

                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label>Event Type</label>

                                                @if ($data['recruiter_event']['event_type']==='Public')

                                               <div>

                                                    <label>Public</label>



                                                        <input type="radio"value="Public" name="event_type" checked>

                                                        &nbsp;

                                                    <label>Private</label>

                                                        <input type="radio" value="Private" name="event_type" >



                                                    </div>

                                                @error('event_type')

                                                    <div class="text-danger">{{ $message }}</div>

                                                @enderror

                                                @elseif ($data['recruiter_event']['event_type']==='Private')

                                               <div>

                                                    <label>Public</label>



                                                        <input type="radio"value="Public" name="event_type" >

                                                        &nbsp;

                                                    <label>Private</label>

                                                        <input type="radio" value="Private" name="event_type" checked>

                                                        @error('event_type')

                                                        <div class="text-danger">{{ $message }}</div>

                                                    @enderror



                                                    </div>







                                                @endif



                                            </div>

                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label>Joining Type</label>

                                                @if ($data['recruiter_event']['joining_type']==='Free')

                                              <div>

                                                    <label>Free</label>



                                                        <input type="radio"value="Free" name="joining_type" checked>

                                                        &nbsp;

                                                    <label>Private</label>

                                                        <input type="radio" value="Paid" name="joining_type" >

                                                        @error('joining_type')

                                                        <div class="text-danger">{{ $message }}</div>

                                                    @enderror



                                                    </div>



                                                @elseif ($data['recruiter_event']['joining_type']==='Paid')

                                                <div>

                                                    <label>Free</label>



                                                        <input type="radio"value="Free" name="joining_type" >

                                                        &nbsp;

                                                    <label>Paid</label>

                                                        <input type="radio" value="Paid" name="joining_type" checked>

                                                        @error('joining_type')

                                                        <div class="text-danger">{{ $message }}</div>

                                                    @enderror



                                                    </div>





                                                @endif

                                            </div>

                                        </div>

                                        {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label>Hiring Entertainers Status</label>

                                                @if ($data['recruiter_event']['hiring_entertainers_status']==='hired')

                                                <div>

                                                    <label>Hired</label>



                                                        <input type="radio"value="hired" name="hiring_entertainers_status" checked>

                                                        &nbsp;

                                                    <label>Open For Hiring</label>

                                                        <input type="radio" value="open for hiring" name="hiring_entertainers_status" >

                                                        @error('hiring_entertainers_status')

                                                        <div class="text-danger">{{ $message }}</div>

                                                    @enderror



                                                    </div>



                                                @elseif ($data['recruiter_event']['hiring_entertainers_status']==='open for hiring')

                                               <div>

                                                    <label>Hired</label>



                                                        <input type="radio"value="hired" name="hiring_entertainers_status" >

                                                        &nbsp;

                                                    <label>Open For Hiring</label>

                                                        <input type="radio" value="open for hiring" name="hiring_entertainers_status" checked>

                                                        @error('hiring_entertainers_status')

                                                        <div class="text-danger">{{ $message }}</div>

                                                    @enderror

                                                    </div>

                                                @endif

                                            </div>

                                        </div> --}}

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label>Seats</label>

                                                <input type="number" name="seats" id="seats" Value="{{ $data['recruiter_event']['seats'] }}" class="form-control"

                                                placeholder="example" >

                                                @error('seats')

                                                    <div class="text-danger">{{ $message }}</div>

                                                @enderror

                                            </div>

                                        </div>



                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label>Date</label>



                                                <input type="date" name="date" id="date" Value="{{$data['recruiter_event']['date']}}" class="form-control"

                                                placeholder="example" >



                                                @error('date')

                                                    <div class="text-danger">{{ $message }}</div>

                                                @enderror







                                            </div>

                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label>From</label>



                                                <input type="time" name="from" id="from" Value="{{ $data['recruiter_event']['from'] }}" class="form-control"

                                                placeholder="example" >



                                                @error('from')

                                                    <div class="text-danger">{{ $message }}</div>

                                                @enderror



                                            </div>

                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2">

                                                <label>To</label>



                                                <input type="time" name="to" id="to" Value="{{ $data['recruiter_event']['to'] }}" class="form-control"

                                                 >



                                                @error('to')

                                                    <div class="text-danger">{{ $message }}</div>

                                                @enderror



                                            </div>

                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Image</label>
                                                <input type="file" name="cover_image" id="cover_image"
                                                    Value="{{ $data['recruiter_event']['cover_image'] }}" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">

                                            <div class="form-group mb-2 mt-4">

                                                <label>Feature Ads</label>

                                                @if($data['recruiter_event']['feature_status']==='0')

                                                <input type="checkbox"  name="feature_ads" data-toggle="toggle"

                                                    data-on="Featured"

                                                    data-toggle="tooltip" data-off="Unfeatured" data-onstyle="success"

                                                    data-offstyle="danger">

                                                    @else

                                                    <input type="checkbox"  name="feature_ads" data-toggle="toggle"

                                                    data-on="Featured"

                                                    data-toggle="tooltip" data-off="Unfeatured" data-onstyle="success"

                                                    data-offstyle="danger" checked>

                                            </div>

                                        </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3"  id='edit_feature_packages'>

                                            <div class="form-group mb-2">

                                                <label>Select Feature Package</label>

                                                <select name="event_feature_ads_packages_id" class="form-control">

                                                    <option value='null'>Please Select Package</option>

                                                    @foreach($data['Event_feature_ads_packages'] as $feature)

                                                    <option value="{{ $feature->id }}"{{ str_contains($data['recruiter_event']['event_feature_ads_packages_id'],$feature->id)? 'selected' : ''  }}>{{ $feature->title }} - $ {{ $feature->price }} - {{ $feature->validity }}</option>

                                                    @endforeach

                                               </select>

                                                @error('event_feature_ads_packages_id')

                                                    <div class="text-danger">{{ $message }}</div>

                                                @enderror



                                            </div>

                                        </div>

                                    </div>

                                        @endif

                                        <div class="card-footer text-center row">

                                            <div class="col">

                                                <button type="submit" class="btn btn-success mr-1 btn-bg"

                                                    id="submit">Update</button>

                                            </div>

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

                $('#edit_feature_packages').remove();

                $('#edit_event_row_2').append(` <div class="col-sm-6 pl-sm-0 pr-sm-3"  id='edit_feature_packages'>

                                            <div class="form-group mb-2">

                                                <label>Select Feature Package</label>

                                                <select name="event_feature_ads_packages_id" class="form-control">

                                                    <option value='null'>Please Select Package</option>

                                                @foreach($data['Event_feature_ads_packages'] as $feature)

                                                <option value="{{ $feature->id }}"{{ str_contains($data['recruiter_event']['event_feature_ads_packages_id'],$feature->id)? 'selected' : ''  }}>{{ $feature->title }} - $ {{ $feature->price }} - {{ $feature->validity }}</option>

                                                @endforeach



                                               </select>

                                                @error('event_feature_ads_packages_id')

                                                    <div class="text-danger">{{ $message }}</div>

                                                @enderror



                                            </div>

                                        </div>`)

            }else{

                $('#edit_feature_packages').remove();

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

                $('#edit_feature_packages').remove();

            }else{

                $(this).removeClass("btn-danger");

                $(this).removeClass("off");

                $(this).addClass("btn-success");

                $('#edit_feature_packages').remove();

                $('#edit_event_row_2').append(`<div class="col-sm-6 pl-sm-0 pr-sm-3" id='edit_feature_packages'>

                                            <div class="form-group mb-2">

                                                <label>Select Feature Package</label>

                                                <select name="event_feature_ads_packages_id" class="form-control">

                                                 <option value='null'>Please Select Package</option>

                                                @foreach($data['Event_feature_ads_packages'] as $feature)

                                                <option value="{{ $feature->id }}"{{ str_contains($data['recruiter_event']['event_feature_ads_packages_id'],$feature->id)? 'selected' : ''  }}>{{ $feature->title }} - $ {{ $feature->price }} - {{ $feature->validity }}</option>

                                                @endforeach

                                                </select>

                                                @error('event_feature_ads_packages_id')

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

