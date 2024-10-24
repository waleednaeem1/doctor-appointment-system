@extends($activeTemplate . 'layouts.frontend')
@section('content')
<div class="containern ptb-80">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <form action="{{route('deposit.insertpayment')}}" class="form-inline" method="post">
                @csrf
                <input type="hidden" name="currency">
                <input type="hidden" name="trx" value="{{$trx}}">
                <input type="hidden" name="doctor_id" value="{{$doctorId}}">
                <input type="hidden" name="currency" value="USD">
                
                <div class="card custom--card">
                    <div class="card-header">
                        <h5 class="card-title">@lang('Appointment Amount Deposit')</h5>
                    </div>
                    <div class="card-body">
                        
                        <div class="row justify-content-center ml-b-20">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <input type="number" value="4242424242424242" name="number" placeholder="@lang('Card Number')" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==16) return false;" required>
                            </div>
                            <div class="form-group">
                                <input type="number" name="cvc" value="123" placeholder="@lang('CVC')" required>
                            </div>
                            <div class="form-group">
                                <select class="form-select" name="exp_year" aria-label="Expire Year" required >
                                    <option selected value="">Expire Year</option>
                                    @for ($i=2025;$i<=2035;$i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                    
                                  </select>
                            </div>
                            <div class="form-group">
                                <select class="form-select" name="exp_month" aria-label="Expire Month" required >
                                    <option selected value="">Expire Month</option>
                                    @for ($i=1;$i<=12;$i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                    
                                  </select>
                            </div>
                            
                            
                        </div>
                       
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" name="amount" class="form-control" value="{{ $fees }}" autocomplete="off" readonly required>
                                    <span class="input-group-text">{{ $general->cur_text }}</span>
                                </div>
                            </div>
                        </div>   
                      
                        </div>
                        
                        <div class="mt-3 preview-details d-none">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Limit')</span>
                                    <span><span class="min fw-bold">0</span> {{__($general->cur_text)}} - <span class="max fw-bold">0</span> {{__($general->cur_text)}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Charge')</span>
                                    <span><span class="charge fw-bold">0</span> {{__($general->cur_text)}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Payable')</span> <span><span class="payable fw-bold"> 0</span> {{__($general->cur_text)}}</span>
                                </li>
                                <li class="list-group-item justify-content-between d-none rate-element">

                                </li>
                                <li class="list-group-item justify-content-between d-none in-site-cur">
                                    <span>@lang('In') <span class="method_currency"></span></span>
                                    <span class="final_amo fw-bold">0</span>
                                </li>
                                <li class="list-group-item justify-content-center crypto_currency d-none">
                                    <span>@lang('Conversion with') <span class="method_currency"></span> @lang('and final value will Show on next step')</span>
                                </li>
                            </ul>
                        </div>
                        <button type="submit" class="btn cmn-btn w-100 my-2 submitConfirmation">@lang('Submit')</button>
                        <p class="msg text-danger d-none"></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- <div class="row justify-content-center">
        <div class="col-lg-6">
            <form id="payment-form">
                @csrf
                <input type="hidden" name="trx" value="{{$trx}}">
                <input type="hidden" name="doctor_id" value="{{$doctorId}}">
                <input type="hidden" name="currency" value="USD">
                
                <div class="card custom--card">
                    <div class="card-header">
                        <h5 class="card-title">@lang('Appointment Amount Deposit')</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="card-element">Card Information</label>
                            <div id="card-element" class="form-control"></div>
                        </div>
                        <div id="card-errors" role="alert"></div>
                        
                        <button type="submit" class="btn cmn-btn w-100 my-2">@lang('Submit')</button>
                        <p class="msg text-danger d-none"></p>
                    </div>
                </div>
            </form>
        </div>
    </div> --}}
</div>
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";
            $('select[name=gateway]').change(function(){
                if(!$('select[name=gateway]').val()){
                    $('.preview-details').addClass('d-none');
                    return false;
                }
                var resource = $('select[name=gateway] option:selected').data('gateway');
                var fixed_charge = parseFloat(resource.fixed_charge);
                var percent_charge = parseFloat(resource.percent_charge);
                var rate = parseFloat(resource.rate)
                if(resource.method.crypto == 1){
                    var toFixedDigit = 8;
                    $('.crypto_currency').removeClass('d-none');
                }else{
                    var toFixedDigit = 2;
                    $('.crypto_currency').addClass('d-none');
                }
                $('.min').text(parseFloat(resource.min_amount).toFixed(2));
                $('.max').text(parseFloat(resource.max_amount).toFixed(2));
                var amount = parseFloat($('input[name=amount]').val());
                if (!amount) {
                    amount = 0;
                }
                if(amount <= 0){
                    $('.preview-details').addClass('d-none');
                    return false;
                }
                $('.preview-details').removeClass('d-none');
                var charge = parseFloat(fixed_charge + (amount * percent_charge / 100)).toFixed(2);
                $('.charge').text(charge);
                var payable = parseFloat((parseFloat(amount) + parseFloat(charge))).toFixed(2);
                $('.payable').text(payable);
                var final_amo = (parseFloat((parseFloat(amount) + parseFloat(charge)))*rate).toFixed(toFixedDigit);
                $('.final_amo').text(final_amo);
                if (resource.currency != '{{ $general->cur_text }}') {
                    var rateElement = `<span class="fw-bold">@lang('Conversion Rate')</span> <span><span  class="fw-bold">1 {{__($general->cur_text)}} = <span class="rate">${rate}</span>  <span class="method_currency">${resource.currency}</span></span></span>`;
                    $('.rate-element').html(rateElement)
                    $('.rate-element').removeClass('d-none');
                    $('.in-site-cur').removeClass('d-none');
                    $('.rate-element').addClass('d-flex');
                    $('.in-site-cur').addClass('d-flex');
                }else{
                    $('.rate-element').html('')
                    $('.rate-element').addClass('d-none');
                    $('.in-site-cur').addClass('d-none');
                    $('.rate-element').removeClass('d-flex');
                    $('.in-site-cur').removeClass('d-flex');
                }
                $('.method_currency').text(resource.currency);
                $('input[name=currency]').val(resource.currency);
                $('input[name=amount]').on('input');


                //submit disabled when out of min-max
                let minAmount = parseFloat(resource.min_amount).toFixed(2);
                let maxAmount = parseFloat(resource.max_amount).toFixed(2);
                if (minAmount > amount || maxAmount < amount) {
                    $(".submitConfirmation").prop('disabled', true);
                    $('.msg').text('Please follow deposit limit!').removeClass('d-none')
                } else {
                    $(".submitConfirmation").prop('disabled', false);
                    $('.msg').addClass('d-none')
                }

                //gateway-selected
                $('.gateway-elected').addClass('d-none');
            });
            $('input[name=amount]').on('input',function(){
                $('select[name=gateway]').change();
                $('.amount').text(parseFloat($(this).val()).toFixed(2));
            });
        })(jQuery);
    </script>

    {{-- <script>
        var stripe = Stripe('pk_test_51JcrmwHL1XXZRbXgQHVQR842wlU5B0YYLqhjEFjfVwYDkr1LO7VDJT2MVgb7tMbKLPJSBseATxMsvMFxD1uVJwBh00Cz0IPI3s'); // Use your STRIPE_KEY here
        var elements = stripe.elements();
        var card = elements.create('card');
        card.mount('#card-element');
     
        var form = document.getElementById('payment-form');
     
        form.addEventListener('submit', function(event) {
            event.preventDefault();
     
            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    // Show error message to the user
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Token successfully created, send it to your server for processing
                    var token = result.token;
                    // Include the token in your form data and submit it to your server
                    // You can use JavaScript to add an input element with the token value to your form
                    var tokenInput = document.createElement('input');
                    tokenInput.setAttribute('type', 'hidden');
                    tokenInput.setAttribute('name', 'stripeToken');
                    tokenInput.setAttribute('value', token.id);
                    form.appendChild(tokenInput);
                    form.submit();
                }
            });
        });
     </script> --}}
@endpush
