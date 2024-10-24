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
                                    Video Chat Rooms
                                </div>
                                <div id="media-div">
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
//     Twilio.Video.createLocalTracks({
//        audio: true,
//        video: { width: 300 }
//        //video: true
//     }).then(function(localTracks) {
//        return Twilio.Video.connect("{{ @$accessToken }}", {
//            name: "{{ @$roomName }}",
//            tracks: localTracks,
//            video: { width: 300 }
//            //video:true
//        });
//     }).then(function(room) {
//        console.log('Successfully joined a Room: ', room.name);

//        room.participants.forEach(participantConnected);

//        var previewContainer = document.getElementById(room.localParticipant.sid);
//        if (!previewContainer || !previewContainer.querySelector('video')) {
//            participantConnected(room.localParticipant);
//        }

//        room.on('participantConnected', function(participant) {
//            console.log("Joining: '"  + participant.identity);
//            participantConnected(participant);
//        });

//        room.on('participantDisconnected', function(participant) {
//            console.log("Disconnected: '" + participant.identity );
//            participantDisconnected(participant);
//        });
//     });
//     // additional functions will be added after this point

//     function participantConnected(participant) {
//    console.log('Participant "%s" connected', participant.identity);

//    const div = document.createElement('div');
//    div.id = participant.sid;
//    div.setAttribute("style", "float: left; margin: 10px;");
//    div.innerHTML = "<div style='clear:both'>"+ participant.identity+ "</div>";

//    participant.tracks.forEach(function(track) {
//        trackAdded(div, track)
//    });

//    participant.on('trackAdded', function(track) {
//        trackAdded(div, track)
//    });
//    participant.on('trackRemoved', trackRemoved);

//    document.getElementById('media-div').appendChild(div);
// }

// function participantDisconnected(participant) {
//    console.log('Participant "%s" disconnected', participant.identity);

//    participant.tracks.forEach(trackRemoved);
//    document.getElementById(participant.sid).remove();
// }


// function trackAdded(div, track) {
//    div.appendChild(track.attach());
//    var video = div.getElementsByTagName("video")[0];
//    if (video) {
//        video.setAttribute("style", "max-width:300px;");
//    }
// }

// function trackRemoved(track) {
//    track.detach().forEach( function(element) { element.remove() });
// }






/////////////////////////////////////

const Video = Twilio.Video;

Video.connect("{{ @$accessToken }}", { name: "{{ @$roomName }}" }).then(room => {
  console.log('Connected to Room "%s"', room.name);

  room.participants.forEach(participantConnected);
  room.on('participantConnected', participantConnected);

  room.on('participantDisconnected', participantDisconnected);
  room.once('disconnected', error => room.participants.forEach(participantDisconnected));
});

function participantConnected(participant) {
  console.log('Participant "%s" connected', participant.identity);

  const div = document.createElement('div');
  div.id = participant.sid;
  div.innerText = participant.identity;

  participant.on('trackSubscribed', track => trackSubscribed(div, track));
  participant.on('trackUnsubscribed', trackUnsubscribed);

  participant.tracks.forEach(publication => {
    if (publication.isSubscribed) {
      trackSubscribed(div, publication.track);
    }
  });

  //document.body.appendChild(div);
  document.getElementById('media-div').appendChild(div);
}

function participantDisconnected(participant) {
  console.log('Participant "%s" disconnected', participant.identity);
  document.getElementById(participant.sid).remove();
}

function trackSubscribed(div, track) {
  div.appendChild(track.attach());
}

function trackUnsubscribed(track) {
  track.detach().forEach(element => element.remove());
}

</script>
@endpush