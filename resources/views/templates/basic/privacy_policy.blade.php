@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="contact-item-section pd-t-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="contact-form-area">
                        <div class="row ml-b-30" style="margin-bottom: 30px;">
                            @if (isset($page_content) || isset($page_content->data_values))
                                <?php echo trans($page_content->data_values->description_nic); ?>
                            @else
                                Data Updated Soon!                           
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


