<?php

/**
 * Lấy bài viết
 *
 * @param int $limit số bài viết cần lấy
 * @param int|array $category_id chỉ lấy trong danh mục cụ thể
 * @param bool $onlyShowPublished chỉ hiện các bài đã đăng tải
 * @param string $orderBy sắp xếp `latest`, `oldest`, `popular`
 *
 * @return mixed
 */
function get_list_royalty(
  $limit = 10,
  $category_id = 0,
  $onlyShowPublished = true,
  $orderBy = 'latest',
  $locale = null
) {
  $repository = app()->make(\Modules\Royalty\Repositories\RoyaltyRepository::class);
  return $repository->getRoyalty($limit, $category_id, $onlyShowPublished, $orderBy, $locale);
}

/**
 * Lấy danh sách danh mục
 *
 * @param bool $onlyShowPublished
 * @param null $locale
 * @return mixed
 */
function get_list_royalty_category($onlyShowPublished = true)
{
  $repository = app()->make(\Modules\Royalty\Repositories\RoyaltyCategoryRepository::class);
  return $repository->getCategory($onlyShowPublished);
}

/**
 * Lấy danh mục theo ID
 * @param $id
 * @return mixed
 */
function get_royalty_category_by_id($id)
{
  $repository = app()->make(\Modules\Royalty\Repositories\RoyaltyCategoryRepository::class);
  return $repository->getViaId($id);
}

function get_all_royalty_category()
{
  $repository = app()->make(\Modules\Royalty\Repositories\RoyaltyCategoryRepository::class);
  return $repository->all();
}

function get_all_royalty_status()
{
  $repository = app()->make(\Modules\Royalty\Repositories\RoyaltyStatusRepository::class);
  return $repository->all();
}

function get_all_royalty_status_to_choose()
{
  $status = \Modules\Royalty\Models\RoyaltyStatus::all();

  return $status->mapWithKeys(function ($model) {
    return [$model->id => $model->name];
  })->toArray();
}
