<div class="row">
    <input type="hidden" name="locale_post" value="{{ $postHistory->locale }}">
    <div class="col-lg-6">
        @component('components.block')
            @slot('title', trans('language.basic_info'))
            <div class="block-body">
                <div class="form-bordered">
                    <ul class="nav nav-tabs" data-toggle="tabs">
                        @foreach (config('cnv.languages') as $language)
                            <li {{ $loop->first ? 'class=active' : '' }}>
                                <a href="#{{ $language['locale'] }}">
                                    {{ $language['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="{{ @$postHistory->locale }}">

                            <div class="form-group">
                                {!! Form::label('origin_content', 'Phiên bản trước ( ' . $postHistory->created_at->format('d/m/Y H:i:s') . ')', [
                                    'class' => 'label-control',
                                ]) !!}
                                <div class="content_news">{!! @$postHistory->origin_content !!}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcomponent
    </div>
    <div class="col-lg-6">
        @component('components.block')
            @slot('title', trans('language.basic_info'))
            <div class="block-body">
                <div class="form-bordered">
                    <ul class="nav nav-tabs" data-toggle="tabs">
                        @foreach (config('cnv.languages') as $language)
                            <li {{ $loop->first ? 'class=active' : '' }}>
                                <a href="#{{ $language['locale'] }}">
                                    {{ $language['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        @foreach (config('cnv.languages') as $language)
                            <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="{{ $language['locale'] }}">

                                <div class="form-group">
                                    {!! Form::label(
                                        'content',
                                        'Phiên bản hiện tại ( ' . $postHistory->post->updated_at->format('d/m/Y H:i:s') . ')',
                                        ['class' => 'label-control'],
                                    ) !!}
                                    <div class="content_news">{!! @$postHistory->post->language('content', $language['locale']) !!}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        @endcomponent
    </div>

</div>

@include('partial.editor')
