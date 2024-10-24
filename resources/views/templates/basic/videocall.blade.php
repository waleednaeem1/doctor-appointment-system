@extends($activeTemplate . 'layouts.frontend')
@section('content')

    <section class="contact-item-section pd-t-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="contact-form-area">
                        <div class="row ml-b-30" style="margin-bottom: 30px;">
                            <div class="content">
                                <div class="title m-b-md">
                                    Video Call
                                </div>
                                <p>
                                    <button id="get-video">Show your video on screen</button>
                                  </p>
                              
                                  <div class="videos">
                                    <div id="video-container"></div>
                                  </div>
                            
                            </div>
                             
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- contact-item-section end -->
@endsection
@push('script')
<script>
    const container = document.getElementById("video-container");
    const button = document.getElementById("get-video");

    button.addEventListener("click", () => {
      console.log(1);
      Twilio.Video.createLocalVideoTrack().then((track) => {
        setTimeout(() => {
          container.classList.add("live");
        }, 2000);
        container.append(track.attach());
        const name = document.createElement("p");
        name.classList.add("name");
        name.append(document.createTextNode("Searchavet"));
        container.append(name);
        button.remove();
      });
    });
  </script>

@endpush