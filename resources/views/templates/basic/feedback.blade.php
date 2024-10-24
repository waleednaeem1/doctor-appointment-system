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
                                <!-- overview-section start -->
                                <section class="overview-section pd-b-80">
                                    <div class="container">
                                        <div class="overview-area mrb-40">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="overview-tab-wrapper">
                                                        <ul class="tab-menu">
                                                            <li>@lang('Report')</li>
                                                            <li class="active">@lang('Feedback')</li>

                                                            @if (loadFbComment() != null)
                                                                <li>@lang('Feedback')</li>
                                                            @endif
                                                        </ul>
                                                        <div class="tab-cont">
                                                            <div class="tab-item">
                                                                <div class="overview-tab-content ml-b-30">

                                                                    <div class="overview-content">
                                                                        <div
                                                                        class="overview-review-header">
                                                                        <div class="overview-review-header-left mrb-10">
                                                                            <!-- contact-section start -->
                                                                        <section class="contact-section">
                                                                            <div class="container">
                                                                                <div class="row justify-content-center mrb-40">
                                                                                    <div class="col-lg-12">
                                                                                        <div class="contact-form-area">
                                                                                            <form class="contact-form" action="{{ route('reportSubmit') }}" method="POST">
                                                                                                @csrf
                                                                                                <div class="row justify-content-center ml-b-20">
                                                                                                    <div class="col-lg-12">
                                                                                                        <div class="form-group">
                                                                                                            <label>Message</label>
                                                                                                            <input type="hidden" name="type" value="report">
                                                                                                            <textarea placeholder="@lang('Your Message')" name="feedback" required>{{ old('feedback') }}</textarea>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <button type="submit" class="submit-btn">@lang('Send')</button>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </section>
                                                                        <!-- contact-section end -->
                                                                        </div>
                                                                    </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-item">
                                                                <div class="overview-tab-content">
                                                                    <div
                                                                        class="overview-review-header">
                                                                        <div class="overview-review-header-left mrb-10">
                                                                            Please enter your feedback here
                                                                        <!-- contact-section start -->
                                                                        <section class="contact-section">
                                                                            <div class="container">
                                                                                <div class="row justify-content-center mrb-40">
                                                                                    <div class="col-lg-12">
                                                                                        <div class="contact-form-area">
                                                                                            <form class="contact-form" action="{{ route('feedbackSubmit') }}" method="POST" enctype="multipart/form-data">
                                                                                                @csrf
                                                                                                <div class="row justify-content-center ml-b-20">
                                                                                                    <div class="col-lg-12">
                                                                                                        <div class="form-group">
                                                                                                            <label>Experience</label>
                                                                                                            <select name="experience" class="form-control" required>
                                                                                                                <option value="" selected disabled>@lang('Select Experience')</option>
                                                                                                                <option value="excellent">Excellent</option>
                                                                                                                <option value="satisfied">Satisfied</option>
                                                                                                                <option value="poor">Poor</option>
                                                                                                            </select>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col-lg-12">
                                                                                                        <div class="form-group">
                                                                                                            <label>Feedback</label>
                                                                                                            <input type="hidden" name="type" value="reaction">
                                                                                                            <textarea placeholder="@lang('Your Feedback')" name="feedback" required>{{ old('feedback') }}</textarea>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <button type="submit" class="submit-btn">@lang('Send Feedback')</button>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </section>
                                                                        <!-- contact-section end -->
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <!-- overview-section end -->
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- feedback-item-section end -->
@endsection
