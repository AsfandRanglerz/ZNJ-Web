@extends('admin.layout.app')
@section('title', 'Privacy Policy')
@section('content')
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>About Us</h4>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td title="{{ strip_tags($data->description ?? '') }}">
                                            <div style="
                                                display: -webkit-box;
                                                -webkit-line-clamp: 3;
                                                -webkit-box-orient: vertical;
                                                overflow: hidden;
                                                text-overflow: ellipsis;
                                                max-height: 7.5em;
                                            ">
                                                {!! $data->description ?? '' !!}
                                            </div>
                                        </td>

                                        <td><a href="{{route('aboutUs.edit',$data->id)}}"><i class="fas fa-edit"></i></a></td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('js')
    @if(\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{\Illuminate\Support\Facades\Session::get('message')}}');
        </script>
    @endif
@endsection
