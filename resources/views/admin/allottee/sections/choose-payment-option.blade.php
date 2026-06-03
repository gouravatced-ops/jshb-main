<div>
    <!-- HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                Choose You Payment Option
            </h1>
            <p class="page-subtitle">
                Select your preferred payment method for property booking
            </p>
        </div>
        <button
            class="btn-ghost"
            onclick="window.close();">
            <i class="fa-solid fa-arrow-left"></i>
            Back to List
        </button>
    </div>


    {{-- Payment Options Section --}}
    <div class="payment-plan-wrapper mt-4">

        <div class="row g-4">

            {{-- EMI PLAN --}}
            <div class="col-lg-6">

                <form method="POST"
                    action="{{ route('admin.allottees.payment-option', $allottee) }}">
                    @csrf
                    <input type="hidden"
                        name="payment_option"
                        value="emi">

                    <button type="submit"
                        class="payment-card payment-card-emi w-100">

                        <div class="payment-icon">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>

                        <div class="payment-content">

                            <div class="payment-top">
                                <h4>EMI Plan</h4>

                                <!-- <span class="popular-badge">
                                    Popular
                                </span> -->
                            </div>

                            <p>
                                Pay the amount in flexible monthly installments
                                with better financial comfort.
                            </p>

                            <ul class="payment-features">
                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    Easy Monthly Installments
                                </li>

                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    Lower Initial Burden
                                </li>

                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    Flexible Payment Schedule
                                </li>
                            </ul>

                            <div class="payment-action">
                                Choose EMI Plan
                                <i class="fa-solid fa-arrow-right ms-2"></i>
                            </div>

                        </div>
                    </button>

                </form>
            </div>

            {{-- ONE TIME PAYMENT --}}
            <div class="col-lg-6">

                <form method="POST"
                    action="{{ route('admin.allottees.payment-option', $allottee) }}">
                    @csrf
                    <input type="hidden"
                        name="payment_option"
                        value="one_time">

                    <button type="submit"
                        class="payment-card payment-card-full w-100">

                        <div class="payment-icon">
                            <i class="fa-solid fa-wallet"></i>
                        </div>

                        <div class="payment-content">

                            <div class="payment-top">
                                <h4>One Time Payment</h4>

                                <!-- <span class="save-badge">
                                    Best Value
                                </span> -->
                            </div>

                            <p>
                                Pay the full amount at once and enjoy faster
                                processing & additional benefits.
                            </p>

                            <ul class="payment-features">
                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    Complete Payment Instantly
                                </li>

                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    Faster Approval Process
                                </li>

                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    Additional Payment Benefits
                                </li>
                            </ul>

                            <div class="payment-action">
                                Choose One Time Payment
                                <i class="fa-solid fa-arrow-right ms-2"></i>
                            </div>

                        </div>
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>