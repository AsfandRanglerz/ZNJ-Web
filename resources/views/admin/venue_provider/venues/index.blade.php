@extends('admin.layout.app')

@section('title', 'venue')

@section('content')

    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Venue</h4>

                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                <a class="btn btn-primary mb-3"
                                href="{{route('admin.user.index')}}">Back</a>
                                <a class="btn btn-success mb-3"
                                       href="{{route('venue-providers.venue.add',$data['user_id'])}}">Add Venue</a>
                                <table class="table" id="table_id_venue">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Description</th>
                                            <th>Seats</th>
                                            <th>Stands</th>
                                            <th>Amenities</th>
                                            <th>Feature Ads</th>
                                            <th>Opening_time</th>
                                            <th>Closing_time</th>
                                            <th>Created_At</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     @foreach($data['venue'] as $venue)


                                            {{-- @dd($entertainer->title) --}}
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $venue->title }}</td>
                                                <td>{{ $venue->venueCategory->category ??'' }}</td>
                                                <td>{{ $venue->description }}</td>
                                                <td>{{ $venue->seats }}</td>
                                                <td>{{ $venue->stands }}</td>
                                                <td>{{ $venue->amenities }}</td>
                                                @if (isset($venue->venueFeatureAdsPackage->title))
                                                @if (str_contains($venue->venueFeatureAdsPackage->title,'Silver'))
                                                    <th>
                                                        <button type="button" class="btn btn-secondary"
                                                            style="background-color: silver; border-color: silver">{{ $venue->venueFeatureAdsPackage->title }}</button>
                                                    </th>
                                                @elseif (str_contains($venue->venueFeatureAdsPackage->title,'Gold'))
                                                    <th>
                                                        <button type="button" class="btn btn-secondary"
                                                            style="background-color: gold; border-color: gold">{{ $venue->venueFeatureAdsPackage->title }}
                                                            </button>
                                                    </th>
                                                @else
                                                    <th>
                                                        <button type="button" class="btn btn-secondary" style="background-color: purple; border-color: purple">{{ $venue->venueFeatureAdsPackage->title }}</button>
                                                    </th>
                                                @endif
                                                @else
                                                <th><button class="btn btn-danger">Unfeatured</button></th>
                                               @endif

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

                                                <td>
                                                    <div style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                        
                                                        {{-- <a class="btn btn-success"
                                                       href="{{route('entertainer.edit', $entertainer->id)}}">Categories</a> --}}
                                                       <a class="btn btn-primary"
                                                       href="{{route('venue-providers.venue.photo.show', ['user_id'=>$data['user_id'],'venue_id'=>$venue->id])}}">Photos</a>
                                                       <a class="btn btn-success"
                                                       href="{{route('venue-providers.venue.venue_pricings.index',['user_id'=>$data['user_id'],'venue_id'=>$venue->id] )}}">Packages</a>
                                                        <a class="btn btn-info"
                                                       href="{{route('venue-providers.venue.edit',['user_id'=>$data['user_id'],'venue_id'=>$venue->id])}})}}">Edit</a>
                                                                <form method="get" action="{{ route('venue-providers.venue.delete', $venue->id) }}">
                                                                    @csrf
                                                                    <input name="_method" type="hidden" value="DELETE">
                                                                    <button type="submit" class="btn btn-danger btn-flat show_confirm" data-toggle="tooltip" >Delete</button>
                                                                </form>
                                                    </div>
                                                           </td>
                                                        </tr>
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
@section ('scripts')
@if (\Illuminate\Support\Facades\Session::has('message'))
<script>
    toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
</script>
@endif
<script>
    $(document).ready(function() {
        $('#table_id_venue').DataTable();
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">

$('.show_confirm').click(function(event) {
          var form =  $(this).closest("form");
          var name = $(this).data("name");
          event.preventDefault();
          swal({
              title: `Are you sure you want to delete this record?`,
              text: "If you delete this, it will be gone forever.",
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              form.submit();
            }
          });
      });

</script>
@endsection

