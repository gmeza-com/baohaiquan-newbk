<?php
namespace Modules\Activity\Repositories;

use Modules\Activity\Models\Activity;
use Modules\User\Models\User;

class ActivityRepository
{
    /**
     * @var Activity
     */
    protected $model;

    /**
     * ActivityRepository constructor.
     *
     * @param $model
     */
    public function __construct(Activity $model)
    {
        $this->model = $model;
    }

    /**
     * @param int $paginate
     * @return mixed
     */
    public function getAllActivities($paginate = 24)
    {
        $data = $this->model->with('subject')->latest();
        if ($paginate) {
            return $data->paginate($paginate);
        }
        return $data->get();
    }

    /**
     * @return string
     */
    public function countAllActivities()
    {
        return number_format($this->model->count());
    }

    /**
     * @param User $user
     * @param int $paginate
     * @return mixed
     */
    public function getActivitiesViaUser(User $user, $paginate = 24)
    {
        $data = $user->activities()->with('subject')->latest();

        if ($paginate) {
            return $data->paginate($paginate);
        }
        return $data->get();
    }

    /**
     * @param User $user
     * @return string
     */
    public function countActivitiesViaUser(User $user)
    {
        return number_format($user->activities->count());
    }

}