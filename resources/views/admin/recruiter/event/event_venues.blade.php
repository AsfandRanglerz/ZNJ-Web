@extends('admin.layout.app')

@section('title', 'index')

@section('content')

    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    {{-- @dd($data['event_entertainers']) --}}
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Venue</h4>
                                </div>
                            </div>
                            {{-- @dd($data) --}}
                            <div class="card-body table-striped table-bordered table-responsive">
                                <a class="btn btn-primary mb-3"
                                href="{{route('recruiter.show',$data['user_id'])}}">Back</a>
                                {{-- <a class="btn btn-success mb-3"
                                       href="{{route('entertainer.talent.add',$data['user_id'])}}">Add Talent</a> --}}
                                <table class="table" id="table_id_event_venues">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Description</th>
                                            <th>Seats</th>
                                            <th>Stands</th>
                                            <th>Opening_time</th>
                                            <th>Closing_time</th>
                                            <th>Created_At</th>
                                            {{-- <th scope="col">Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                     @foreach($data['event_venues'] as $venue)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $venue->title }}</td>
                                                <td>{{ $venue->category }}</td>
                                                <td>{{ $venue->description }}</td>
                                                <td>{{ $venue->seats }}</td>
                                                <td>{{ $venue->stands }}</td>
                                                @if( explode(':',$venue->opening_time)[0]>=12)
                                                <td>{{ $venue->opening_time }} PM</td>
                                                @else
                                                 <td>{{ $venue->opening_time }} AM</td>
                                                @endif
                                                @if( explode(':',$venue->closing_time)[0]>=12)
                                                <td>{{ $venue->closing_time }} PM</td>
                                                @else
                                                 <td>{{ $venue->closing_time }} AM</td>
                                                @endif
                                               <td>{{ $venue->created_at }}</td>
                                            </tr>
                                                {{-- <td
                                                style="display: flex;align-items: center;justify-content: center;column-gap: 8px"> --}}
                                                {{-- <a class="btn btn-success"
                                               href="{{route('entertainer.edit', $entertainer->id)}}">Categories</a> --}}
                                               {{-- <a class="btn btn-primary"
                                               href="{{route('entertainer.photo.show', $entertainer->id)}}">Photos</a>
                                                <a class="btn btn-info"
                                               href="{{route('entertainer.talent.edit', $entertainer->id)}}">Edit</a>
                                                        <form method="get" action="{{ route('entertainer.talent.delete', $entertainer->id) }}">
                                                            @csrf
                                                            <input name="_method" type="hidden" value="DELETE">
                                                            <button type="submit" class="btn btn-xs btn-danger btn-flat show_confirm" data-toggle="tooltip" >Delete</button>
                                                        </form>
                                                           </td>
                                                        </tr> --}}
                                      @endforeach

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

@section('scripts')
<script>
    $(document).ready(function(){
        $('#table_id_event_venues').DataTable()

    })
</script>

@endsection
