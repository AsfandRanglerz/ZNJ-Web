@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')
    <body>
        {{-- @dd($data['price_package']['venues_id']) --}}
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <a class="btn btn-primary mb-2" href="{{route('venue-providers.venue.venue_pricings.index',['user_id'=>$data['user_id'],'venue_id'=>$data['price_package']['venues_id']])}}">Back</a>
                    {{-- @dd($data['price_package']) --}}
                    <form id="add_student" action="{{ route('venue-providers.venue.venue_pricings.update',['user_id'=>$data['user_id'],'venue_pricing_id'=>$data['price_package']['id']]) }}" name="form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Edit Price Package</h4>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Price</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon2">$</span>
                                                    </div>
                                                    <input type="number" class="form-control" placeholder="Price" aria-label="Price" name="price" Value="{{ $data['price_package']['price'] }}" aria-describedby="basic-addon2">
                                                </div>
                                                @error('price')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Opening Time</label>
                                                <input type="time"  name="opening_time" id="opening_time"  value="{{ $data['price_package']['opening_time'] }}" class="form-control">
                                                @error('opening_time')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Closing Time</label>
                                                <input type="time" placeholder="" name="closing_time" id="closing_time" value="{{ $data['price_package']['closing_time'] }}"  class="form-control"
                                                    >
                                                @error('closing_time')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group">
                                                <label>Day</label>
                                                <select name="day"  class="form-control">
                                                    <option value="" {{ str_contains($data['price_package']['day'], 'null') ? 'selected' :'' }}>Please Select a Category </option>
                                                    <option value="Monday"  {{ str_contains($data['price_package']['day'], 'Monday') ? 'selected' :'' }}>Monday</option>
                                                    <option value="Tuesday"  {{ str_contains($data['price_package']['day'], 'Tuesday') ? 'selected' :'' }}>Tuesday</option><option value="Wednesday"  {{ str_contains($data['price_package']['day'], 'Wednesday') ? 'selected' :'' }}>Wednesday</option>
                                                    <option value="Thursday"  {{ str_contains($data['price_package']['day'], 'Thursday') ? 'selected' :'' }}>Thursday</option>
                                                    <option value="Friday"  {{ str_contains($data['price_package']['day'], 'Friday') ? 'selected' :'' }}>Friday</option>
                                                    <option value="Saturday"  {{ str_contains($data['price_package']['day'], 'Saturday') ? 'selected' :'' }}>Saturday</option>
                                                    <option value="Sunday"  {{ str_contains($data['price_package']['day'], 'Sunday') ? 'selected' :'' }}>Sunday</option>
                                                    </select>
                                                @error('day')
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
<script>
//     var inputEle = document.getElementById('timeInput');

// function onTimeChange(){
// const a=document.getElementById("timeInput").value;
// console.log('thisssssssssss',a.split(':'));
// }
// function onTimeChange() {
//   var timeSplit = inputEle.value.split(':'),
//     hours,
//     minutes,
//     meridian;
//   hours = timeSplit[0];
//   minutes = timeSplit[1];
//   if (hours > 12) {
//     meridian = 'PM';
//     hours -= 12;
//   } else if (hours < 12) {
//     meridian = 'AM';
//     if (hours == 0) {
//       hours = 12;
//     }
//   } else {
//     meridian = 'PM';
//   }
// //   alert(hours + ':' + minutes + ' ' + meridian);
// ish=hours + ':' + minutes + ' ' + meridian
// document.forms['form']['time'].value = ish;
// document.getElementById("timeInput").value = ish ;

// $(document).ready(function () {
// alert($('#timeInput').val(hours + ':' + minutes + ' ' + meridian))


// });

// }
</script>

@endsection
