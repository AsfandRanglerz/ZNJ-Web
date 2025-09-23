@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <div class="card">
            <div class="card-header">
              <h4>Ads Packages</h4>
            </div>

            {{-- @dd($data['recruiter']) --}}
            <div class="card-body">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Event</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Entertainer</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Venue</a>
                </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">

                    <div class="card-body table-striped table-bordered table-responsive">
                        {{-- <a class="btn btn-success mb-3"
                        href="{{route('recruiter.create')}}">Add</a> --}}
                        {{-- <a class="btn btn-success"  href="">Add</a> --}}
                        <table class="table" id="table_event_feature">
                            <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Validity</th>
                                    <th>Created At</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                             @foreach($data['event_ads_packages'] as $package)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $package->title }}</td>
                                        <td>${{ $package->price }}</td>
                                        <td>{{ $package->validity }}</td>
                                        <td>{{ $package->created_at }}</td>
                                        <td
                                        style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                        <a class="btn btn-success"
                                                href="{{route('feature_ads_packages.event.edit.index', $package->id)}}">Edit</a>
                                                {{-- <form method="POST" action="{{ route('recruiter.destroy', $recruiter->id) }}">
                                                    @csrf
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button type="submit" class="btn btn-xs btn-danger btn-flat show_confirm" data-toggle="tooltip" >Delete</button>
                                                </form> --}}
                                                   </td>
                                                </tr>
                              @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="card-body table-striped table-bordered table-responsive">
                        {{-- <a class="btn btn-success mb-3"
                                       href="{{route('entertainer.create')}}">Add</a> --}}
                        <table class="table" id="table_talent_feature">
                            <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Validity</th>
                                    <th>Created At</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                             @foreach($data['talent_ads_packages'] as $package )



                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $package->title }}</td>
                                        <td>${{ $package->price }}</td>
                                        <td>{{ $package->validity }}</td>
                                        <td>{{ $package->created_at }}</td>

                                        <td
                                        style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                        <a class="btn btn-success"
                                       href="{{route('feature_ads_packages.talent.edit.index', $package->id)}}">Edit</a>
                                                   </td>
                                                </tr>
                              @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="card-body table-striped table-bordered table-responsive">
                        {{-- <a class="btn btn-success mb-3"
                        href="{{route('venue.create')}}">Add</a> --}}
                        <table class="table" id="table_venue_feature">
                            <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Validity</th>
                                    <th>Created At</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                             @foreach($data['venue_ads_packages'] as $package)
                             {{-- @dd($venue['venues']) --}}
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $package->title }}</td>
                                        <td>${{ $package->price }}</td>
                                        <td>{{ $package->validity }}</td>
                                        <td>{{ $package->created_at }}</td>

                                        <td
                                        style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                        {{-- <a class="btn btn-success"
                                                href="{{route('venue.show', $venue->id)}}">Venue</a> --}}
                                        <a class="btn btn-success"
                                                href="{{route('feature_ads_packages.venue.edit.index', $package->id)}}">Edit</a>
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
          </div>



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
        $('#table_event_feature').DataTable();
        $('#table_talent_feature').DataTable();
        $('#table_venue_feature').DataTable();


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
