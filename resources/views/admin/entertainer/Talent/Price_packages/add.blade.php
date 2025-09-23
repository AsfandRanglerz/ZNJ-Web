@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')
    <body>
        {{-- @dd($data) --}}
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <a class="btn btn-primary mb-3"
                    href="{{route('entertainer.talent.price_packages.index',['user_id'=>$data['user_id'],'entertainer_details_id'=>$data['entertainer_details_id']])}}">Back</a>
                    {{-- @dd($data) --}}
                    <form id="add_student" action="{{ route('entertainer.talent.price_packages.store',['user_id'=>$data['user_id'],'entertainer_details_id'=>$data['entertainer_details_id']]) }}" method="POST" enctype="multipart/form-data">
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
                                                    <input type="number" class="form-control" placeholder="Price" aria-label="Price" name="price_package" value="{{ old('price_package') }}" aria-describedby="basic-addon2">
                                                </div>
                                                @error('price_package')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Number of Hours  </label>
                                                <input type="number" placeholder="Number of Hours" name="time" value="{{ old('time') }}"  class="form-control">
                                                @error('time')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                        </div>

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
