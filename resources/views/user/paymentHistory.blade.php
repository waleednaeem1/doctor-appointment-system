@extends('user.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">
            <div class="col-lg-8 mrb-60">
                <div class="blog-item">
                    <div class="blog-content">
                        <div
                            class="blog-details-content-header d-flex flex-wrap align-items-center justify-content-between">
                            <h3 class="title">{{ __($userPet->name) }}</h3>
                        </div>
                        Age: <span class="title">{{ __($userPet->age) }} {{ __($userPet->age_in) }}</span>
                        <p>{{ __($userPet->short_description) }}</p>
                    </div>
                    @if(!$userPet->attachments->isEmpty())
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                          <button class="nav-link active" id="nav-image-tab" data-bs-toggle="tab" data-bs-target="#nav-image" type="button" role="tab" aria-controls="nav-image" aria-selected="true">Images</button>
                          <button class="nav-link" id="nav-video-tab" data-bs-toggle="tab" data-bs-target="#nav-video" type="button" role="tab" aria-controls="nav-video" aria-selected="false">Videos</button>
                          <button class="nav-link" id="nav-previous-tab" data-bs-toggle="tab" data-bs-target="#nav-previous" type="button" role="tab" aria-controls="nav-previous" aria-selected="false">Previous Record</button>
                        </div>
                      </nav>
                      <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-image" role="tabpanel" aria-labelledby="nav-image-tab" tabindex="0">
                            <div class="row">
                                @foreach($userPet->attachments as $attachment)
                                @php
                                    $extension = pathinfo($attachment->attachment, PATHINFO_EXTENSION);
                                @endphp
                                <div class="col-md-4">
                                    <div class="blog-thumb">
                                        @if($attachment->attachment_type=='image')
                                        <img src="{{ getImage('assets/images/pets/' . @$attachment->attachment) }}"
                                            alt="@lang('pet-image')" style="max-width: 100%; height: auto;">
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-video" role="tabpanel" aria-labelledby="nav-video-tab" tabindex="0">
                            <div class="row">
                                @foreach($userPet->attachments as $attachment)
                                @php
                                   // $extension = pathinfo($attachment->attachment, PATHINFO_EXTENSION);
                                @endphp
                                <div class="col-md-8">
                                    <div class="blog-thumb">
                                        @if($attachment->attachment_type=='video')
                                        <video controls width="100%" height="auto">
                                            <source src="{{ asset('assets/images/pets/' . @$attachment->attachment) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-previous" role="tabpanel" aria-labelledby="nav-previous-tab" tabindex="0">
                            <div class="row">
                                @foreach($userPet->attachments as $attachment)

                                <div class="col-md-12">
                                    <div class="blog-thumb">

                                            @if($attachment->attachment_type == 'previous_record')
                                            {{-- <iframe src="https://docs.google.com/gview?url={{ asset('assets/images/pets/' . @$attachment->attachment) }}&embedded=true" frameborder="0" style="width:100%;height:300px;" >
                                            </iframe> --}}
                                            <iframe src="{{ asset('assets/images/pets/' . @$attachment->attachment) }}" frameborder="0" width="100%" height="400px"></iframe>
                                            @endif


                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                      </div>
                      @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('user.petslist') }}" class="btn btn-sm btn-outline--primary"><i class="la la-undo"></i>
        @lang('Back') </a>
@endpush
