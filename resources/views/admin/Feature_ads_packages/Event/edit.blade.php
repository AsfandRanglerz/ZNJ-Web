@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')
    <body>
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <a class="btn btn-primary mb-2"
                    href="{{route('feature_ads_packages.index')}}">Back</a>
                    <form id="add_student" action="{{ route('feature_ads_packages.event.update',$data['event_ads_package']['id']) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Edit Event Packages</h4>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label> Title </label>
                                                <input type="text" name="title"  Value="{{$data['event_ads_package']['title'] }}"class="form-control" readonly>
                                                @error('title')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-2">
                                            <div class="form-group mb-3">
                                                <label>Validity Count</label>
                                                <input type="number" name="validity_count" Value="{{explode(' ',$data['event_ads_package']['validity'])[0] }}"class="form-control" />
                                            </div>
                                            @error('validity_count')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-2">
                                            <div class="form-group mb-3">
                                                <label>Validity</label>
                                                <select name="validity" class="form-control">
                                                @if (explode(' ',$data['event_ads_package']['validity'])[1]==='Days')
                                                <option>Please Select Validity</option>
                                                <option value="Days" selected>Day</option>
                                                <option value="Week">Week</option>
                                                <option value="Month">Month</option>
                                                <option value="Year">Year</option>
                                                @elseif(explode(' ',$data['event_ads_package']['validity'])[1]==='Week')
                                                <option>Please Select Validity</option>
                                                <option value="Days">Day</option>
                                                <option value="Week" selected>Week</option>
                                                <option value="Month">Month</option>
                                                <option value="Year">Year</option>
                                                @elseif(explode(' ',$data['event_ads_package']['validity'])[1]==='Month')
                                                <option>Please Select Validity</option>
                                                <option value="Days">Day</option>
                                                <option value="Week">Week</option>
                                                <option value="Month" selected>Month</option>
                                                <option value="Year">Year</option>
                                                @elseif(explode(' ',$data['event_ads_package']['validity'])[1]==='Year')
                                                <option>Please Select Validity</option>
                                                <option value="Days">Day</option>
                                                <option value="Week">Week</option>
                                                <option value="Month">Month</option>
                                                <option value="Year" selected>Year</option>
                                                @else
                                                <option>Please Select Validity</option>
                                                <option value="Days">Day</option>
                                                <option value="Week">Week</option>
                                                <option value="Month">Month</option>
                                                <option value="Year">Year</option>

                                                @endif

                                                </select>

                                            </div>
                                            @error('validity')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Price</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon2">PKR</span>
                                                    </div>
                                                    <input type="number" class="form-control" placeholder="Price" aria-label="Price" name="price" Value="{{$data['event_ads_package']['price'] }}" aria-describedby="basic-addon2">
                                                </div>
                                                @error('price')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center row">
                                        <div class="col">
                                            <button type="submit" class="btn btn-success mr-1 btn-bg"
                                                id="submit">Update</button>
                                        </div>
                                    </div>
                                    {{-- <d class="row mx-0 px-4"> --}}

                                    {{-- </d --}}
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
