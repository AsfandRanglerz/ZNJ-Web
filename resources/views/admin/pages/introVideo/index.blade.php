@extends('admin.layout.app')

@section('title', 'index')

@section('content')
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-6">
                                    <h4>Introduction Video</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                <table class="table" id="table_id">
                                    <thead>
                                        <tr>
                                            <th>Video</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <tr>
                                               <td>
                                                @if (isset($data->video))
                                                <video id="video" controls="controls autoplay"  width="300" height="150">
                                                    <source id="mp4" src="{{ asset('public/storage/'.$data->video)}}">
                                                    </video>
                                                  </td>
                                                    </tr>
                                    </tbody>
                                </table>

                                <form  action="{{ route('intro-video.update', $data->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @method('PATCH')
                                    @csrf
                                    <div class="card">
                                        <h4 class="text-center my-4"></h4>
                                        <div class="row mx-0 px-4">
                                            <div class="col-sm-12 pl-sm-12 pr-sm-12">
                                                <div class="form-group mb-2">
                                                    <label>Video Upload</label>
                                                    <input type="file" name="video" id="video"

                                                         class="form-control">
                                                    @error('video')
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
                                </form>
                                @else
                                <form  action="{{ route('intro-video.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="card">
                                        <h4 class="text-center my-4"></h4>
                                        <div class="row mx-0 px-4">
                                            <div class="col-sm-12 pl-sm-12 pr-sm-12">
                                                <div class="form-group mb-2">
                                                    <label>Video Add</label>
                                                    <input type="file" name="video" id="video"

                                                         class="form-control">
                                                    @error('video')
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
                                </form>

                                @endif

                            </div>
                        </div>


                </div>

            </div>
        </section>

    </div>

@endsection

@section ('scripts')
@if (\Illuminate\Support\Facades\Session::has('message'))
<script>
    toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
</script>
@endif

@endsection

