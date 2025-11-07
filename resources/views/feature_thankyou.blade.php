@extends('web.layout.apps')

@section('title', 'Payment Successful')

@section('content')
<div class="container mt-5 mb-5 text-center d-flex flex-column align-items-center form-container">

    <div>
        <h1 class="mb-3 text-success thank-you-heading">Payment Successful!</h1>
    </div>
     
    <div class="text-white mt-2">
        <p class="thank-you-paragraph">
            Thank you for your payment! Your transaction has been processed successfully.
        </p>
        @if(!empty($orderId))
            <p class="thank-you-paragraph">
                {{-- Your Order ID: <strong>{{ $orderId }}</strong> --}}
            </p>
        @endif
    </div>

{{-- --}}

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

        @if(isset($errors) && $errors->any())
            @foreach($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif

    });
</script>
@endsection
