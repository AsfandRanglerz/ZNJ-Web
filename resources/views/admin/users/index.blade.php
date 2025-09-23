@extends('admin.layout.app')

@section('title', 'Dashboard')

@section('content')
    <style>
    .toggle[data-toggle="toggle"] {
        width: 98.3855px!important;
        height: 35.7986px!important;
    }
    </style>
    <!-- Main Content -->

    <div class="main-content">

        <div class="card">

            <div class="card-header">

                <h4>Users</h4>

            </div>



            {{-- @dd($data['recruiter']) --}}

            <div class="card-body">

                <ul class="nav nav-tabs" id="myTab" role="tablist">

                    <li class="nav-item">

                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                            aria-controls="home" aria-selected="true">Recruiters</a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                            aria-controls="profile" aria-selected="false">Entertainers</a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                            aria-controls="contact" aria-selected="false">Venue Providers</a>

                    </li>

                </ul>

                <div class="tab-content" id="myTabContent">

                    <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">



                        <div class="card-body table-striped table-bordered table-responsive">

                            <a class="btn btn-success mb-3" href="{{ route('recruiter.create') }}">Add</a>

                            {{-- <a class="btn btn-success"  href="">Add</a> --}}

                            <table class="table responsive" id="table_id_1">

                                <thead>

                                    <tr>

                                        <th>Sr.</th>

                                        <th>Name</th>
                                        <th>Image</th>

                                        <th>Email</th>

                                        <th>Phone</th>

                                        <th>Company</th>

                                        <th>Designation</th>

                                        <th>Events</th>

                                        <th>Created At</th>





                                        <th scope="col">Action</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($data['recruiter'] as $recruiter)
                                        <tr>

                                            <td>{{ $loop->iteration }}</td>

                                            <td>
                                                @if(!empty($recruiter->name))
                                                    {{ $recruiter->name }}
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($recruiter->image))
                                                    <img src="{{ asset($recruiter->image) }}" alt="" height="50"
                                                        width="50" class="image">
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($recruiter->email))
                                                    {{ $recruiter->email }}
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($recruiter->phone))
                                                    {{ $recruiter->phone }}
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($recruiter->company))
                                                    {{ $recruiter->company }}
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($recruiter->designation))
                                                    {{ $recruiter->designation }}
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>

                                            @if (count($recruiter['events']) === 0)
                                                <td><span class="badge badge-danger">No record found</span></td>
                                            @elseif (count($recruiter['events']) > 1)
                                                <td>
                                                    @php $titles = array_column(json_decode($recruiter['events'], true), 'title'); @endphp
                                                    @if(!empty($titles))
                                                        {{ implode(', ', $titles) }}
                                                    @else
                                                        <span class="badge badge-danger">No record found</span>
                                                    @endif
                                                </td>
                                            @else
                                                <td>
                                                    @if(!empty($recruiter['events'][0]['title']))
                                                        {{ $recruiter['events'][0]['title'] }}
                                                    @else
                                                        <span class="badge badge-danger">No record found</span>
                                                    @endif
                                                </td>
                                            @endif
                                            <td>
                                                @if(!empty($recruiter->created_at))
                                                    {{ $recruiter->created_at }}
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>





                                            <td>
                                                <div style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                    <form method="POST" action="{{ route('user.verify', $recruiter->id) }}">
                                                        @csrf
                                                        @method('POST')
                                                        @if ($recruiter->is_verify == '0')
                                                            <input type="checkbox"  name="is_verify" data-toggle="toggle "
                                                                data-on="Verified" data-off="Un-Verify" data-onstyle="success"
                                                                data-offstyle="danger">
                                                        @else
                                                            <input type="checkbox" name="is_verify" data-toggle="toggle"
                                                                data-on="Verified" data-off="Un-Verify" data-onstyle="success"
                                                                data-offstyle="danger" checked>
                                                        @endif
                                                    </form>
    
                                                    <a class="btn btn-success"
                                                        href="{{ route('recruiter.show', $recruiter->id) }}">Event</a>
    
                                                    <a class="btn btn-info"
                                                        href="{{ route('recruiter.edit', $recruiter->id) }}">Edit</a>
    
                                                    <form method="POST"
                                                        action="{{ route('recruiter.destroy', $recruiter->id) }}">
    
                                                        @csrf
    
                                                        <input name="_method" type="hidden" value="DELETE">
    
                                                        <button type="submit" class="btn btn-danger btn-flat show_confirm"
                                                            data-toggle="tooltip">Delete</button>
    
                                                    </form>

                                                </div>

                                            </td>

                                        </tr>
                                    @endforeach



                                </tbody>

                            </table>

                        </div>

                    </div>

                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                        <div class="card-body table-striped table-bordered table-responsive">

                            <a class="btn btn-success mb-3" href="{{ route('entertainer.create') }}">Add</a>

                            <table class="table responsive" id="table_id_2">

                                <thead>

                                    <tr>

                                        <th>Sr.</th>

                                        <th>Name</th>
                                        <th>Image</th>


                                        <th>Email</th>

                                        <th>Phone</th>

                                        <th>DOB</th>

                                        <th>Country</th>

                                        <th>City</th>

                                        <th>Gender</th>

                                        <th>Nationality



                                        </th>

                                        <th>Category</th>

                                        <th>Created at</th>

                                        <th scope="col">Action</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($data['entertainer'] as $entertainer)
                                        <tr>

                                            <td>{{ $loop->iteration }}</td>

                                            <td>
                                                @if(!empty($entertainer->name))
                                                    {{ $entertainer->name }}
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($entertainer->image))
                                                    <img src="{{ asset($entertainer->image) }}" alt="" height="50"
                                                        width="50" class="image">
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($entertainer->email))
                                                    {{ $entertainer->email }}
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($entertainer->phone))
                                                    {{ $entertainer->phone }}
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($entertainer->dob))
                                                    {{ $entertainer->dob }}
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($entertainer->country))
                                                    {{ $entertainer->country }}
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($entertainer->city))
                                                    {{ $entertainer->city }}
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($entertainer->gender))
                                                    {{ $entertainer->gender }}
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($entertainer->nationality))
                                                    {{ $entertainer->nationality }}
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>

                                            {{-- @dd(json_decode($entertainer['entertainerDetail'][0]['talentCategory'],true)) --}}

                                            {{-- @dd(count($entertainer['entertainerDetail'])) --}}



                                            {{-- {!! $dat[] = json_decode($entertainer['entertainerDetail'][0]['talentCategory'],true)  !!} --}}

                                            {{-- {!! $dat[]=json_decode($entertainer['entertainerDetail'][0]['talentCategory'],true) !!} --}}

                                            {{-- @dd($dat) --}}



                                            @if (count($entertainer['entertainerDetail']) === 1)
                                                <td>
                                                    @if(!empty($entertainer['entertainerDetail'][0]['talentCategory']['category']))
                                                        {{ $entertainer['entertainerDetail'][0]['talentCategory']['category'] }}
                                                    @else
                                                        <span class="badge badge-danger">No record found</span>
                                                    @endif
                                                </td>
                                            @elseif (count($entertainer['entertainerDetail']) > 1)
                                                @php $dat = []; @endphp
                                                @for ($i = 0; $i < count($entertainer['entertainerDetail']); $i++)
                                                    @php $dat[] = json_decode($entertainer['entertainerDetail'][$i]['talentCategory'], true); @endphp
                                                @endfor
                                                <td>
                                                    @if(!empty($dat))
                                                        {{ implode(',', array_column($dat, 'category')) }}
                                                    @else
                                                        <span class="badge badge-danger">No record found</span>
                                                    @endif
                                                </td>
                                            @else
                                                <td><span class="badge badge-danger">No record found</span></td>
                                            @endif
                                            <td>
                                                @if(!empty($entertainer->created_at))
                                                    {{ $entertainer->created_at }}
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>



                                            <td>
                                                <div style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                    <form method="POST" action="{{ route('user.verify', $entertainer->id) }}">
                                                        @csrf
                                                        @method('POST')
                                                        @if ($entertainer->is_verify == '0')
                                                            <input type="checkbox" name="is_verify" class="p-1"
                                                                data-toggle="toggle" data-on="Verified" data-off="Un-Verify"
                                                                data-onstyle="success" data-offstyle="danger"
                                                                >
                                                        @else
                                                            <input type="checkbox" name="is_verify" data-toggle="toggle"
                                                                data-on="Verified" data-off="Un-Verify"
                                                                data-onstyle="success" data-offstyle="danger" checked >
                                                        @endif
                                                    </form>
    
                                                    <a class="btn btn-success"
                                                        href="{{ route('entertainer.show', $entertainer->id) }}">Talent</a>
    
                                                    <a class="btn btn-info"
                                                        href="{{ route('entertainer.edit', $entertainer->id) }}">Edit</a>
    
                                                    <form method="POST"
                                                        action="{{ route('entertainer.destroy', $entertainer->id) }}">
    
                                                        @csrf
    
                                                        <input name="_method" type="hidden" value="DELETE">
    
                                                        <button type="submit" class="btn btn-danger btn-flat show_confirm"
                                                            data-toggle="tooltip">Delete</button>
    
                                                    </form>
                                                </div>

                                            </td>

                                        </tr>
                                    @endforeach



                                </tbody>

                            </table>

                        </div>







                    </div>





                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

                        <div class="card-body table-striped table-bordered table-responsive">

                            <a class="btn btn-success mb-3" href="{{ route('venue.create') }}">Add</a>

                            <table class="table responsive" id="table_id_3">

                                <thead>

                                    <tr>

                                        <th>Sr.</th>

                                        <th>Name</th>
                                        <th>Image</th>


                                        <th>Email</th>

                                        <th>Phone</th>

                                        <th>Venues</th>

                                        <th>Created At</th>

                                        <th scope="col">Action</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($data['venue'] as $venue)
                                        {{-- @dd($venue['venues']) --}}

                                        <tr>

                                            <td>{{ $loop->iteration }}</td>

                                            <td>
                                                @if(!empty($venue->name))
                                                    {{ $venue->name }}
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($venue->image))
                                                    <img src="{{ asset($venue->image) }}" alt="" height="50"
                                                        width="50" class="image">
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($venue->email))
                                                    {{ $venue->email }}
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if(!empty($venue->phone))
                                                    {{ $venue->phone }}
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>
                                            @if (count($venue['venues']) === 1)
                                                <td>
                                                    @if(!empty($venue['venues'][0]['venueCategory']['category']))
                                                        {{ $venue['venues'][0]['venueCategory']['category'] }}
                                                    @else
                                                        <span class="badge badge-danger">No record found</span>
                                                    @endif
                                                </td>
                                            @elseif (count($venue['venues']) > 1)
                                                @php $venue_category = []; @endphp
                                                @for ($i = 0; $i < count($venue['venues']); $i++)
                                                    @php $venue_category[] = json_decode($venue['venues'][$i]['venueCategory'], true); @endphp
                                                @endfor
                                                <td>
                                                    @if(!empty($venue_category))
                                                        {{ implode(',', array_column($venue_category, 'category')) }}
                                                    @else
                                                        <span class="badge badge-danger">No record found</span>
                                                    @endif
                                                </td>
                                            @else
                                                <td><span class="badge badge-danger">No record found</span></td>
                                            @endif
                                            <td>
                                                @if(!empty($venue->created_at))
                                                    {{ $venue->created_at }}
                                                @else
                                                    <span class="badge badge-danger">No record found</span>
                                                @endif
                                            </td>



                                            <td>
                                                <div style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
                                                    <form method="POST" action="{{ route('user.verify', $venue->id) }}">
                                                        @csrf
                                                        @method('POST')
                                                        @if ($venue->is_verify == '0')
                                                            <input type="checkbox" name="is_verify" data-toggle="toggle"
                                                                data-on="Verified" data-off="Un-Verify"
                                                                data-onstyle="success" data-offstyle="danger">
                                                        @else
                                                            <input type="checkbox" name="is_verify" data-toggle="toggle"
                                                                data-on="Verified" data-off="Un-Verify"
                                                                data-onstyle="success" data-offstyle="danger" checked>
                                                        @endif
                                                    </form>
    
                                                    <a class="btn btn-success"
                                                        href="{{ route('venue.show', ['user_id' => $venue->id]) }}">Venue</a>
    
                                                    <a class="btn btn-info"
                                                        href="{{ route('venue.edit', ['user_id' => $venue->id]) }}">Edit</a>
    
                                                    <form method="POST"
                                                        action="{{ route('venue.destroy', ['user_id' => $venue->id]) }}">
    
                                                        @csrf
    
                                                        <input name="_method" type="hidden" value="DELETE">
    
                                                        <button type="submit" class="btn btn-danger btn-flat show_confirm"
                                                            data-toggle="tooltip">Delete</button>
    
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

    </div>







    </div>

@endsection



@section('scripts')

    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif

    <script>
        $(document).ready(function() {

           $('#table_id_1').DataTable();

            // Initialize DataTables for visible tables only
            $('#table_id_2').DataTable();
            $('#table_id_3').DataTable();

            // Fix DataTables columns when tab is shown
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
            });
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

    <script type="text/javascript">
      $(document).ready(function () {
       $(document).on('click', '.show_confirm', function(event){

            var form = $(this).closest("form");

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
    });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('change', 'input[name="is_verify"]', function() {
                var checkbox = $(this); // Store a reference to the checkbox

                var isChecked = checkbox.prop("checked");
                var confirmationTitle = isChecked ? "Are you sure you want to Verify this User?" :
                    "Are you sure you want to Un-Verify this User?";

                swal({
                    title: confirmationTitle,
                    icon: "warning",
                    buttons: ["Cancel", "OK"],
                    dangerMode: true,
                }).then((willSubmit) => {
                    if (willSubmit) {
                        // You can update the checkbox properties here
                        // checkbox.removeClass("btn-danger off").addClass("btn-success");
                        $('#feature_packages').remove();
                        // User clicked "OK," submit the form
                        checkbox.closest('form').submit();
                    } else {
                        // User clicked "Cancel," handle the cancel action here
                        // For example, remove a package with id "feature_packages"
                        $('#feature_packages').remove();
                        // Reset checkbox classes if needed
                        if (checkbox.closest('.toggle').hasClass('btn-success')) {
                            checkbox.closest('.toggle').removeClass('btn-success').addClass('btn-danger off');
                        } else {
                            checkbox.closest('.toggle').removeClass('btn-danger off').addClass('btn-success');
                        }
                        // checkbox.closest('.toggle').removeClass('btn-danger off').addClass("btn-success");
                    }
                });
            });
        });
    </script>

@endsection
