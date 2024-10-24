@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="blog-section blog-details-section ptb-80">
        <div class="container">
            <div class="row justify-content-center ml-b-20">
                <div class="col-lg-8 mrb-60">
                    <div class="blog-item">
                        <div class="blog-content pt-4 ">
                            <div class="blog-details-content-header d-flex flex-wrap align-items-center justify-content-between alert alert-dark ">
                                <h4 class="title">{{ __($userPet->name) }}<br><small class="lead">Age: </small><span class="badge bg-secondary">{{ __($userPet->age) }} {{ __($userPet->age_in) }}</span> </h4>


                                <div class="card" style="width: 30rem;">

                                    <div class="card-body">
                                        <p class="card-text">{{ __($userPet->short_description) }}</p>
                                    </div>


                                </div>
                            </div>


                        </div>

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
                                    <div class="col-md-12">
                                        <div class="card-body">
                                        </div>
                                    </div>
                                </div>
                                <div class="row updateDivAfterImageDelete">
                                    @foreach($imageattachments as $attachment)
                                        <div class="col-md-4">
                                            @php
                                                $extension = pathinfo($attachment->attachment, PATHINFO_EXTENSION);
                                            @endphp
                                            @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                <div class="blog-thumb">
                                                    <img src="{{ getImage('assets/images/pets/' . @$attachment->attachment) }}"
                                                        alt="@lang('pet-image')" style="width: 280px; height: 250px;">
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-video" role="tabpanel" aria-labelledby="nav-video-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-body">

                                        </div>
                                    </div>
                                </div>
                                <div class="row updateDivAfterVideoDelete">
                                    @foreach($videoattachments as $attachment)
                                        <div class="col-md-4">
                                            @php
                                                $extension = pathinfo($attachment->attachment, PATHINFO_EXTENSION);
                                            @endphp
                                            @if(in_array($extension, ['mp4', 'mov', 'wmv', 'avi', 'mkv', 'flv', 'webm']) && $attachment->attachment_type == 'video')
                                                <div class="mt-3">
                                                    <video controls width="270px" height="200px">
                                                        <source src="{{ asset('assets/images/pets/' . $attachment->attachment) }}">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-previous" role="tabpanel" aria-labelledby="nav-previous-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-body">
                                        </div>
                                    </div>
                                </div>
                                <div class="row updateDivAfterPreviousRecordDelete">
                                    @foreach($previousrecordattachments as $attachment)
                                        <div class="col-md-4">
                                            @php
                                            $extension = pathinfo($attachment->attachment, PATHINFO_EXTENSION);
                                            @endphp
                                            @if(in_array($extension, ['pdf','doc', 'docx', 'jpg', 'jpeg', 'png', 'gif', 'webp','mp4', 'x-m4v']) && $attachment->attachment_type == 'previous_record')
                                                @if(in_array($extension, ['pdf']))
                                                    <iframe src="{{ asset('assets/images/pets/' . @$attachment->attachment) }}" frameborder="0" width="275px" height="280px"></iframe>
                                                @elseif(in_array($extension, ['doc', 'docx']))
                                                    <a href="{{ asset('assets/images/pets/' . @$attachment->attachment) }}" download="{{ @$userPet->name.'_previousrecord' }}" class="link-primary">Download File</a>
                                                @elseif(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                    <img src="{{ getImage('assets/images/pets/' . @$attachment->attachment) }}"
                                                    alt="@lang('previous_record')" style="width: 280px; height: 280px; text-align:center;">
                                                    @elseif(in_array($extension, ['mp4', 'x-m4v']))
                                                    <video width="270px" height="270px" controls>
                                                        <source src="{{ asset('assets/images/pets/' . @$attachment->attachment) }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @else
                                                File type not valid.
                                            {{-- <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ asset('assets/images/pets/' . @$attachment->attachment) }}"
                                                frameborder="0" width="100%" height="400px"></iframe> --}}
                                                @endif
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>



                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('fbComment')
    @php echo loadExtension('fb-comment') @endphp
@endpush
