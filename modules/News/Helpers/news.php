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
function get_list_news_posts(
    $limit = 10,
    $category_id = 0,
    $onlyShowPublished = true,
    $orderBy = 'latest',
    $locale = null
)
{
    $repository = app()->make(\Modules\News\Repositories\PostRepository::class);
    return $repository->getPosts($limit, $category_id, $onlyShowPublished, $orderBy, $locale);
}

function get_list_news_featured_posts(
    $limit = 10,
    $category_id = 0,
    $onlyShowPublished = true,
    $orderBy = 'latest',
    $locale = null
)
{
    $repository = app()->make(\Modules\News\Repositories\PostRepository::class);
    return $repository->getPostsFeatured($limit, $category_id, $onlyShowPublished, $orderBy, $locale);
}

/**
 * Lấy danh sách danh mục
 *
 * @param int $parent_id
 * @param bool $onlyShowPublished
 * @param null $locale
 * @return mixed
 */
function get_list_news_categories($parent_id = 0, $onlyShowPublished = true, $locale = null)
{
    $repository = app()->make(\Modules\News\Repositories\PostCategoryRepository::class);
    return $repository->getCategories($parent_id, $onlyShowPublished, $locale);
}

/**
 * Lấy danh mục theo ID
 * @param $id
 * @return mixed
 */
function get_news_category_by_id($id) {
    $repository = app()->make(\Modules\News\Repositories\PostCategoryRepository::Class);
    return $repository->getViaId($id);
}

function get_list_authors_for_choose() {
    $users = \Modules\User\Models\User::all();

    return $users->mapWithKeys(function ($model) {
        return [$model->id => $model->name];
    })->toArray();
}

function get_all_categories()
{
    $repository = app()->make(\Modules\News\Repositories\PostCategoryRepository::Class);
    return $repository->all();
}
