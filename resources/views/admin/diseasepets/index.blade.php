@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light">
                            <thead>
                                <tr>
                                    <th>@lang('Image')</th>
                                    <th>@lang('Pet Name')</th>
                                    <th>@lang('Age')</th>
                                    <th>@lang('Medical Record')</th>
                                    <th>@lang('Video')</th>
                                    <th>@lang('Short Description')</th>
                                    
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allPets as $allpet)
                                    <tr>
                                        <td>  <div class="avatar avatar--md">
                                            <img src="{{ getImage(getFilePath('pets') . '/' . $allpet->images, getFileSize('pets')) }}"
                                               alt="@lang('Image')">
                                       </div></td>
                                        <td>{{ $allpet->name}}</td>
                                        
                                        <td>{{ $allpet->age}}</td>
                                        <td>  <div class="avatar avatar--md">
                                            <img src="{{ getImage(getFilePath('pets') . '/' . $allpet->previous_record, getFileSize('pets')) }}"
                                               alt="@lang('Image')">
                                       </div></td>
                                       <td>  <div class="avatar avatar--md">
                                        <img src="{{ getImage(getFilePath('pets') . '/' . $allpet->video, getFileSize('pets')) }}"
                                           alt="@lang('Image')">
                                   </div></td>
                                        <td>{{ $allpet->short_description}}</td>
                                       
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div><!-- card end -->
        </div>
    </div>

    <!--Cu Modal -->
    <div id="cuModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.pets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>@lang('Image')</label>
                                    <div class="image-upload">
                                        <div class="thumb">
                                            <div class="avatar-preview">
                                                <div class="profilePicPreview"
                                                    style="background-image: url({{ getImage(getFilePath('department'), getFileSize('department')) }})">
                                                    <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                            <div class="avatar-edit">
                                                <input type="file" class="profilePicUpload" name="image" id="profilePicUpload1"
                                                    accept=".png, .jpg, .jpeg" required>
                                                <label for="profilePicUpload1" class="bg--success">@lang('Upload Image')</label>
                                                <small class="mt-2 ">@lang('Supported files'):
                                                    <b>@lang('png'),@lang('jpg'),@lang('jpeg').</b>
                                                    @lang('Image will be resized into') {{ getFileSize('department') }} </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Age')</label>
                                    <input type="text" name="age" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Short Description')</label>
                                    <textarea name="short_description" rows="7" class="form-control" required></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form />
    {{-- <button type="button" class="btn btn-sm btn-outline--primary h-45 cuModalBtn" data-modal_title="@lang('Add New Pet')"> <i class="las la-plus"></i>@lang('Add New')
    </button> --}}
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.editBtn').on('click', function() {
                $('#cuModal').find('[name=image]').removeAttr('required');
                $('#cuModal').find('[name=image]').closest('.form-group').find('label').first().removeClass('required');
            });

            var placeHolderImage = "{{ getImage(getFilePath('department'), getFileSize('department')) }}";

            $('#cuModal').on('hidden.bs.modal', function() {
                $('#cuModal').find('.profilePicPreview').css({
                    'background-image' : `url(${placeHolderImage})`
                });
                $('#cuModal').find('[name=image]').attr('required', 'required');
                $('#cuModal').find('[name=image]').closest('.form-group').find('label').first().addClass('required');
            });

        })(jQuery);
    </script>
@endpush
