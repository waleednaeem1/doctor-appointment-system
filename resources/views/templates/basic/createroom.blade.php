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
                                
                                <form method="POST" name="roomForm" class="cmn-form verify-gcaptcha login-form route" action="createroom" >
                                    @csrf

                                
                                
                                <div class="form-group">
                                    <label class="text--black">@lang('Room Name')</label>
                                    <input type="text" class="form-control text--black" style="border: 1px solid black;" name="roomName" required>
                                </div>
                                
                                
                                <button type="submit" class="logInBtn">@lang('Go')</button>
                                
                            </form>


                                {{-- {!! Form::open(['url' => 'room/create']) !!}
                                    {!! Form::label('roomName', 'Create or Join a Video Chat Room') !!}
                                    {!! Form::text('roomName') !!}
                                    {!! Form::submit('Go') !!}
                                {!! Form::close() !!}
                              --}}
                                @if($rooms)
                                @foreach ($rooms as $room)
                                    <a href="{{ url('joinroom/'.$room) }}">{{ $room }}</a>
                                @endforeach
                                @endif


                                
                             </div>
                             
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- contact-item-section end -->
@endsection


