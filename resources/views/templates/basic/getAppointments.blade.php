@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="contact-item-section mt-4 mb-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    @if(isset($userPets) && count($userPets) > 0 && $userPets !== null)
                        <div class="row">
                            <div class="col-md-8">
                                {{-- <h2>My pets</h2> --}}
                            </div>
                            <div class="col-md-4 header-bottom-action">
                                <button type="button" class="cmn-btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    @lang('Add New')
                                </button>
                            </div>
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
                                                <div class="row ml-b-20">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>Pet Name</label>
                                                            <input type="text" class="form-control" id="name" name="name" placeholder="@lang('Pet Name')" value="{{ old('name') }}"  autocomplete="off" required>
                                                            <input type="hidden" name="record_type" value="pet">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Breed(optional)</label>
                                                            <input type="text" class="form-control" id="breed" name="breed" placeholder="@lang('Breed')" value="{{ old('breed') }}"  autocomplete="off">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Weight</label>
                                                            <input type="text" min="0" class="form-control" id="weight" name="weight" placeholder="@lang('Weight')" value="{{ old('weight') }}" onkeypress="return onlyNumberKey(event)" maxlength="3" autocomplete="off" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Age</label>
                                                            <input type="text" class="form-control" id="age" name="age" placeholder="@lang('Age')" value="{{ old('age') }}"  autocomplete="off" required oninput="validateAge(this)">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Image</label>
                                                            <input type="file" class="form-control" id="images" multiple name="images[]" placeholder="@lang('Image')" value="{{ old('image') }}" accept="image/*">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>@lang('Species')</label>
                                                            <select class="form-control" name="pet_type_id" required>
                                                                <option value="" selected disabled >@lang('Select one option')</option>
                                                                @foreach ($allPetTypes as $pet )
                                                                <option value="{{ $pet->id }}" >{{ $pet->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Gender</label>
                                                            <select class="form-control" name="gender" required>
                                                                <option value="" selected>@lang('Select gender')</option>
                                                                <option value="male">@lang('Male')</option>
                                                                <option value="female">@lang('Female')</option>
                                                            </select>
                                                        </div>
                                                        <div class="" style="margin-top: 60px;">
                                                            <div class="btn-group btn-radio-group" data-toggle="buttons">
                                                                <label class="btn unit-btn active rounded">
                                                                    <input type="radio" name="unit" id="option1" autocomplete="off" checked value="lbs"> lbs
                                                                </label>
                                                                <label class="btn unit-btn rounded">
                                                                    <input type="radio" name="unit" id="option2" autocomplete="off" value="kg"> kg
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="">
                                                            <div class="" style="margin-top: 50px;">
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
                                                        {{-- <div class="form-group" style="margin-top: 10px;">
                                                            <label>Video</label>
                                                            <input type="file" class="form-control" id="video" name="video[]" multiple placeholder="@lang('Video')" value="{{ old('video') }}" accept="video/mp4,video/x-m4v,video/*">
                                                        </div> --}}
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <textarea rows="4" placeholder="@lang('Short Description')" class="form-control" id="short_description" name="short_description">{{ old('short_description') }}</textarea>
                                                        </div>
                                                    </div>
                                                    <button type="submit" id="PetCreateButton" class="cmn-btn">@lang('Add Pet Detail')</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div id="div2" style="display: none;">
                                            <div class="cover_spin_attachment"></div>
                                            <form class="contact-form verify-gcaptcha" method="POST" enctype="multipart/form-data" id="preRecordForm" >
                                                @csrf
                                                <div class="row ml-b-20">
                                                    <div class="form-group">
                                                        <label>Previous Medical Record</label>
                                                        <input type="file" id="previous_record" multiple name="previous_record[]" placeholder="@lang('Image')" value="{{ old('image') }}" required accept=".doc,.docx,.pdf,video/mp4,video/x-m4v,video/*,image/*" />
                                                        <input type="hidden" name="record_type" value="record">
                                                    </div>
                                                    <button type="submit" id="PreRecordButton" class="cmn-btn">@lang('Add Pet Previous Record')</button>
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
                    @endif
                    <div class="row justify-content-center mrt-30" id="loadpet">
                        @if(count($userPets) < 0 || $userPets == null || $userPets->isEmpty())
                            @foreach($allPetTypes as $petType)
                                <div class="col-lg-2 col-md-6 col-sm-6 mrb-30">
                                    <div class="booking-item">
                                        <div class="booking-thumb">
                                            <a href="{{ route('getpetsdisease', $petType->id ) }}">
                                            <img src="{{ getImage(getFilePath('pets') . '/' . @$petType->image, getFileSize('pets')) }}" alt="@lang('pets')"></a>
                                            <span class="doc-deg">
                                                {{-- <a href="{{ route('pet.details', $petType->id ) }}">{{$petType->name}}</a> --}}
                                                <a href="{{ route('getpetsdisease', $petType->id ) }}">{{$petType->name}}</a>
                                            </span>
                                            <span class="fav-btn"><i class="fas fa-medal"></i></span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            @foreach($userPets as $userPet)
                                <div class="col-lg-2 col-md-6 col-sm-6 mrb-30">
                                    <div class="booking-item">
                                        <div class="booking-thumb">
                                            @php
                                                $image = $userPet->attachments->where('user_id', auth()->guard('user')->user()->id)->where('attachment_type', 'image')->first()
                                            @endphp
                                            {{-- <a href="{{ route('pet.details', $userPet->id ) }}"> --}}
                                            <a href="{{ route('getpetsdisease',[$userPet->pet_type_id,$userPet->id] ) }}">
                                                <img src="{{ getImage(getFilePath('pets') . '/' . @$image->attachment, getFileSize('pets')) }}" alt="@lang('pets')">
                                            </a>
                                            <span class="fav-btn"><i class="fas fa-medal"></i></span>
                                        </div>
                                        <div class="booking-content">
                                            <h5 class="title">{{ $userPet->name }} <i class="fas fa-check-circle text-success"></i></h5>
                                            <p><strong>Age: </strong> {{ $userPet->age.' '.$userPet->age_in }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
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
 </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endpush
