@extends('web.layout.apps')

@section('title', 'Feature Ad Payment')

@section('content')
<section class="d-flex justify-content-center align-items-center py-5" style="min-height:80vh; background: #0a0a0a;">
    <div class="card shadow-lg text-center border-0 p-5" style="max-width: 480px; background: #1c1c1c; color: #fff; border-radius: 20px;">
        <h2 class="mb-4 text-gradient fw-bold">Feature Ad Payment</h2>

        <div class="mb-3">
            <p class="fs-5 mb-1 text-secondary">Amount to Pay:</p>
            <h3 class="text-success fw-bold">PKR {{ number_format($amount, 2) }}</h3>
        </div>

        <div class="my-4">
            <button id="payButton" 
                    class="btn btn-lg w-100 py-3 fw-bold"
                    style="background: linear-gradient(90deg, #ff6a00, #ee0979); border:none; border-radius:10px; color:white; transition:0.3s;">
                <i class="fas fa-credit-card me-2"></i> Proceed to Payment
            </button>
        </div>

        <p class="text-muted small mt-3">
            Secure payment powered by <strong>Bank Alfalah</strong> & <strong>Mastercard</strong>.
        </p>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://bankalfalah.gateway.mastercard.com/static/checkout/checkout.min.js"
        data-error="errorCallback"
        data-cancel="cancelCallback"></script>

<script type="text/javascript">
    Checkout.configure({
        session: { id: "{{ $sessionId }}" }
    });

    document.getElementById("payButton").onclick = function () {
        Checkout.showPaymentPage();
    };
</script>
@endsection
