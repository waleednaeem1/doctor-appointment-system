@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="contact-item-section mt-4 mb-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-8">
                            {{-- <h2>My pets</h2> --}}
                        </div>
                        @if (auth()->guard('user')->user())
                            <div class="col-md-4 header-bottom-action">
                                <button type="button" class="cmn-btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    @lang('Add New')
                                </button>
                            </div>
                        @endif
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="col-md-10">
                                        <h2>Add Your Pet Profile</h2>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="div1">
                                        <div class="cover_spin_petCreated"></div>
                                        <form class="contact-form verify-gcaptcha" method="POST" enctype="multipart/form-data" id="petCreatedForm" >
                                            @csrf
                                            <input type="hidden" name="page_type" value="mypets">
                                            <div class="row ml-b-20">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>Pet Name</label>
                                                        <input type="text" class="form-control" id="name" name="name" placeholder="@lang('Pet Name')" value="{{ old('name') }}"  autocomplete="off" required>
                                                        <input type="hidden" name="record_type" value="pet">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>@lang('Species')</label>
                                                        <select class="form-control" name="pet_type_id" required>
                                                            <option value="" selected disabled >@lang('Select one option')</option>
                                                            @foreach ($pet_type as $pet )
                                                            <option value="{{ $pet->id }}" >{{ $pet->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row ml-b-20 mt-2">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>Breed(optional)</label>
                                                        <input type="text" class="form-control" id="breed" name="breed" placeholder="@lang('Breed')" value="{{ old('breed') }}"  autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>Gender</label>
                                                        <select class="form-control" name="gender" required>
                                                            <option value="" selected>@lang('Select gender')</option>
                                                            <option value="male">@lang('Male')</option>
                                                            <option value="female">@lang('Female')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row ml-b-20 mt-2">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>Weight</label>
                                                        <input type="text" min="0" class="form-control" id="weight" name="weight" placeholder="@lang('Weight')" value="{{ old('weight') }}" onkeypress = "return numericOnly(this);" ondrop = "return false;" onpaste = "return false;" maxlength="4"  autocomplete="off" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="" style="margin-top: 35px;">
                                                        <div class="btn-group btn-radio-group" data-toggle="buttons">
                                                            <label class="btn unit-btn active rounded">
                                                                <input type="radio" name="unit" id="option1" autocomplete="off" checked value="lbs"> lbs
                                                            </label>
                                                            <label class="btn unit-btn rounded">
                                                                <input type="radio" name="unit" id="option2" autocomplete="off" value="kg"> kg
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row ml-b-20 mt-2">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>Age</label>
                                                        <input type="text" id="age" name="age" class="form-control" placeholder="@lang('Age')" value="{{ old('age') }}" autocomplete="off" required oninput="validateAge(this)">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="" style="margin-top: 35px;">
                                                        <div class="btn-group btn-radio-group" data-toggle="buttons">
                                                            <label class="btn unit-btn active rounded">
                                                                <input type="radio" name="age_in" id="option1" autocomplete="off" checked value="year"> year
                                                            </label>
                                                            <label class="btn unit-btn rounded">
                                                                <input type="radio" name="age_in" id="option2" autocomplete="off" value="month"> month
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row ml-b-20 mt-2">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>Image</label>
                                                        <input type="file" class="form-control" id="images" multiple name="images[]" placeholder="@lang('Image')" value="{{ old('image') }}" accept=".png, .jpg, .jpeg, .webp">
                                                    </div>
                                                </div>
                                                {{-- <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>Video</label>
                                                        <input type="file" class="form-control" id="video" name="video[]" multiple placeholder="@lang('Video')" value="{{ old('video') }}" accept="video/mp4,video/x-m4v,video/*">
                                                    </div>
                                                </div> --}}
                                            </div>
                                            <div class="row ml-b-20 mt-2">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <textarea rows="4" placeholder="@lang('Short Description')" class="form-control" id="short_description" name="short_description">{{ old('short_description') }}</textarea>
                                                    </div>
                                                </div>
                                                <button type="submit" id="PetCreateButton" class="cmn-btn">@lang('Save')</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div id="div2" style="display: none;">
                                        <div class="cover_spin_petCreated"></div>
                                        <form class="contact-form verify-gcaptcha" method="POST" enctype="multipart/form-data" id="preRecordForm" >
                                            @csrf
                                            <div class="row ml-b-20">
                                                <div class="form-group">
                                                    <label>Previous Medical Record</label>
                                                    <input type="file" id="previous_record" multiple name="previous_record[]" placeholder="@lang('Image')" value="{{ old('image') }}" required accept=".doc,.docx,.pdf,video/mp4,video/x-m4v,video/*,image/*" />
                                                    <input type="hidden" name="record_type" value="record">
                                                </div>
                                                <button type="submit" style="margin-bottom:10px;" id="PreRecordButton" class="cmn-btn">@lang('Add Pet Previous Record')</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer" style="border-top:none; margin-bottom:20px;">
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <p id="loadermsg" class="col-lg-4 col-md-6 col-sm-6 mrb-30 alert alert-danger text-center" role="alert" style="display: none;"></p>
                    <div class="row justify-content-center mrt-30" id="loadpet">
                        @forelse($userPets as $userPet)
                            <div class="col-lg-3 col-md-6 col-sm-6 mrb-30">
                                <div class="booking-item">
                                    <div class="booking-thumb">
                                        @php
                                            $image = $userPet->attachments->where('user_id', auth()->guard('user')->user()->id)->where('attachment_type', 'image')->first()
                                        @endphp
                                        <a href="{{ route('pet.details', $userPet->id ) }}">
                                        <img src="{{ getImage(getFilePath('pets') . '/' . @$image->attachment, getFileSize('pets')) }}"
                                            alt="@lang('pets')" style="width: 284.4px;height: 284.4px;"></a>
                                        <span class="doc-deg">
                                            <a href="{{ route('pet.details', $userPet->id ) }}">{{$userPet->name}}</a>
                                        </span>
                                        <span class="fav-btn"><i class="fas fa-medal"></i></span>
                                    </div>
                                    <div class="booking-content">
                                        <h5 class="title">{{ $userPet->name }} <i class="fas fa-check-circle text-success"></i></h5>
                                        <p><strong>Age: </strong> {{ $userPet->age.' '.$userPet->age_in }}</p>
                                        <ul class="booking-list" style="display: none">
                                            <li><i class="fas fa-street-view">Michel</i>
                                                <a href=""></a>
                                            </li>
                                            <li><i class="fas fa-phone"></i>87878787878 </li>
                                        </ul>
                                        <div class="booking-btn" style="display: none" >
                                            <a href="" class="cmn-btn w-100 text-center">@lang('View Detail')</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-lg-12 col-md-12 col-sm-12 mrb-30">
                                <div class="booking-item text-center">
                                    <h3 class="title mt-2">Add your pet</h3>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
<script>
    function onlyNumberKey(evt) {
        // Only ASCII character in that range allowed
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
    }
    var specialKeys = new Array();  
    specialKeys.push(8); //Backspace  
    function numericOnly(elementRef) {  
        var keyCodeEntered = (event.which) ? event.which : (window.event.keyCode) ?    window.event.keyCode : -1;  
        if ((keyCodeEntered >= 48) && (keyCodeEntered <= 57)) {  
            return true;  
        }  
        else if (keyCodeEntered == 46) {  
            // Allow only 1 decimal point ('.')...  
            if ((elementRef.value) && (elementRef.value.indexOf('.') >= 0))  
                return false;  
            else  
                return true;  
        }  
        return false;  
    }       
 </script>   
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endpush
