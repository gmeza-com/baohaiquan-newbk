<?php

/**
 * Lấy bài viết
 *
 * @param int $limit số bài viết cần lấy
 * @param int|array $category_id chỉ lấy trong danh mục cụ thể
 * @param bool $onlyShowPublished chỉ hiện các bài đã đăng tải
 * @param string $orderBy sắp xếp `latest`, `oldest`, `popular`
 * @param string $locale null sẽ là hiển thị theo ngôn ngữ trang
 *
 * @return mixed
 */
function get_list_gallery(
  $limit = 10,
  $category_id = 0,
  $onlyShowPublished = true,
  $orderBy = 'latest',
  $locale = null
) {
  $repository = app()->make(\Modules\Gallery\Repositories\GalleryRepository::class);
  return $repository->getGallery($limit, $category_id, $onlyShowPublished, $orderBy, $locale);
}

/**
 * Lấy danh sách danh mục
 *
 * @param int $parent_id
 * @param bool $onlyShowPublished
 * @param null $locale
 * @return mixed
 */
function get_list_gallery_categories($parent_id = 0, $onlyShowPublished = true, $locale = null)
{
  $repository = app()->make(\Modules\Gallery\Repositories\GalleryCategoryRepository::class);
  return $repository->getCategories($parent_id, $onlyShowPublished, $locale);
}

/**
 * Lấy danh mục theo ID
 * @param $id
 * @return mixed
 */
function get_gallery_category_by_id($id)
{
  $repository = app()->make(\Modules\Gallery\Repositories\GalleryCategoryRepository::class);
  return $repository->getViaId($id);
}

function get_all_gallery_categories()
{
  $repository = app()->make(\Modules\Gallery\Repositories\GalleryCategoryRepository::class);
  return $repository->all();
}
