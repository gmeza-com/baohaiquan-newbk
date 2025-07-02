@extends('admin')

@section('page_header')

    @can('news.post.create')
        <div class="pull-right">
            <a href="{{ admin_route('post.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> {{ trans('language.create') }}
            </a>
        </div>
    @endcan

    <h1>{{ $title }}</h1>
@stop

@section('content')
    @include('partial.datatable_mutillang', ['url' => admin_route('post.index')])

    @component('components.block')
        @slot('title', trans('news::language.post_list'))

        @slot('action')
            <form action="{{ request()->url() }}" id="filter" method="GET">
                <select name="category" id="flter_by_category" class="form-control non-select2" style="min-width: 500px">
                    <option value="*">{{ trans('news::language.include_categories') }}</option>
                    @foreach (get_all_categories() as $category)
                        <option value="{{ $category->id }}" {{ $category->id == request()->get('category') ? 'selected' : '' }}>
                            {{ $category->language('name') }}
                        </option>
                    @endforeach
                </select>
            </form>

            <div class="clearfix"></div>
        @endslot

        <div class="text-center" style="padding: 8px 16px">
            <a href="{{ admin_route('post.revision.index') }}" class="btn btn-danger btn-alt">
                Lịch sử bài viết
            </a>

            <a href="{{ request()->url() }}"
                class="btn btn-default btn-alt {{ !request()->has('published') ? 'active' : '' }}">
                Tất cả
            </a>
            <a href="{{ request()->url() }}?published=-1"
                class="btn btn-danger btn-alt {{ request()->has('published') && request('published') == -1 ? 'active' : '' }}">
                Đã hủy
            </a>
            <a href="{{ request()->url() }}?published=0"
                class="btn btn-warning btn-alt {{ request()->has('published') && request('published') == 0 ? 'active' : '' }}">
                {{ trans('news::language.waiting_level_1') }}
            </a>
            @if (allow('news.post.approved_level_2') || allow('news.post.approved_admin'))
                <a href="{{ request()->url() }}?published=1"
                    class="btn btn-info btn-alt {{ request('published') == 1 ? 'active' : '' }}">
                    {{ trans('news::language.waiting_level_2') }}
                </a>
            @endif
            <a href="{{ request()->url() }}?published=2"
                class="btn btn-primary btn-alt {{ request('published') == 2 ? 'active' : '' }}">
                {{ trans('news::language.waiting_level_3') }}
            </a>
            <a href="{{ request()->url() }}?published=3"
                class="btn btn-success btn-alt {{ request('published') == 3 ? 'active' : '' }}">
                {{ trans('news::language.approved_by_level_3') }}
            </a>
        </div>

        <div class="clearfix" style="margin-bottom: 1px; border-bottom: solid 1px #eaedf1"></div>

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

        $('#flter_by_category').change(function(e) {
            $('#filter').trigger('submit');
        });
    </script>
@endpush
