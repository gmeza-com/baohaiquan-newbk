<?php
namespace Modules\Activity\Http\Controllers;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Modules\Activity\Repositories\ActivityRepository;

class ActivityController extends AdminController
{
    public function index(Request $request, ActivityRepository $activityRepository)
    {
        if($request->ajax() && $request->method() == 'DELETE') {
            return $this->clearActivities($request, $activityRepository);
        }

        $this->tpl->setData('title', trans('activity::language.activity_log'));
        $this->tpl->setTemplate('activity::index');

        $this->tpl->breadcrumb()->add('/' . admin_path(), trans('language.dashboard'));
        $this->tpl->breadcrumb()->add('admin.activity.index', trans('activity::language.activity_log'));

        // Nếu là thành viên chỉ xem được hoạt động của bản thân
        if ($request->user()->can('activity.activity.owner') && ! $request->user()->is_super_admin)
        {
            $activities = $activityRepository->getActivitiesViaUser($request->user());
            $countActivities = $activityRepository->countActivitiesViaUser($request->user());
        } else {
            $activities = $activityRepository->getAllActivities();
            $countActivities = $activityRepository->countAllActivities();
        }
        $this->tpl->setData('activities', $activities);
        $this->tpl->setData('count_activities', $countActivities);

        return $this->tpl->render();
    }

    protected function clearActivities(Request $request, ActivityRepository $activityRepository)
    {
        if ($request->user()->can('activity.activity.owner') && ! $request->user()->is_super_admin)
        {
            $activityRepository->getActivitiesViaUser($request->user(), 0)->each->delete();
        } else {
            $activityRepository->getAllActivities(0)->each->delete();
        }

        return response()->json([
            'status' => 200,
            'message' => trans('language.delete_success')
        ]);
    }
}