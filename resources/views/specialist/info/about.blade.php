@extends('doctor.layouts.app')

@section('panel')
<div class="row mb-none-30">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('doctor.info.about.update')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label>@lang('About')</label>
                        <textarea name="about" rows="5" required>{{ $doctor->about }}</textarea>
                    </div>
                    <div class="row ml-b-20 mt-2">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Overall Experience</label>
                                <input type="text" min="0" class="form-control" id="overall_experience" name="overall_experience" value="{{ $doctor->overall_experience }}" oninput="runMultipleFunctions(this)"  autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="" style="margin-top: 35px;">
                                <div class="btn-group btn-radio-group" data-toggle="buttons">
                                    <label class="btn unit-btn rounded">
                                        <input type="radio" name="experience_time" id="option1" autocomplete="off" value="Month" {{ $doctor->experience_time === 'Month' ? 'checked' : '' }}> Months
                                    </label>
                                    <label class="btn unit-btn rounded">
                                        <input type="radio" name="experience_time" id="option2" autocomplete="off" value="Year" {{ $doctor->experience_time === 'Year' ? 'checked' : '' }}> Years
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-5">
                        <div class="form-group">
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
