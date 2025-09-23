@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')
    <body>
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <a class="btn btn-primary mb-3"
                    href="{{route('venue-providers.venue.photo.show',['user_id'=>$photo['user_id'],'venue_id'=>$photo['venue_id']])}}">Back</a>
                    {{-- @dd($data) --}}
                    <form id="add_student" action="{{ route('venue-providers.venue.photo.update',['user_id'=>$photo['user_id'],'venue_id'=>$photo['venue_id'],'photo_id'=>$photo['photo']['id']]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <h4 class="text-center my-4">Edit Photo</h4>
                                    <div class="row mx-0 px-4">
                                        <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                            <div class="form-group mb-2">
                                                <label>Choose Images</label>
                                                <input type="file" placeholder="example" name="photos" id="photos" Value="{{$photo['photo']['event_photos'] }}" class="form-control" multiple="multiple">
                                                @error('images')
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

@endsection
