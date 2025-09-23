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
                                <div class="col-12">
                                    <h4>Deleted Events</h4>

                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">

                                    <table class="table responsive" id="table_id_talent">

                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">User Name</th>
                                                <th scope="col">User Email</th>
                                                <th scope="col">User Role</th>
                                                <th scope="col">Title</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">From</th>
                                                <th scope="col">To</th>
                                                <th scope="col">Venue Title & Email</th>
                                                {{-- <th scope="col">Venue Email</th> --}}
                                                <th scope="col">Entertainers Email</th>




                                                {{-- <th scope="col">Accept</th>
                                                <th scope="col">Reject</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($events as $event)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if(!empty($event->user) && !empty($event->user->name))
                                                        {{ $event->user->name }}
                                                    @else
                                                        <span class="badge badge-danger">No record found</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!empty($event->user) && !empty($event->user->email))
                                                        {{ $event->user->email }}
                                                    @else
                                                        <span class="badge badge-danger">No record found</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!empty($event->user) && !empty($event->user->role))
                                                        {{ $event->user->role }}
                                                    @else
                                                        <span class="badge badge-danger">No record found</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!empty($event->title))
                                                        {{ $event->title }}
                                                    @else
                                                        <span class="badge badge-danger">No record found</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!empty($event->date))
                                                        {{ $event->date }}
                                                    @else
                                                        <span class="badge badge-danger">No record found</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!empty($event->from))
                                                        {{ $event->from }}
                                                    @else
                                                        <span class="badge badge-danger">No record found</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!empty($event->to))
                                                        {{ $event->to }}
                                                    @else
                                                        <span class="badge badge-danger">No record found</span>
                                                    @endif
                                                </td>
                                                <!-- Loop through event venues -->
                                                <td>
                                                    @if(count($event->eventVenues) > 0)
                                                        @foreach($event->eventVenues as $eventVenue)
                                                            @if(!empty($eventVenue->title))
                                                                {{ $eventVenue->title }}
                                                            @else
                                                                <span class="badge badge-danger">No record found</span>
                                                            @endif
                                                            <br>,
                                                            @if(!empty($eventVenue->user) && !empty($eventVenue->user->email))
                                                                {{ $eventVenue->user->email }}
                                                            @else
                                                                <span class="badge badge-danger">No record found</span>
                                                            @endif
                                                            <br>
                                                        @endforeach
                                                    @else
                                                        <span class="badge badge-danger">No record found</span>
                                                    @endif
                                                </td>
                                                <!-- Loop through entertainer details -->
                                                <td>
                                                    @if(count($event->entertainerDetails) > 0)
                                                        @foreach($event->entertainerDetails as $eventVenue)
                                                            @if(!empty($eventVenue->user) && !empty($eventVenue->user->email))
                                                                {{ $eventVenue->user->email }}
                                                            @else
                                                                <span class="badge badge-danger">No record found</span>
                                                            @endif
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <span class="badge badge-danger">No record found</span>
                                                    @endif
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

@section('scripts')
    @if (\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{ \Illuminate\Support\Facades\Session::get('message') }}');
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $('#table_id_talent').DataTable();
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">
        $('.show_confirm').click(function(event) {
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
    </script>
@endsection
