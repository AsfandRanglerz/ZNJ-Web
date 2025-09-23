@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <div class="card">
            <div class="card-header">
                <h4>Payment</h4>
            </div>
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="card-body table-striped table-bordered table-responsive">
                            {{-- <a class="btn btn-success mb-3" href="{{ route('recruiter.create') }}">Add</a> --}}
                            <table class="table responsive" id="table_id_1">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Sender</th>
                                        <th>Receiver</th>
                                        <th>Receiver Role</th>
                                        <th>Event</th>
                                        <th>Payment Charges</th>
                                        <th>Type</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        <th>Payment</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if (isset($payment->user->name))
                                                    {{ $payment->user->name }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($payment->entertainer->name))
                                                    {{ $payment->entertainer->name }}
                                                @elseif(isset($payment->venue->name))
                                                    {{ $payment->venue->name }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($payment->entertainer->role))
                                                    {{ $payment->entertainer->role }}
                                                @elseif(isset($payment->venue->role))
                                                    {{ $payment->venue->role }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($payment->event->title))
                                                    {{ $payment->event->title }}
                                                @endif
                                            </td>
                                            <td>{{ $payment->payment }}</td>
                                            <td>{{ $payment->type }}</td>
                                            <td>{{ $payment->created_at }}</td>
                                            <td>
                                                @if ($payment->status == 1)
                                                    <div class="badge badge-success badge-shadow">Payed</div>
                                                @else
                                                    <div class="badge badge-danger badge-shadow">Un-Payed</div>
                                                @endif
                                            </td>
                                            {{-- <td>
                                                <input type="checkbox" id="featured_ads" name="featured_ads" data-toggle="toggle" data-on="Payable" data-off="Unpayable" data-onstyle="success" data-offstyle="danger">
                                            </td> --}}
                                            <td style="display: flex; align-items: center; justify-content: center; column-gap: 8px">
                                                @if ($payment->status == 0)
                                                    <form action="{{ route('payment.status', ['id' => $payment->id]) }}" method="post">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger show_confirm">

                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-toggle-right">
                                                                <rect x="1" y="5" width="22" height="14" rx="7" ry="7"></rect>
                                                                <circle cx="8" cy="12" r="3"></circle>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @elseif ($payment->status == 1)
                                                    <form action="{{ route('payment.status', ['id' => $payment->id]) }}" method="post">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success confirm">

                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-toggle-left">
                                                                <rect x="1" y="5" width="22" height="14" rx="7" ry="7"></rect>
                                                                <circle cx="16" cy="12" r="3"></circle>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>

                                                {{-- <a class="btn btn-info"
                                                href="{{ route('user.edit', $officer->id) }}">Edit</a>
                                            <form method="post"
                                                action="{{ route('user.destroy', $officer->id) }}">
                                                @csrf
                                                <input name="_method" type="hidden" value="DELETE">
                                                <button type="submit" class="btn btn-danger btn-flat show_confirm"
                                                    data-toggle="tooltip">Delete</button>
                                            </form> --}}

                                            {{-- <td
                                                style="display: flex;align-items: center;justify-content: center;column-gap: 8px">
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
                                            </td> --}}
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
        });
    </script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
    <script type="text/javascript">
        $('.show_confirm').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            Swal.fire({
                title: "Are you sure you want to approve the Payment?",
                icon: "success",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
    <script type="text/javascript">
        $('.confirm').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            Swal.fire({
                title: "Are you sure you want to change the Payment status?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>



@endsection
