@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="blog-section blog-details-section ptb-80">
        <div class="container">
            <div class="row justify-content-center ml-b-20">
                <div class="col-lg-8 mrb-60">
                    <div class="blog-item">
                        {{-- <div class="blog-content pt-4 ">
                            <div class="blog-details-content-header d-flex flex-wrap align-items-center justify-content-between alert alert-dark ">
                                <h4 class="title">{{ __($userPet->name) }}<br><small class="lead">Age: </small><span class="badge bg-secondary">{{ __($userPet->age) }} {{ __($userPet->age_in) }}</span> </h4>
                                <div class="card" style="width: 30rem;">
                                    <div class="card-body">
                                        <p class="card-text">{{ __($userPet->short_description) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <nav style="height: 50px;padding-top: 10px;">
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                              <button class="nav-link active" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="true">Profile</button>
                              <button class="nav-link" id="nav-image-tab" data-bs-toggle="tab" data-bs-target="#nav-image" type="button" role="tab" aria-controls="nav-image" aria-selected="true">Images</button>
                              <button class="nav-link" id="nav-video-tab" data-bs-toggle="tab" data-bs-target="#nav-video" type="button" role="tab" aria-controls="nav-video" aria-selected="false">Videos</button>
                              <button class="nav-link" id="nav-previous-tab" data-bs-toggle="tab" data-bs-target="#nav-previous" type="button" role="tab" aria-controls="nav-previous" aria-selected="false">Previous Record</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-md-4">
                                        @php
                                            $extension = pathinfo($imageattachments[0]->attachment, PATHINFO_EXTENSION);
                                        @endphp
                                        @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                            <div class="blog-thumb">
                                                {{-- <a href="javascript:void(0)" class="las la-trash designForDelete" onclick="deleteSinglePetAttachment({{$imageattachments[0]->id}}, 'image')"></a> --}}
                                                <img src="{{ getImage('assets/images/pets/' . @$imageattachments[0]->attachment) }}" alt="@lang('pet-image')" style="width: 280px; height: 250px; border-radius:50%">
                                            </div>
                                        @endif
                                        <h3 class="mt-4" style="margin-left: 50%;">{{$userPet->name}}</h3>
                                    </div>
                                    <div class="col-md-8 mt-4">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="grid-container">
                                                    @if(isset($userPet->breed) && $userPet->breed !='')
                                                        <div class="heading mb-2" style="color: lightslategray;">Breed</div>
                                                    @endif
                                                    @if(isset($userPet->weight) && $userPet->weight !='')
                                                        <div class="heading mb-2" style="color: lightslategray;">Weight</div>
                                                    @endif
                                                    @if(isset($userPet->age) && $userPet->age !='')
                                                        <div class="heading mb-2" style="color: lightslategray;">Age</div>
                                                    @endif
                                                    @if(isset($userPet['pettype']->name) && $userPet['pettype']->name !='')
                                                        <div class="heading mb-2" style="color: lightslategray;">Species</div>
                                                    @endif
                                                    @if(isset($userPet->gender) && $userPet->gender !='')
                                                        <div class="heading mb-2" style="color: lightslategray;">Gender</div>
                                                    @endif
                                                    @if(isset($userPet->short_description) && $userPet->short_description !='')
                                                        <div class="heading" style="color: lightslategray;">Description</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="grid-container">
                                                    <div class="value mb-2" style="color: lightslategray;">{{$userPet->breed}}</div>
                                                    <div class="value mb-2" style="color: lightslategray;">{{$userPet->weight}}</div>
                                                    <div class="value mb-2" style="color: lightslategray;">{{$userPet->age.' '.$userPet->age_in}}</div>
                                                    <div class="value mb-2" style="color: lightslategray;">{{$userPet['pettype']->name}}</div>
                                                    <div class="value mb-2" style="color: lightslategray;">{{$userPet->gender}}</div>
                                                    <div class="value" style="color: lightslategray;width: 400px;">{{$userPet->short_description}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="nav-image" role="tabpanel" aria-labelledby="nav-image-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-body">
                                            <form method="post" action="{{ route('petimages',$userPet->id) }}" enctype="multipart/form-data">
                                                @csrf

                                                <div class="text-end">
                                                    <button type="button" class="btn cmn-btn reply-btn addFile"><i class="fa fa-plus"></i> @lang('Add New Images')</button>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">@lang('')</label> <small class="text-danger d-none">@lang('Max 5 files can be uploaded'). @lang('Upload size is') {{ ini_get('upload_max_filesize') }}</small>
                                                    <input type="file" name="attachments[]" required class="form-control form--control"/>
                                                    <div id="fileUploadsContainer"></div>
                                                    <small class="my-2 ticket-attachments-message text-muted">
                                                        @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), @lang('webp')
                                                    </small>
                                                </div>
                                                <button type="submit" class="btn cmn-btn w-20"> @lang('Upload')</button>
                                            </form>
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
                                                    <a href="javascript:void(0)" class="las la-trash designForDelete" onclick="deleteSinglePetAttachment({{$attachment->id}}, 'image')"></a>
                                                    <img src="{{ getImage('assets/images/pets/' . @$attachment->attachment) }}"alt="@lang('pet-image')" style="width: 280px; height: 250px;border-radius: 10%;">
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
                                            <form method="post" action="{{ route('petvideos',$userPet->id) }}" enctype="multipart/form-data">
                                                @csrf

                                                <div class="text-end">
                                                    <button type="button" class="btn cmn-btn reply-btn addFileVideo"><i class="fa fa-plus"></i> @lang('Add New Video')</button>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">@lang('')</label> <small class="text-danger d-none">@lang('Max 5 files can be uploaded'). @lang('Upload size is') {{ ini_get('upload_max_filesize') }}</small>
                                                    <input type="file" name="attachmentsvideos[]" required class="form-control form--control" accept="video/mp4,video/x-m4v,video/*" />
                                                    <div id="fileUploadsVideoContainer"></div>
                                                    <small class="my-2 ticket-attachments-message text-muted">
                                                        @lang('Allowed Video Extensions'): .@lang('mp4'), .@lang('x-m4v')
                                                    </small>
                                                </div>
                                                <button type="submit" class="btn cmn-btn w-20"> @lang('Upload')</button>
                                            </form>
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
                                                    <a href="javascript:void(0)" class="las la-trash designForVideoDelete" onclick="deleteSinglePetAttachment({{$attachment->id}}, 'video')"></a>
                                                    <video controls width="270px" height="200px;" style="border-radius: 10%;">
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
                                            <form method="post" action="{{ route('petrecords',$userPet->id) }}" enctype="multipart/form-data">
                                                @csrf

                                                <div class="text-end">
                                                    <button type="button" class="btn cmn-btn reply-btn addFileRecord"><i class="fa fa-plus"></i> @lang('Add New Records')</button>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">@lang('')</label> <small class="text-danger d-none">@lang('Max 5 files can be uploaded'). @lang('Upload size is') {{ ini_get('upload_max_filesize') }}</small>
                                                    <input type="file" name="attachmentsrecords[]" required class="form-control form--control"  accept=".doc,.docx,.pdf,.jpg,.png,.jpeg,.webp,.mp4,.x-m4v" />
                                                    <div id="fileUploadsRecordContainer"></div>
                                                    <small class="my-2 ticket-attachments-message text-muted">
                                                        @lang('Allowed Extensions'): .@lang('pdf'), .@lang('doc'), .@lang('docx'), .@lang('jpg'), .@lang('png'), .@lang('jpeg'), .@lang('webp'), .@lang('mp4'), .@lang('x-m4v'),
                                                    </small>
                                                </div>
                                                <button type="submit" class="btn cmn-btn w-20"> @lang('Upload')</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="row updateDivAfterPreviousRecordDelete">
                                    @foreach($previousrecordattachments as $attachment)
                                        <div class="col-md-4">
                                            @php
                                            $extension = pathinfo($attachment->attachment, PATHINFO_EXTENSION);
                                            @endphp
                                            @if(in_array($extension, ['pdf','doc', 'docx', 'jpg', 'jpeg', 'png' , 'webp','mp4', 'x-m4v']) && $attachment->attachment_type == 'previous_record')
                                                @if(in_array($extension, ['pdf']))
                                                    <a href="javascript:void(0)" class="las la-trash designForPreviousRecordPDFDelete" onclick="deleteSinglePetAttachment({{$attachment->id}}, 'previous_record')"></a>
                                                    <iframe src="{{ asset('assets/images/pets/' . @$attachment->attachment) }}" frameborder="0" width="275px" height="280px" style="border-radius: 10%;"></iframe>
                                                @elseif(in_array($extension, ['doc', 'docx']))
                                                    <a href="{{ asset('assets/images/pets/' . @$attachment->attachment) }}" download="{{ @$userPet->name.'_previousrecord' }}" class="link-primary">Download File</a>
                                                @elseif(in_array($extension, ['jpg', 'jpeg', 'png', 'webp']))
                                                    <a href="javascript:void(0)" class="las la-trash designForPreviousRecordimageDelete" onclick="deleteSinglePetAttachment({{$attachment->id}}, 'previous_record')"></a>
                                                    <img src="{{ getImage('assets/images/pets/' . @$attachment->attachment) }}" alt="@lang('previous_record')" style="width: 280px; height: 280px; text-align:center;border-radius: 10%;">
                                                    @elseif(in_array($extension, ['mp4', 'x-m4v']))
                                                    <a href="javascript:void(0)" class="las la-trash designForPreviousRecordVideoDelete" onclick="deleteSinglePetAttachment({{$attachment->id}}, 'previous_record')"></a>
                                                    <video width="270px" height="256px" style="border-radius: 10%;" controls>
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
@push('script')
    <script>
        (function ($) {
            "use strict";
            //extra images uploader
            var fileAdded = 0;
            $('.addFile').on('click',function(){
                if (fileAdded >= 50) {
                    notify('error','You\'ve added maximum number of file');
                    return false;
                }
                fileAdded++;
                $("#fileUploadsContainer").append(`
                    <div class="input-group my-3">
                        <input type="file" name="attachments[]" class="form-control form--control" required />
                        <button type="submit" class="input-group-text btn-danger remove-btn"><i class="las la-times"></i></button>
                    </div>
                `)
            });
            $(document).on('click','.remove-btn',function(){
                fileAdded--;
                $(this).closest('.input-group').remove();
            });

            //extra videos uploader
            var fileVideoAdded = 0;
            $('.addFileVideo').on('click',function(){
                if (fileVideoAdded >= 50) {
                    notify('error','You\'ve added maximum number of file');
                    return false;
                }
                fileVideoAdded++;
                $("#fileUploadsVideoContainer").append(`
                    <div class="input-group input-group-video my-3">
                        <input type="file" name="attachmentsvideos[]" class="form-control form--control" required />
                        <button type="submit" class="input-group-text btn-danger remove-btn-video"><i class="las la-times"></i></button>
                    </div>
                `)
            });
            $(document).on('click','.remove-btn-video',function(){
                fileVideoAdded--;
                $(this).closest('.input-group-video').remove();
            });

            //extra Record uploader
            var fileRecordAdded = 0;
            $('.addFileRecord').on('click',function(){
                if (fileRecordAdded >= 50) {
                    notify('error','You\'ve added maximum number of file');
                    return false;
                }
                fileRecordAdded++;
                $("#fileUploadsRecordContainer").append(`
                    <div class="input-group input-group-record my-3">
                        <input type="file" name="attachmentsrecords[]" class="form-control form--control" required />
                        <button type="submit" class="input-group-text btn-danger remove-btn-record"><i class="las la-times"></i></button>
                    </div>
                `)
            });
            $(document).on('click','.remove-btn-record',function(){
                fileRecordAdded--;
                $(this).closest('.input-group-record').remove();
            });
        })(jQuery);
    </script>
@endpush
