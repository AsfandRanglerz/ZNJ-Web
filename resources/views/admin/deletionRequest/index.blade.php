@extends('admin.layout.app')

@section('title', 'Account Deletion Request')

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

                <h4>Account Deletion Request</h4>

            </div>



            {{-- @dd($data['recruiter']) --}}

            <div class="card-body">

                <ul class="nav nav-tabs" id="myTab" role="tablist">

                    <li class="nav-item">

                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                            aria-controls="home" aria-selected="true">Users</a>

                    </li>

                </ul>

                <div class="tab-content" id="myTabContent">

                    <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="card-body table-striped table-bordered table-responsive">

                            <table class="table responsive" id="table_id_1">

                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Accept</th>
                                        <th scope="col">Reject</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>
                                            <form method="post" action="{{url('admin/delete-account')}}">
                                                @csrf
                                                <input name="id" type="hidden" value="{{$user->id}}">
                                                <button type="submit" class="btn btn-success btn-flat show_confirm"
                                                    data-toggle="tooltip">Accept</button>
                                            </form>
                                        </td>
                                        <td>
                                            <form method="post" action="{{url('admin/reject-account')}}">
                                                @csrf
                                                <input name="id" type="hidden" value="{{$user->id}}">
                                                <button type="submit" class="btn btn-danger btn-flat show_confirms"
                                                    data-toggle="tooltip">Reject</button>
                                            </form>
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

            $('#table_id_2').DataTable();
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script>
    $('.show_confirm').click(function(event) {
    var form = $(this).closest("form");
    var name = $(this).data("name");
    event.preventDefault();
    swal({
            title: `Are you sure you want to accept this record?`,
            text: "If you accept this, it will be gone forever.",
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
$('.show_confirms').click(function(event) {
    var form = $(this).closest("form");
    var name = $(this).data("name");
    event.preventDefault();
    swal({
            title: `Are you sure you want to reject this request?`,
            text: "If you reject this, it will be gone forever.",
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
