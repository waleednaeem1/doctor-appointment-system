@extends($activeTemplate . 'layouts.frontend')
@section('content')

    <section class="contact-item-section pd-t-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card b-radius--10 ">
                        <div class="card-body p-0">
                            <div class="table-responsive--sm table-responsive">
                                <table class="table table--light">
                                    <thead>
                                    <tr>
                                        <th>@lang('Subject')</th>
                                      
                                        <th>@lang('Status')</th>
                                        <th>@lang('Priority')</th>
                                        <th>@lang('Last Reply')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($items as $item)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.ticket.view', $item->id) }}"> [@lang('Ticket')#{{ $item->ticket }}] {{ strLimit($item->subject,30) }} </a>
                                            </td>
        
                                            
                                            <td>
                                                @php echo $item->statusBadge; @endphp
                                            </td>
                                            <td>
                                                @if($item->priority == Status::PRIORITY_LOW)
                                                    <span class="badge badge--dark">@lang('Low')</span>
                                                @elseif($item->priority == Status::PRIORITY_MEDIUM)
                                                    <span class="badge  badge--warning">@lang('Medium')</span>
                                                @elseif($item->priority == Status::PRIORITY_HIGH)
                                                    <span class="badge badge--danger">@lang('High')</span>
                                                @endif
                                            </td>
        
                                            <td>
                                                {{ diffForHumans($item->last_reply) }}
                                            </td>
        
                                            <td>
                                                <a href="{{ route('ticket.view', $item->ticket) }}" class="btn btn-sm btn-outline--primary ms-1 cmn-btn">
                                                  @lang('Details')
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                        </tr>
                                    @endforelse
        
                                    </tbody>
                                </table><!-- table end -->
                            </div>
                        </div>
                       
                        <div class="card-footer py-4">
                          
                        </div>
                        
                    </div><!-- card end -->
                </div>
            </div>
        </div>
    </section>
    <!-- contact-item-section end -->
@endsection


