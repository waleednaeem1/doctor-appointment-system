@extends($activeTemplate . 'layouts.frontend')
@section('content')
<section class="appoint-section ptb-80">
    <div class="container">
        <div class="booking-search-area">
            <div class="row justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="appoint-content">
                        <h2>Where are you located?</h2>
                        <p>We’ll use your current location to show vets.</p>
                        <form class="appoint-form" action="{{ route('doctors.nearByVets') }}" method="POST">
                            @csrf
                            <input type="hidden" id="autoUserLatitude" name="autoUserLatitude">
                            <input type="hidden" id="autoUserLongitude" name="autoUserLongitude">
                            <input type="hidden" value="latlong" name="latlong">
                            <button type="button" style="margin-bottom:10px;" class="cmn-btn" onclick="getLocationAndSubmit();">@lang('Find Vets according to your current location')</button>
                        </form>
                        <hr>
                        <div style="width:50%;margin-left:28%">
                            <h3 style="text-align: left">Enter Manually</h3>
                            <form class="appoint-form" action="{{ route('doctors.nearByVets') }}" method="POST">
                                @csrf
                                <input type="text" class="mb-2" placeholder="Zipcode" id="zipcode" name="autoUserLatitude">
                                <input type="hidden" value="zipcode" name="zipcode">
                                {{-- <input type="text" class="mb-2" placeholder="Longitude" id="manualUserLongitude" name="autoUserLongitude"> --}}
                                <button type="submit" style="margin-bottom:10px;" class="cmn-btn">@lang('Find Vets')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@push('script')
    <script>
        function getLocationAndSubmit() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }
        function showPosition(position) {
            var autoUserLatitude = position.coords.latitude;
            var autoUserLongitude = position.coords.longitude;

            // Now you have the user's latitude and longitude; you can use these values in your form or AJAX request.
            document.getElementById("autoUserLatitude").value = autoUserLatitude;
            document.getElementById("autoUserLongitude").value = autoUserLongitude;
            
            // Manually submit the form
            document.forms[0].submit();
        }
    </script>
@endpush
