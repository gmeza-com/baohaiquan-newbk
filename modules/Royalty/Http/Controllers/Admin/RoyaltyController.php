<?php

namespace Modules\Royalty\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Carbon\Carbon;
use Illuminate\Support\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Modules\Royalty\Models\Royalty;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Response;

class RoyaltyController extends AdminController
{
  public function index(Request $request)
  {
    if ($request->ajax()) return $this->data($request);

    $this->tpl->setData('title', trans('royalty::language.manager'));
    $this->tpl->setTemplate('royalty::admin.index');

    // breadcrumb
    $this->tpl->breadcrumb()->add('admin.royalty.index', trans('royalty::language.manager'));

    // datatable
    $filter = encrypt($request->only(['category', 'author', 'status', 'note', 'month']));
    $url = admin_route('royalty.index');
    $url = $url . '?filter=' . $filter;
    $this->tpl->datatable()->setSource($url);

    $this->tpl->datatable()->addColumn('#', 'id');
    $this->tpl->datatable()->addColumn(
      trans('royalty::language.author'),
      'author',
      ['class' => 'col-md-2'],
    );

    $this->tpl->datatable()->addColumn(
      trans('royalty::language.amount'),
      'amount'
    );

    $this->tpl->datatable()->addColumn(
      trans('royalty::language.category'),
      'category',
      ['class' => 'col-md-1'],
    );

    $this->tpl->datatable()->addColumn(
      trans('royalty::language.post'),
      'post',
      ['class' => 'col-md-2'],
      false,
      false
    );

    $this->tpl->datatable()->addColumn(
      trans('royalty::language.month'),
      'month',
      [],
      false,
      true
    );

    $this->tpl->datatable()->addColumn(
      trans('language.status'),
      'status'
    );

    $this->tpl->datatable()->addColumn(
      trans('royalty::language.note'),
      'note',
      ['class' => 'col-md-2'],
      true,
      false
    );

    $this->tpl->datatable()->addColumn(
      trans('language.updated_at'),
      'updated_at',
      [],
      false,
      true
    );

    return $this->tpl->render();
  }

  public function data(Request $request)
  {
    $filter = decrypt($request->get('filter'));
    $model = Royalty::with(['author', 'category', 'status']);

    // filter by category
    if (@$filter['category'] && @$filter['category'] !== '*') {
      $model->whereHas('category', function ($query) use ($filter) {
        $query->where('id', @$filter['category']);
      });
    }

    // filter by status
    if (@$filter['status'] && @$filter['status'] !== '*') {
      $model->whereHas('status', function ($query) use ($filter) {
        $query->where('id', @$filter['status']);
      });
    }

    // filter by month
    if (@$filter['month'] && @$filter['month'] !== '*') {
      $month = Carbon::createFromFormat('m/Y', $filter['month'])->format('Y-m');
      $model->where('month', $month);
    }

    $classes = ['', 'default', 'info', 'success', 'danger'];

    return Datatables::of($model)->addColumn('author', function ($model) {
      return $model->author->name;
    })->addColumn('status', function ($model) use ($classes) {
      return sprintf(
        '<strong><span class="label label-%s">%s</span></strong>',
        $classes[$model->status->id],
        $model->status->name
      );
    })->filterColumn('author', function ($query, $keyword) {
      $query->whereHas('author', function ($q) use ($keyword) {
        $q->where('name', 'like', "%{$keyword}%");
      });
    })->addColumn('category', function ($model) {
      return $model->category->name;
    })->addColumn('month', function ($model) {
      return Carbon::createFromFormat('Y-m', $model->month)->format('m/Y');
    })->addColumn('post', function ($model) {
      $title = '';
      $route = '';
      $isAllowEdit = false;
      if ($model->post_id > 0) {
        $title = $model->post->languages->where('locale', 'vi')->first()->name;
        $route = admin_route('post.edit', $model->post_id);
        $isAllowEdit = allow('news.post.edit');
      } else if ($model->gallery_id > 0) {
        $title = $model->gallery->languages->where('locale', 'vi')->first()->name;
        $route = admin_route('gallery.edit', $model->gallery_id);
        $isAllowEdit = allow('gallery.gallery.edit');
      }

      if ($title) {
        return $isAllowEdit ? sprintf(
          '<a href="%s" class="truncate" target="_blank"><strong>%s</strong></a>',
          $route,
          $title
        ) : sprintf('<strong class="truncate">%s</strong>', $title);
      } else return '';
    })->editColumn('note', function ($model) {
      return sprintf(
        '<div class="text-muted truncate">%s</div>',
        $model->note
      );
    })->addColumn('action', function ($model) {
      app('helper')->load('buttons');
      $button = [];

      // edit role
      if (allow('royalty.royalty.edit') && $model->status->id < 3) {
        $button[] = [
          'route' => admin_route('royalty.edit', $model->id),
          'name' => trans('language.edit'),
          'icon' => 'fa fa-pencil-square-o',
          'attributes' => [
            'class' => 'btn btn-xs btn-primary'
          ]
        ];
      }

      // cancel post
      if (allow('royalty.royalty.edit') && $model->status->id < 4) {
        $button[] = [
          'route' => '',
          'name' => 'Hủy',
          'icon' => 'fa fa-ban',
          'attributes' => [
            'class' => 'btn btn-xs btn-danger',
            'data-id' => $model->post->id,
            'data-action' => 'cancel_this_post'
          ]
        ];
      }

      // delete
      if (Auth::user()->roles->first()->slug == 'administrator') {
        if (allow('royalty.royalty.destroy') && $model->status->id < 2) {
          $button[] = [
            'route' => 'javascript:void(0);',
            'name' => trans('language.delete'),
            'icon' => 'fa fa-trash-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-danger',
              'data-url' => admin_route('royalty.destroy', $model->id),
              'data-action' => 'confirm_to_delete',
              'data-message' => trans('language.confirm_to_delete')
            ]
          ];
        }
      }

      return cnv_action_block($button);
    })->editColumn('updated_at', function ($model) {
      return Carbon::parse($model->updated_at)->format('d/m/Y H:i');
    })->editColumn('created_at', function ($model) {
      return Carbon::parse($model->created_at)->format('m/Y');
    })->editColumn('amount', function ($model) {
      return Number::currency($model->amount, 'VND', 'vi_VN');
    })->rawColumns(['status', 'action', 'note', 'post'])->make(true);
  }

  public function create(Request $request, Royalty $royalty)
  {
    if ($request->ajax()) {
      return $this->getForm($request->get('type'), $royalty);
    }

    $royalty->published_at = Carbon::now();
    $royalty->published = true;

    $this->tpl->setData('title', trans('royalty::language.royalty_create'));
    $this->tpl->setData('royalty', $royalty);
    $this->tpl->setTemplate('royalty::admin.create');

    // breadcrumb
    $this->tpl->breadcrumb()->add(admin_route('royalty.index'), trans('royalty::language.manager'));
    $this->tpl->breadcrumb()->add(admin_route('royalty.create'), trans('royalty::language.royalty_create'));

    return $this->tpl->render();
  }

  public function store(Request $request)
  {
    if (! $request->ajax()) return;

    $data = $request->except(['_token']);


    $data['category_id'] = @$data['category_id'] ?: 0;
    // required category
    if (!$data['category_id']) {
      return response()->json([
        'status' => 500,
        'message' => trans('royalty::language.required_category'),
      ]);
    }

    // month
    $data['month'] = $data['year'] . '-' . $data['month'];


    if ($royalty = Royalty::create($data)) {
      // $royalty->category()->sync($data['category_id']);

      Cache::flush();
      return response()->json([
        'status' => 200,
        'message' => trans('language.create_success'),
        'redirect_url' => admin_route('royalty.edit', $royalty->id)
      ]);
    } else {
      return response()->json([
        'status' => 500,
        'message' => trans('language.update_fail'),
      ]);
    }
  }

  public function export(Request $request)
  {
    $filter =  $request->all();
    $model = Royalty::with(['author', 'category', 'status']);

    // filter by category
    if (@$filter['category'] && @$filter['category'] !== '*') {
      $model->whereHas('category', function ($query) use ($filter) {
        $query->where('id', @$filter['category']);
      });
    }

    // filter by status
    if (@$filter['status'] && @$filter['status'] !== '*') {
      $model->whereHas('status', function ($query) use ($filter) {
        $query->where('id', @$filter['status']);
      });
    }

    // filter by month
    if (@$filter['month'] && @$filter['month'] !== '*') {
      $date = Carbon::createFromFormat('m/Y', $filter['month']);
      $model->where('month', $date->month);
    }

    $csvData = [];
    $csvData[] = ['ID', 'Người nhận', 'Số tiền', 'Danh mục', 'Tháng', 'Trạng thái', 'Ghi chú', 'Ngày cập nhật']; // Header row

    $royalties = $model->get();

    foreach ($royalties as $royalty) {
      $csvData[] = [
        $royalty->id,
        $royalty->author->name,
        Number::currency($royalty->amount, 'VND', 'vi_VN'),
        $royalty->category->name,
        Carbon::parse($royalty->created_at)->format('m/Y'),
        $royalty->status->name,
        $royalty->note,
        Carbon::parse($royalty->updated_at)->format('d/m/Y H:i')
      ];
    }

    // Convert array to CSV string
    $csvContent = '';
    foreach ($csvData as $row) {
      $csvContent .= implode(',', $row) . "\n";
    }

    // Return CSV as a downloadable response
    return Response::make($csvContent, 200, [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename="royalty_table.csv"',
    ]);
  }

  public function edit(Request $request, Royalty $gallery)
  {
    if ($request->ajax()) {
      return $this->getForm($request->get('type'), $gallery);
    }

    $this->tpl->setData('title', trans('gallery::language.gallery_edit'));
    $this->tpl->setData('gallery', $gallery);
    $this->tpl->setTemplate('gallery::admin.edit');

    // breadcrumb
    $this->tpl->breadcrumb()->add(admin_route('gallery.index'), trans('gallery::language.manager'));
    $this->tpl->breadcrumb()->add(admin_route('gallery.edit', $gallery->id), trans('gallery::language.gallery_edit'));

    return $this->tpl->render();
  }

  public function update(Request $request, Royalty $royalty)
  {
    if (! $request->ajax()) {
      return;
    }

    $data = $request->except(['_token', 'language']);

    $data['featured'] = $request->has('featured') ? true : false;
    $data['published'] = $request->has('published') ? true : false;
    $data['published_at'] = Carbon::createFromFormat('d-m-Y H:i', $this->getDatetimeOrCreateFromNow($request));

    $data['category'] = @$data['category'] ?: [];
    // required category
    if (!$data['category']) {

      Cache::flush();
      return response()->json([
        'status' => 500,
        'message' => trans('gallery::language.required_categories'),
      ]);
    }

    $languages = $request->only('language');

    if (! @$languages['language']['vi']['content']) {
      return response()->json([
        'status' => 500,
        'message' => 'Các trường album hoặc video bị thiếu, không thể lưu !',
      ]);
    }

    if ($royalty->update($data)) {
      $royalty->saveLanguages($languages);
      $royalty->categories()->sync($data['category']);

      Cache::flush();
      return response()->json([
        'status' => 200,
        'message' => trans('language.update_success'),
        'redirect_url' => admin_route('gallery.edit', $royalty->id)
      ]);
    } else {
      return response()->json([
        'status' => 500,
        'message' => trans('language.update_fail'),
      ]);
    }
  }

  public function destroy(Request $request, Royalty $royalty)
  {
    if (! $request->ajax()) {
      return;
    }
    if ($royalty->delete()) {

      Cache::flush();
      return response()->json([
        'status' => 200,
        'message' => trans('language.delete_success')
      ]);
    } else {
      return response()->json([
        'status' => 500,
        'message' => trans('language.delete_fail'),
      ]);
    }
  }

  protected function getForm($type, $royalty)
  {
    if ($type == 'video') {
      return view('royalty::admin.video', compact('royalty'));
    }

    return view('royalty::admin.album', compact('royalty'));
  }

  protected function getDatetimeOrCreateFromNow(Request $request)
  {
    $date = $request->has('date_published') ? $request->input('date_published') : date('d-m-Y');
    $time = $request->has('time_published') ? $request->input('time_published') : '00:00';

    return $date . ' ' . $time;
  }
}
