@extends('web.layout.app')

@section('title','Thanks')

@section('content')
<div class="container mt-5 mb-5 text-center d-flex flex-column align-items-center form-container ">

    <div>
        <h1 class="mb-3 text-warning thank-you-heading">Thank You!</h1>
    </div>
     
    <div class="text-white mt-2">
        <p class="thank-you-paragraph">Your payment has been processed successfully, your booking is confirmed and your ticket has been generated.</p>
    </div>
<div class="d-flex gap-5 mt-5">
   <a href="{{ url('mytickets') }}" class="thank-you-anchors"> ->Go To Ticket Page</a>
   <a href="{{ url('events') }}" class="thank-you-anchors">->Go To Events Page</a>
   <a href="{{ url('dashboard') }}" class="thank-you-anchors">->Go To Dashboard Page</a>
</div>

</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    });
</script>
@endsection