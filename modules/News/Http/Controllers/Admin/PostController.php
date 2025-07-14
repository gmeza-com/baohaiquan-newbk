<?php

namespace Modules\News\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\News\Models\Post;
use Modules\News\Models\PostLanguage;
use Modules\News\Models\PostHistory;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\AdminController;
// use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Royalty\Models\RoyaltyCategory;

class PostController extends AdminController
{
  public function index(Request $request)
  {
    if ($request->ajax()) {
      if ($request->has('id')) {
        return $request->exists('published') ? $this->approvedNow($request->get('id')) : $this->show($request->get('id'));
      }
      return $this->data($request);
    }

    $this->tpl->setData('title', trans('news::language.manager_post'));
    $this->tpl->setTemplate('news::admin.post.index');

    // breadcrumb
    $this->tpl->breadcrumb()->add('admin.post.index', trans('news::language.manager_post'));

    // datatable
    $filter = encrypt($request->only(['language', 'category', 'published']));
    $url = admin_route('post.index');
    $url = $url . '?filter=' . $filter;
    $this->tpl->datatable()->setSource($url);

    $this->tpl->datatable()->addColumn(
      '#',
      'id',
      ['class' => 'col-md-1'],
      false
    );

    $this->tpl->datatable()->addColumn(
      trans('language.name'),
      'name',
      ['class' => 'col-md-4']
    );

    $this->tpl->datatable()->addColumn(
      'Lịch sử',
      'revision_history',
      ['class' => 'col-md-1'],
      false,
      true
    );


    $this->tpl->datatable()->addColumn(
      trans('news::language.feature'),
      'post.featured',
      ['class' => 'col-md-1'],
      false,
      true
    );

    $this->tpl->datatable()->addColumn(
      trans('language.status'),
      'post.published',
      ['class' => 'col-md-1'],
      false,
      true
    );

    $this->tpl->datatable()->addColumn(
      'Ẩn tạm thời',
      'post.status',
      ['class' => 'col-md-1'],
      false,
      false
    );

    $this->tpl->datatable()->addColumn(
      trans('language.updated_at'),
      'post.updated_at',
      [],
      false
    );

    return $this->tpl->render();
  }

  public function data(Request $request)
  {
    $filter = decrypt($request->get('filter'));
    $language = @$filter['language'] ?: config('cnv.language_default');
    $model = PostLanguage::with('post')->where('locale', $language);
    if (! isset($filter['published'])) {
      $model = $model->whereHas('post', function ($query) {
        $query->where('published', '<>', -1);
      });
    }

    $user_id = Auth::user()->id;

    if (allow('news.post.only_show_my_post')) {
      $model = $model->whereHas('post', function ($query) use ($user_id) {
        $query->where('user_id', $user_id);
      });
    }

    if (isset($filter['published']) && $filter['published'] !== '*') {
      $published = intval($filter['published']);
      $model = $model->whereHas('post', function ($query) use ($published) {
        $query->where('published', $published ?: 0);
      });
    }



    if (@$filter['category'] && @$filter['category'] !== '*') {
      $model->whereHas('post', function ($query) use ($filter) {
        $query->whereHas('categories', function ($query) use ($filter) {
          $query->where('id', @$filter['category']);
        });
      });
    }

    if ((!isset($filter['published']) || !$filter['published']) && allow('news.post.approved_level_3')) {

      $model = $model->whereHas('post', function ($query) {
        $query->where('published', '<>', 1);
      });
    }

    return Datatables::of($model)
      ->editColumn('name', function ($model) {
        $html = '<h4>';
        $html .= '<a href="javascript:void(0);" onclick="showData(' . $model->post->id . ');"> ' . $model->name . '</a>';
        $html .= '</h4>';
        $html .= '<p>' . $model->description . '</p>';

        return $html;
      })
      ->addColumn('action', function ($model) {
        app('helper')->load('buttons');
        $button = [];

        if ($model->post->published >= 3 && $model->post->getOriginal('published_at')) {
          $button[] = [
            'route' => $model->link,
            'name' => trans('language.show'),
            'icon' => 'fa fa-eye',
            'attributes' => [
              'class' => 'btn btn-xs btn-warning',
              'target' => '_blank'
            ],
          ];
        }

        // edit role
        if (allow('news.post.edit') && ($this->allowToDo2($model->post->published) || $model->post->published >= 3)) {
          $button[] = [
            'route' => admin_route('post.edit', $model->post->id),
            'name' => trans('language.edit'),
            'icon' => 'fa fa-pencil-square-o',
            'attributes' => [
              'class' => 'btn btn-xs btn-primary'
            ]
          ];
        }

        // cancel post
        if (allow('news.post.edit') &&  (allow('news.post.approved_level_2') || allow('news.post.approved_level_3')) && $model->post->published > -1) {
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
          if (allow('news.post.destroy') && $this->allowToDo($model->post->published)) {
            $button[] = [
              'route' => 'javascript:void(0);',
              'name' => trans('language.delete'),
              'icon' => 'fa fa-trash-o',
              'attributes' => [
                'class' => 'btn btn-xs btn-danger',
                'data-url' => admin_route('post.destroy', $model->post->id),
                'data-action' => 'confirm_to_delete',
                'data-message' => trans('language.confirm_to_delete')
              ]
            ];
          }
        }

        return cnv_action_block($button);
      })
      ->editColumn('post.published', function ($model) {
        return $model->post->show_published_status;
      })

      ->editColumn('revision_history', function ($model) {
        return sprintf('<a style="display:block ; width:30px ; margin : 0 auto" href="%s" class="label label-danger"><i class="fa fa-eye"></i></a>', admin_route('post.revision.index') . '?post_id=' . $model->post->id);
      })

      ->editColumn('post.featured', function ($model) {
        return sprintf(
          '<span class="label label-%s">%s</span>',
          $model->post->featured ? 'warning' : 'default',
          $model->post->featured ? trans('news::language.was_featured') : trans('news::language.normal')
        );
      })

      ->editColumn('post.updated_at', function ($model) {
        return Carbon::parse($model->post->updated_at)->format('d/m/Y H:i');
      })

      ->editColumn('post.status', function ($model) {
        return sprintf(
          '<span class="label label-%s">%s</span>',
          $model->post->status ? 'danger' : 'success',
          $model->post->status ? 'Ẩn' : 'Không ẩn'
        );
      })


      ->rawColumns(['name', 'action', 'post.published', 'post.featured', 'post.status', 'revision_history'])
      ->make(true);
  }

  public function create(Post $post)
  {
    $post->status = false;
    $this->tpl->setData('title', trans('news::language.post_create'));
    $this->tpl->setData('post', $post);
    $this->tpl->setTemplate('news::admin.post.create');

    // breadcrumb
    $this->tpl->breadcrumb()->add('admin.post.index', trans('news::language.manager_post'));
    $this->tpl->breadcrumb()->add(admin_route('post.create'), trans('news::language.post_create'));

    return $this->tpl->render();
  }

  public function show($id)
  {
    $post = Post::find($id);

    return view('news::admin.post.show', compact('post'));
  }

  public function approvedNow($id)
  {
    $post = Post::find($id);
    $post->update([
      'published' => $this->approved(true)
    ]);
  }

  public function store(Request $request, Post $post)
  {
    if (!$request->ajax()) return;

    $data = $request->except(['_token', 'language']);
    $data = $this->readyData($request, $data);
    $data['status'] = $request->has('status') ? true : false;
    if (!$this->allowToPublished($post)) {
      $data['published_at'] = null;
    }

    // required category
    if (!$data['category']) {
      return response()->json([
        'status' => 500,
        'message' => trans('news::language.required_categories'),
      ]);
    }

    $languages = $request->input('language');
    foreach ($languages as $locale => $dataLanguage) {
      $languages[$locale]['slug'] = isset($dataLanguage['slug']) ? $dataLanguage['slug'] : Str::slug($dataLanguage['name']);
      if ($languagePost = PostLanguage::query()->whereLocale($locale)->whereSlug(@$dataLanguage['slug'])->first()) {
        return response()->json([
          'status' => 500,
          'message' => 'Tên bài viết đã tồn tại . Yêu cầu nhập tên khác'
        ]);
      }
    }
    if ($post = Post::create($data)) {
      /*$languages = $request->input('language');
            foreach ($languages as $locale => $dataLanguage) {
                $languages[$locale]['slug'] = isset($dataLanguage['slug']) ? $dataLanguage['slug'] : Str::slug($dataLanguage['name']);
                if ($PostLanguage = PostLanguage::query()->whereLocale($locale)->whereSlug(@$dataLanguage['slug'])->first()) {
                    $languages[$locale]['slug'] = @$dataLanguage['slug'] . '-' . $post->id;
                }
            }*/

      // $post->saveLanguages(['language' => $languages]);

      // save royalty
      $this->updateRoyalty($request, $post);

      $post->saveLanguages($request->only('language'));

      foreach ($languages as $locale => $dataLanguage) {
        PostHistory::create([
          'locale' => $locale,
          'name' => $languages[$locale]['name'],
          'post_id' => $post->id,
          'user_id' => auth()->user()->id,
          'origin_content' => $languages[$locale]['content']
        ]);
      }
      $post->categories()->sync($data['category']);
      Cache::flush();
      return response()->json([
        'status' => 200,
        'message' => trans('language.update_success'),
        'redirect_url' => admin_route('post.edit', $post->id)
      ]);
    } else {
      return response()->json([
        'status' => 500,
        'message' => trans('language.update_fail'),
      ]);
    }
  }

  public function edit(Post $post)
  {
    if ((allow('news.post.only_show_my_post') && $post->user_id !== Auth::user()->id)) {
      abort(403);
    }

    $this->tpl->setData('title', trans('news::language.post_edit'));
    $this->tpl->setData('post', $post);
    $this->tpl->setTemplate('news::admin.post.edit');

    // breadcrumb
    $this->tpl->breadcrumb()->add('admin.post.index', trans('news::language.manager_post'));
    $this->tpl->breadcrumb()->add(admin_route('post.edit', $post->id), trans('news::language.post_edit'));

    return $this->tpl->render();
  }

  public function update(Request $request, Post $post)
  {
    if (!$request->ajax())  return;


    if ($request->action === 'cancel' && (allow('news.post.approved_level_2') || allow('news.post.approved_level_3'))) {
      $post->update(['published' => -1, 'cancel_message' => $request->input('message')]);
    }

    if ((allow('news.post.only_show_my_post') && $post->user_id !== Auth::user()->id)) {
      abort(403);
    }
    $data = $request->except(['_token', 'language']);
    $data['status'] = $request->has('status') ? true : false;
    $data = $this->readyData($request, $data);
    if ($post->published == 3) {
      unset($data['published']);
    }

    if (!@$data['category']) {
      return response()->json([
        'status' => 500,
        'message' => trans('news::language.required_categories'),
      ]);
    }

    $languages = $request->input('language');
    foreach ($languages as $locale => $dataLanguage) {
      $languages[$locale]['slug'] = isset($dataLanguage['slug']) ? $dataLanguage['slug'] : Str::slug($dataLanguage['name']);
      if ($languagePost = PostLanguage::query()->whereLocale($locale)->whereSlug(@$dataLanguage['slug'])->first()) {
        if ($languagePost->post_id != $post->id) {
          return response()->json([
            'status' => 500,
            'message' => 'Tên bài viết đã tồn tại . Yêu cầu nhập tên khác'
          ]);
        }
      }
    }

    if ($post->update($data)) {
      if (count($data) > 1) {
        /*$languages = $request->input('language');
                foreach ($languages as $locale => $dataLanguage) {
                    $languages[$locale]['slug'] = isset($dataLanguage['slug']) ? $dataLanguage['slug'] : str_slug($dataLanguage['name']);
                    if ($PostLanguage = PostLanguage::query()->whereLocale($locale)->whereSlug(@$dataLanguage['slug'])->first()) {
                        $languages[$locale]['slug'] = @$dataLanguage['slug'] . '-' . $post->id;
                    }
                }*/
        // $post->saveLanguages(['language' => $languages]);


        $post->saveLanguages($request->only('language'));

        $this->updateRoyalty($request, $post);

        foreach ($languages as $locale => $dataLanguage) {
          PostHistory::create([
            'locale' => $locale,
            'name' => $languages[$locale]['name'],
            'post_id' => $post->id,
            'user_id' => auth()->user()->id,
            'origin_content' => $languages[$locale]['content']
          ]);
        }

        $post->categories()->sync($data['category']);
      }
      Cache::flush();
      return response()->json([
        'status' => 200,
        'message' => trans('language.update_success'),
      ]);
    }
    return response()->json([
      'status' => 500,
      'message' => trans('language.update_fail'),
    ]);
  }

  protected function updateRoyalty(Request $request, Post $post)
  {
    // check if the add-royalty is enabled
    $isAdd = $request->input('add-royalty');
    if ($isAdd) {
      $royalty = $request->input('royalty');
      if (isset($royalty['id']) && $royalty['id'] > 0) {
        // update royalty if status_id == 1
        if ($royalty['status_id'] == 1) {
          $royalty['amount'] = RoyaltyCategory::query()
            ->where('id', $royalty['category_id'])
            ->value('amount');

          $post->royalties()->updateOrCreate(
            ['id' => $royalty['id']],
            [
              'user_id' => $royalty['user_id'],
              'category_id' => $royalty['category_id'],
              'status_id' => $royalty['status_id'],
              'amount' => $royalty['amount']
            ]
          );
        }
      } else {
        // create royalty. must get the amount from category_id
        $royalty['amount'] = RoyaltyCategory::query()
          ->where('id', $royalty['category_id'])
          ->value('amount');

        $post->royalties()->create([
          'user_id' => $royalty['user_id'],
          'category_id' => $royalty['category_id'],
          'status_id' => $royalty['status_id'],
          'amount' => $royalty['amount']
        ]);
      }
    } else {
      // update the royalty to status_id = 4
      $post->royalties()->update(['status_id' => 4]);
    }
  }

  public function destroy(Request $request, Post $post)
  {
    if (!$request->ajax()) {
      return;
    }

    if ((allow('news.post.only_show_my_post') && $post->user_id !== Auth::user()->id) || !$this->allowToDo($post->published)) {
      abort(403);
    }
    DB::table('post_histories')->where('post_id', $post->id)->delete();
    if ($post->delete()) {
      Cache::flush();
      return response()->json([
        'status' => 200,
        'message' => trans('language.delete_success')
      ]);
    } else {
      return response()->json([
        'status' => 500,
        'message' => trans('language.delete_fail')
      ]);
    }
  }

  protected function getDatetimeOrCreateFromNow(Request $request, $name = 'published')
  {
    $date = $request->has('date_' . $name) ? $request->input('date_' . $name) : date('d-m-Y');
    $time = $request->has('time_' . $name) ? $request->input('time_' . $name) : '00:00';

    return $date . ' ' . $time;
  }

  protected function readyData($request, $data)
  {
    $data['featured'] = $request->has('featured') ? true : false;
    $data['hide'] = $request->has('hide') ? true : false;
    $data['published'] = $this->approved($request->has('published'));

    if ($request->has('date_published') || $request->has('time_published')) {
      $data['published_at'] = Carbon::createFromFormat('d-m-Y H:i', $this->getDatetimeOrCreateFromNow($request));
    } else {
      // $data['published_at'] =  null;
    }
    $data['category'] = @$data['category'] ?: [];
    if ($data['featured']) {
      $data['featured_started_at'] = Carbon::createFromFormat('d-m-Y H:i', $this->getDatetimeOrCreateFromNow($request, 'featured_started_at'));
      $data['featured_ended_at'] = Carbon::createFromFormat('d-m-Y H:i', $this->getDatetimeOrCreateFromNow($request, 'featured_ended_at'));
    }

    $except = [
      'date_featured_started_at',
      'time_featured_started_at',
      'date_featured_ended_at',
      'time_featured_ended_at',
      'date_published',
      'time_published'
    ];
    return Arr::except($data, $except);
  }

  protected function approved($approved = false)
  {
    if (allow('news.post.approved_level_3')) {
      return $approved ? 3 : 2;
    } elseif (allow('news.post.approved_level_2')) {
      return $approved ? 2 : 1;
    } elseif (allow('news.post.approved_level_1')) {
      return $approved ? 1 : 0;
    }
    return 0;
  }

  protected function allowToDo($activated)
  {
    if (allow('news.post.approved_level_3')) {
      return true;
    } elseif (allow('news.post.approved_level_2') && $activated < 3) {
      return true;
    } elseif (allow('news.post.approved_level_1') && $activated < 2) {
      return true;
    }
    return false || $activated == 0;
  }

  protected function allowToDo2($activated)
  {
    if (allow('news.post.approved_level_3')) {
      return true;
    } elseif (allow('news.post.approved_level_2')) {
      return true;
    } elseif (allow('news.post.approved_level_1') && ($activated < 2 || $activated == 3)) {
      return true;
    }
    return false || $activated == 0;
  }

  protected function allowToPublished($post)
  {
    return allow('news.post.approved_level_3') || ((allow('news.post.approved_level_2') || allow('news.post.approved_level_1')) && $post->published >= 3);
  }

  public function updateSlug(Request $request)
  {
    dd('123123');
  }
}
