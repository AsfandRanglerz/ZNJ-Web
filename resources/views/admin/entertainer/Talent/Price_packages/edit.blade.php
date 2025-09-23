@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')
    <body>
        {{-- @dd($data) --}}
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <a class="btn btn-primary mb-3"
                    href="{{route('entertainer.talent.price_packages.index',['user_id'=>$data['user_id'],'entertainer_details_id'=>$data['price_package']['entertainer_details_id']])}}">Back</a>
                    {{-- @dd($data['price_package']) --}}
                    <form id="add_student" action="{{ route('entertainer.talent.price_packages.update',['user_id'=>$data['user_id'],'price_package_id'=>$data['price_package']['id']]) }}" name="form" method="POST" enctype="multipart/form-data">
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
                                                    <input type="number" class="form-control" placeholder="Price" aria-label="Price" name="price_package" Value="{{ $data['price_package']['price_package'] }}" aria-describedby="basic-addon2">
                                                </div>
                                                @error('price_package')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Number of Hours</label>
                                                <input type="number" placeholder="Number of Hours"  name="time"  value='{{ $data['price_package']['time'] }}'  id="timeInput" class="form-control">
                                                @error('time')
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
