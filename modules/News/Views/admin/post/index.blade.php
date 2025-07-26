@extends('admin')

@section('page_header')

    @can('news.post.create')
        @if (!$is_waiting_approve_post)
            <div class="pull-right">
                <a href="{{ admin_route('post.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{ trans('language.create') }}
                </a>
            </div>
        @endif
    @endcan

    <h1>{{ $title }}</h1>
@stop

@section('content')
    @include('partial.datatable_mutillang', ['url' => admin_route('post.index')])

    @component('components.block')
        @slot('title', trans('news::language.post_list'))

        @slot('action')
            <form action="{{ request()->url() }}" id="filter" method="GET" style="display:flex">
                @if (!$is_waiting_approve_post)
                    <select name="published" id="filter_by_status" class="form-control non-select2"
                        style="min-width: 60px; max-width:160px; margin-right: 5px">
                        <option value="*" {{ request()->has('published') && request('published') == '*' ? 'selected' : '' }}>
                            Tất cả trạng thái
                        </option>
                        <option value="3" {{ request()->has('published') && request('published') == 3 ? 'selected' : '' }}>
                            {{ trans('news::language.approved_by_level_3') }}
                        </option>
                        <option value="0" {{ request()->has('published') && request('published') == 0 ? 'selected' : '' }}>
                            {{ trans('news::language.waiting_level_1') }}
                        </option>
                        <option value="1" {{ request()->has('published') && request('published') == 1 ? 'selected' : '' }}>
                            {{ trans('news::language.waiting_level_2') }}
                        </option>
                        <option value="2" {{ request()->has('published') && request('published') == 2 ? 'selected' : '' }}>
                            {{ trans('news::language.waiting_level_3') }}
                        </option>
                        <option value="-1" {{ request()->has('published') && request('published') == -1 ? 'selected' : '' }}>
                            Đã hủy
                        </option>
                    </select>
                @endif
                <select name="category" id="flter_by_category" class="form-control non-select2"
                    style="min-width: 120px; max-width:320px">
                    <option value="*">{{ trans('news::language.include_categories') }}</option>
                    @foreach (get_all_categories() as $category)
                        @if (trim($category->language('name')) !== '')
                            <option value="{{ $category->id }}"
                                {{ $category->id == request()->get('category') ? 'selected' : '' }}>
                                {{ $category->language('name') }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </form>

            <div class="clearfix"></div>
        @endslot

        @include('partial.datatable')
    @endcomponent
@stop

@push('footer')
    <div class="modal fade" id="data" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body"></div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="cancel" role="dialog" data-id="0">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Hủy bài viết</h4>
                </div>
                <div class="modal-body">
                    <textarea name="cancel_message" id="cancel_message" class="form-control" rows="10" placeholder="Nhập lý do hủy"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="cancel_this_post_action">
                        <i class="fa fa-trash-o"></i> Hủy bài viết này
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <script>
        var showData = function(id) {
            $('.modal-body').html('');

            $.get('{{ request()->url() }}?id=' + id, function(data) {
                $('.modal-body').html(data);
                $('#data').modal('show');
            });
        }

        $('#data').on('hidden.bs.modal', function() {
            $('.modal-body').html('');
        })

        var publishedPost = function(id) {
            $.get('{{ request()->url() }}?published&id=' + id, function(data) {
                $('#data').modal('hide');
                $('.modal-body').html('');
                toastr.success('Đã duyệt bài này', 'Thành công !');
                reload_page();
            });
        }

        var cancel_this_post = function(event, btn) {
            event.preventDefault();
            $('#cancel').modal('show');
            $('#cancel').data('id', btn.data('id'));
        };

        $('#cancel_this_post_action').click(function(event) {
            var cancelBox = $('#cancel');

            $.post('{{ request()->url() }}' + '/' + cancelBox.data('id'), {
                _method: 'PUT',
                action: 'cancel',
                message: $('#cancel_message').val()
            }, function(data) {
                cancelBox.modal('hide');
                TablesDatatables.table._fnAjaxUpdate();
                toastr.success('Đã hủy bài này', 'Thành công !');
            });
        });

        $('#flter_by_category,#filter_by_status').change(function(e) {
            $('#filter').trigger('submit');
        });
    </script>
@endpush
