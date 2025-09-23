@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <div class="card">
            <div class="card-header">
                <h4>Feature Payments</h4>
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
                                        <th>Sender Role</th>
                                        <th>Package</th>
                                        <th>Event/Talent/Venue</th>
                                        <th>Payment Charges</th>
                                        <th>Type</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>@if(isset($payment->user->name)){{ $payment->user->name }}@endif</td>
                                            <td>@if(isset($payment->user->role)){{ $payment->user->role }} @endif</td>
                                            <td>@if(isset($payment->entertainerFeaturePackage->title)){{ $payment->entertainerFeaturePackage->title }}@elseif(isset($payment->venuePackage->title)) {{$payment->venuePackage->title}} @elseif(isset($payment->eventPackage->title)) {{$payment->eventPackage->title}} @endif</td>
                                            <td>@if(isset($payment->event->title)){{ $payment->event->title }} @elseif(isset($payment->talent->talentCategory->category)){{ $payment->talent->talentCategory->category }} @elseif(isset($payment->venue->venueCategory->category)){{ $payment->venue->venueCategory->category }} @endif</td>
                                            <td>{{ $payment->payment }}</td>
                                            <td>{{ $payment->type }}</td>
                                            <td>{{ $payment->created_at }}</td>
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
