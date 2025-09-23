@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')
    <body>

        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <a class="btn btn-primary mb-2"
                    href="{{route('venue-providers.venue.venue_pricings.index',['user_id'=>$data['user_id'],'venue_id'=>$data['venue_id']])}}">Back</a>
                    {{-- @dd($data) --}}
                    <form id="add_student" action="{{ route('venue-providers.venue.venue_pricings.store',['user_id'=>$data['user_id'],'venue_id'=>$data['venue_id']]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Add Price Package</h4>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group">
                                                <label>Price Package</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon2">$</span>
                                                    </div>
                                                    <input type="number" class="form-control" placeholder="Price" aria-label="Price" name="price" value="{{ old('price') }}" aria-describedby="basic-addon2">
                                                </div>
                                                @error('price_package')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Opening Time</label>
                                                <input type="time"  name="opening_time" id="opening_time"  value="{{ old('opening_time') }}" class="form-control"
                                                    >
                                                @error('opening_time')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Closing Time</label>
                                                <input type="time" placeholder="" name="closing_time" id="closing_time" value="{{ old('closing_time') }}"  class="form-control"
                                                    >
                                                @error('closing_time')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group  mb-2">
                                                <label>Day</label>
                                                <select name="day" value="{{ old('day') }}" class="form-control">
                                                    <option value="">Please Select a Category </option>
                                                    <option value="Monday">Monday</option>
                                                    <option value="Tuesday">Tuesday</option><option value="Wednesday">Wednesday</option>
                                                    <option value="Thursday">Thursday</option>
                                                    <option value="Friday">Friday</option>
                                                    <option value="Saturday">Saturday</option>
                                                    <option value="Sunday">Sunday</option>
                                                    </select>
                                                @error('day')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
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
                            </div>
                        </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </body>
@endsection

@section ('scripts')
@if (\Illuminate\Support\Facades\Session::has('message'))
<script>
    toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
</script>
@endif

@endsection



