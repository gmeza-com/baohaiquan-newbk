@push('footer')
    <script>
        var TablesDatatables;

        TablesDatatables = function() {
            return {
                init: function() {
                    /* Initialize Bootstrap Datatables Integration */
                    App.datatables();

                    /* Initialize Datatables */
                    this.table = $('#{{ md5($datatable['source']) }}').dataTable({
                        processing: true,
                        serverSide: true,
                        ajax: '{{ $datatable['source'] }}',
                        columns: {!! $datatable['json'] !!},
                        pageLength: 20,
                        lengthMenu: [
                            [10, 20, 30, 50, -1],
                            [10, 20, 30, 50, 'All']
                        ],
                        language: {!! json_encode(trans('language.datatable')) !!},
                        order: [
                            [0, "desc"]
                        ]
                    });

                    /* Add placeholder attribute to the search input */
                    $('.dataTables_filter input').attr('placeholder', '{{ trans('language.datatable_search') }}');
                }
            };
        }();

        $(function() {
            TablesDatatables.init();
        });
    </script>
@endpush

<div class="table-responsive">
    <table id="{{ md5($datatable['source']) }}" class="table table-striped table-hover table-vcenter table-condensed">
        <thead>
            <tr>
                @foreach ($datatable['columns'] as $column)
                    <th {!! $column['attributes'] !!}>
                        {!! $column['name'] !!}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
