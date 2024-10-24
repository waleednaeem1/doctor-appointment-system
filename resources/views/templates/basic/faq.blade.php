@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="contact-item-section pd-t-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 mrb-30">
                    <div class="faq-wrapper">
                        @foreach($faqs as $faq)
                            <div class="faq-item">
                                <h3 class="faq-title"><span class="title">{{ __($faq->data_values->question) }} </span><span class="right-icon"></span></h3>
                                <div class="faq-content">
                                    <p>{{ __($faq->data_values->answer) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


