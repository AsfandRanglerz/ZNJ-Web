@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')
    <body>
        <div class="main-content">
            <section class="section">
                <div class="section-body">

                    {{-- @dd($data['entertainer_talent']) --}}
                    {{-- @dd($data['entertainer_talent']) --}}
                    <a class="btn btn-primary mb-2"
                    href="{{route('entertainer.show',$data['user_id'])}}">Back</a>
                    {{-- @dd($data) --}}
                    <form id="add_student" action="{{ route('entertainer.talent.update',['user_id'=>$data['user_id'],'entertainer_details_id'=>$data['entertainer_talent']['id']]) }}" method="POST" enctype="multipart/form-data">

                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Edit Talent</h4>
                                    <div class="row mx-0 px-4" id="edit_entertainer_row">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-2" >
                                            <div class="form-group mb-3">
                                                <label>Talent Category</label>
                                                <select name="category_id" id="myCategory"  class="form-control">
                                                    <option value='' >Please Select a Category </option>
                                                    @if(isset($data['entertainer_talent']['talentCategory']['category']))
                                                        @foreach($data['talent_categories'] as $category)
                                                        <option value="{{$category->id}}"
                                                            {{ str_contains($data['entertainer_talent']['talentCategory']['category'],$category->category)?'selected' : ''}}>{{$category->category}}</option>
                                                        @endforeach
                                                        @else
                                                        @foreach($data['talent_categories'] as $category)
                                                        <option value="{{$category->id}}"
                                                           >{{$category->category}}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                            </div>
                                            @error('category_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3" id='myCategoryDiv'>
                                            <div class="form-group mb-2">
                                                <label>Price</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon2">$</span>
                                                    </div>
                                                    <input type="number" class="form-control" placeholder="Price" aria-label="Price" name="price" Value="{{ $data['entertainer_talent']['price']}}" aria-describedby="basic-addon2">
                                                </div>
                                                @error('price')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Description</label>
                                                <input type="text" placeholder="Description" name="description" Value="{{ $data['entertainer_talent']['description']}}"  class="form-control">
                                                @error('description')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Feature Ads</label>
                                                @if($data['entertainer_talent']['feature_status']==='0')
                                                <div>
                                                <input type="checkbox" id="feature_ads"  name="feature_ads" data-toggle="toggle"
                                                    data-on="Featured"
                                                    data-toggle="tooltip" data-off="Unfeatured" data-onstyle="success"
                                                    data-offstyle="danger">
                                                </div>
                                            </div>
                                            </div>
                                                    @else
                                                    <div>
                                                    <input type="checkbox" id="feature_ads"  name="feature_ads" data-toggle="toggle"
                                                    data-on="Featured"
                                                    data-toggle="tooltip" data-off="Unfeatured" data-onstyle="success"
                                                    data-offstyle="danger" checked>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3"  id='edit_feature_packages'>
                                            <div class="form-group mb-2">
                                                <label>Select Feature Package</label>
                                                <select name="entertainer_feature_ads_packages_id" class="form-control">
                                                    <option value=''>Please Select Package</option>
                                                    @foreach($data['entertainer_feature_ads_packages'] as $feature)
                                                    <option value="{{ $feature->id }}" {{ str_contains($data['entertainer_talent']['entertainer_feature_ads_packages_id'],$feature->id)? 'selected' : ''  }}>{{ $feature->title }} - $ {{ $feature->price }} - {{ $feature->validity }}</option>
                                                    @endforeach
                                               </select>

                                                @error('entertainer_feature_ads_packages_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror

                                            </div>
                                        </div>
                                        @endif

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

    <script>
        $(document).ready(function () {
        let categoryName = $('#myCategory').find(':selected').text();
        let categoryValue = $('#myCategory').find(':selected').val();
        $('#myCategoryFieldsDiv').remove();
         $('#myCategoryFieldsDiv2').remove();
         if (categoryName === 'Actor/Actress' || categoryName === 'Host/Hostess' || categoryName === 'Model' ) {
            $("#myCategoryDiv").after(`<div class="row mx-0 px-6" id='myCategoryFieldsDiv'>
                <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Awards</label>
                                                    <input type="text" placeholder="No of awards" name="awards" value="{{ $data['entertainer_talent']['awards'] }}" class="form-control">
                                                    @error('awards')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Height</label>
                                                    <input type="text" placeholder="Height" name="height"  value="{{ $data['entertainer_talent']['height'] }}" class="form-control">
                                                    @error('height')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Weight</label>
                                                    <input type="text" placeholder="Weight" name="weight"  value="{{ $data['entertainer_talent']['weight'] }}" class="form-control">
                                                    @error('weight')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Waist</label>
                                                    <input type="text" placeholder="Waist" name="waist"  value="{{ $data['entertainer_talent']['waist'] }}" class="form-control">
                                                    @error('waist')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Shoe Size</label>
                                                    <input type="text" placeholder="Shoe Size" name="shoe_size"  value="{{ $data['entertainer_talent']['shoe_size'] }}" class="form-control">
                                                    @error('shoe_size')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Bio</label>
                                                    <input type="text" placeholder="Bio" name="bio"  value="{{ $data['entertainer_talent']['bio'] }}" class="form-control">
                                                    @error('bio')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Events Completed</label>
                                                    <input type="text" placeholder="Events Completed" name="events_completed"  value="{{ $data['entertainer_talent']['events_completed'] }}" class="form-control">
                                                    @error('events_completed')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
`);
         }else if(categoryValue!==''){
            // console.log('dsds');
            $("#myCategoryDiv").after(`<div class="row mx-0 px-6 w-100" id='myCategoryFieldsDiv2'><div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Awards</label>
                                                    <input type="text" placeholder="No of awards" name="awards" value="{{ $data['entertainer_talent']['awards'] }}"  class="form-control">
                                                    @error('awards')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Bio</label>
                                                    <input type="text" placeholder="Bio" name="bio"  value="{{ $data['entertainer_talent']['bio'] }}" class="form-control">
                                                    @error('bio')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Events Completed</label>
                                                    <input type="text" placeholder="Events Completed" name="events_completed" value="{{ $data['entertainer_talent']['events_completed'] }}"  class="form-control">
                                                    @error('events_completed')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Own Equipments</label>
                                                    <input type="text" placeholder="Equipments" name="own_equipments" value="{{ $data['entertainer_talent']['own_equipments'] }}"  class="form-control">
                                                    @error('own_equipments')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                </div>
                                            </div>
                                            </div>
`)
                                        }
                                        $("#myCategory").change(function(e) {
        e.preventDefault()
        //  let categoryName = $(this).text();
         let categoryName = $('#myCategory').find(':selected').text();
         let categoryValue = $('#myCategory').find(':selected').val();
        //  console.log('this',categoryValue,typeof(categoryValue));
        // console.log(categoryValue !== ' ');


         $('#myCategoryFieldsDiv').remove();
         $('#myCategoryFieldsDiv2').remove();
         if (categoryName === 'Actor/Actress' || categoryName === 'Host/Hostess' || categoryName === 'Model' ) {
            $("#myCategoryDiv").after(`<div class="row mx-0 px-6 w-100" id='myCategoryFieldsDiv'>
                <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Awards</label>
                                                    <input type="text" placeholder="No of awards" name="awards"  value="{{ old('awards') }}" class="form-control">
                                                    @error('awards')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Height</label>
                                                    <input type="text" placeholder="Height" name="height"  value="{{ old('height') }}" class="form-control">
                                                    @error('height')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Weight</label>
                                                    <input type="text" placeholder="Weight" name="weight"  value="{{ old('weight') }}" class="form-control">
                                                    @error('weight')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Waist</label>
                                                    <input type="text" placeholder="Waist" name="waist"  value="{{ old('waist') }}" class="form-control">
                                                    @error('waist')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Shoe Size</label>
                                                    <input type="text" placeholder="Shoe Size" name="shoe_size"  value="{{ old('shoe_size') }}" class="form-control">
                                                    @error('shoe_size')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Bio</label>
                                                    <input type="text" placeholder="Bio" name="bio"  value="{{ old('bio') }}" class="form-control">
                                                    @error('bio')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Events Completed</label>
                                                    <input type="text" placeholder="Events Completed" name="events_completed"  value="{{ old('events_completed') }}" class="form-control">
                                                    @error('events_completed')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
`);
         }else if(categoryValue!== ''){
            $("#myCategoryDiv").after(`<div class="row mx-0 px-6 w-100" id='myCategoryFieldsDiv2'><div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Awards</label>
                                                    <input type="text" placeholder="No of awards" name="awards"  value="{{ $data['entertainer_talent']['awards'] }}" class="form-control">
                                                    @error('awards')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Bio</label>
                                                    <input type="text" placeholder="Bio" name="bio"  value="{{ $data['entertainer_talent']['bio'] }}" class="form-control">
                                                    @error('bio')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Events Completed</label>
                                                    <input type="text" placeholder="Events Completed" name="events_completed" value="{{ $data['entertainer_talent']['events_completed'] }}" class="form-control">
                                                    @error('events_completed')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                                <div class="form-group mb-2">
                                                    <label>Own Equipments</label>
                                                    <input type="text" placeholder="Equipments" name="own_equipments"  value="{{ $data['entertainer_talent']['own_equipments'] }}" class="form-control">
                                                    @error('own_equipments')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                </div>
                                        </div>
                                            </div>
`)}

});

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
                $('#edit_entertainer_row').append(`<div class="col-sm-6 pl-sm-0 pr-sm-3"  id='edit_feature_packages'>
                                            <div class="form-group mb-2">
                                                <label>Select Feature Package</label>
                                                <select name="entertainer_feature_ads_packages_id" class="form-control">
                                                    <option value=''>Please Select Package</option>
                                                    @foreach($data['entertainer_feature_ads_packages'] as $feature)
                                                    <option value="{{ $feature->id }}" {{ str_contains($data['entertainer_talent']['entertainer_feature_ads_packages_id'],$feature->id)? 'selected' : ''  }}>{{ $feature->title }} - $ {{ $feature->price }} - {{ $feature->validity }}</option>
                                                    @endforeach
                                               </select>
                                                @error('entertainer_feature_ads_packages_id')
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
                $('#edit_entertainer_row').append(`<div class="col-sm-6 pl-sm-0 pr-sm-3" id='edit_feature_packages'>
                                            <div class="form-group mb-2">
                                                <label>Select Feature Package</label>
                                                <select name="entertainer_feature_ads_packages_id" class="form-control">
                                                <option value=''>Please Select Package</option>
                                                    @foreach($data['entertainer_feature_ads_packages'] as $feature)
                                                    <option value="{{ $feature->id }}" {{ str_contains($data['entertainer_talent']['entertainer_feature_ads_packages_id'],$feature->id)? 'selected' : ''  }}>{{ $feature->title }} - $ {{ $feature->price }} - {{ $feature->validity }}</option>
                                                    @endforeach
                                               </select>
                                                @error('entertainer_feature_ads_packages_id')
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
