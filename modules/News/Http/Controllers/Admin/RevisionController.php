<?php

namespace Modules\News\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Modules\News\Models\PostHistory;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RevisionController extends AdminController
{
  public function index(Request $request)
  {

    if ($request->ajax()) return $this->data($request);

    $this->tpl->setData('title', 'Danh sách lịch sử bài viết');
    $this->tpl->setTemplate('news::admin.revision.index');

    $this->tpl->breadcrumb()->add('admin.post.index', trans('news::language.manager_post'));

    $this->tpl->datatable()->addColumn(
      '#',
      'id',
      ['class' => 'col-md-1']
    );

    $this->tpl->datatable()->addColumn(
      trans('language.name'),
      'name',
      ['class' => 'col-md-4']
    );

    $this->tpl->datatable()->addColumn(
      'Người Đăng',
      'author_post',
      ['class' => 'col-md-4']
    );

    $this->tpl->datatable()->addColumn(
      trans('language.updated_at'),
      'updated_at'
    );

    return $this->tpl->render();
  }

  public function data(Request $request)
  {
    $model = PostHistory::with('author')->where('locale',  config('cnv.language_default'));

    if ($request->get('post_id')) {
      $model->whereHas('post', function ($query) use ($request) {
        $query->where('id', $request->get('post_id'));
      });
    }
    return Datatables::eloquent($model)
      ->editColumn('name', function ($model) {
        $html = '<h4>';
        $html .= link_to_route('admin.post.revision.edit', $model->name, ['postHistory' => $model->id]);
        $html .= '</h4>';
        return $html;
      })
      ->addColumn('action', function ($model) {
        app('helper')->load('buttons');
        $button = [];

        // edit role
        if (allow('news.revision.edit')) {
          $button[] = [
            'route' => admin_route('post.revision.edit', $model->id),
            'name' => trans('language.edit'),
            'icon' => 'fa fa-eye',
            'attributes' => [
              'class' => 'btn btn-xs btn-warning'
            ]
          ];
        }

        return cnv_action_block($button);
      })

      ->editColumn('author_post', function ($model) {
        return sprintf('<span style="color:blue;font-weight:blod">%s</span>', $model->author->name);
      })
      ->editColumn('updated_at', function ($model) {
        return Carbon::parse($model->updated_at)->format('d/m/Y H:i');
      })
      ->rawColumns(['name', 'author_post', 'action'])
      ->make(true);
  }




  public function edit(PostHistory $postHistory)
  {
    $this->tpl->setData('title', 'Xem lại lịch sử bài viết');
    $this->tpl->setData('postHistory', $postHistory);
    $this->tpl->setTemplate('news::admin.revision.edit');
    // breadcrumb
    $this->tpl->breadcrumb()->add('admin.post.revision.index', 'Xem danh sách lịch sử bài viết');
    $this->tpl->breadcrumb()->add(admin_route('post.revision.edit', $postHistory->id), 'Xem lại bài viết');
    return $this->tpl->render();
  }

  public function update(Request $request, PostHistory $postHistory)
  {

    if (! $request->ajax()) {
      return;
    }

    $data = $request->except(['_token']);

    DB::table('post_languages')
      ->where('locale', @$data['locale_post'] ?: 'vi')
      ->where('post_id', $postHistory->post_id)
      ->update(['content' =>  $postHistory->origin_content]);

    return response()->json([
      'status' => 200,
      'message' => 'Khôi phục dữ liệu thành công',
      'redirect_url' => admin_route('post.revision.edit', $postHistory->id)
    ]);
  }
}
