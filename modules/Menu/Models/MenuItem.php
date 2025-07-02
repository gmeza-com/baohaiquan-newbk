<?php
namespace Modules\Menu\Models;

use App\Traits\ModelNested;
use App\Traits\ModelLanguages;
use Illuminate\Database\Eloquent\Model;
use Modules\Activity\Traits\RecordsActivity;

class MenuItem extends Model
{
    use ModelLanguages;

    /**
     * @var array
     */
    protected $fillable = [
        'attributes',
        'parent_id',
        'position',
        'level',
        'menu_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'attributes' => 'array'
    ];

    protected $with = [
        'languages', 'children'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var MenuItemLanguage
     */
    protected $languages;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->setAttribute('level', $model->getLevelCurrent($model->getAttribute('parent_id')));
            $model->setAttribute('position', $model->position ?: $model->getLastPosition($model) + 1);
        });

        static::deleting(function ($model) {
            $model->rebuildPosition($model);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function languages()
    {
        return $this->hasMany(MenuItemLanguage::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id');
    }

    /**
     * Get level nested
     *
     * @param  int $parent_id
     * @return int
     */
    public function getLevelCurrent($parent_id)
    {
        if ($parent_id == 0) {
            return 0;
        } else {
            $parent = static::select('level')->where('id', $parent_id)->first();
            return $parent ? $parent->level + 1 : 0;
        }
    }

    /**
     * Get last position in items
     *
     * @param  $model
     * @return int
     */
    public function getLastPosition($model = null)
    {
        if ($model) {
            $count = static::where('menu_id', $model->getAttribute('menu_id'))
                ->where('level', $model->getAttribute('level'))->max('position');
            return intval($count);
        }

        return 0;
    }

    /**
     * Rebuild all positions items
     *
     * @param $model
     * @return void
     */
    public function rebuildPosition($model)
    {
        $getAllItems = static::where([
            ['position', '<', $model->position],
            ['menu_id', '=', $model->menu_id],
            ['level', '=', $model->level]
        ])->get();
        $getAllItems->each(function ($model) {
            $model->increment('position');
        });
    }

    /**
     * Resolve updating
     *
     * @return void
     */
    public function resolveUpdated()
    {
        $this->rebuildPosition($this);
        $this->setAttribute('level', $this->getLevelCurrent($this->parent_id));
        $this->setAttribute('position', $this->getLastPosition($this) + 1);
        $this->save();
    }

    /**
     * Resolve delete
     *
     * @return void
     */
    public function resolveDelete($model)
    {
        if ($model->children->count()) {
            $model->children->each(function ($model) {
                return $this->resolveDelete($model);
            });
        }
        $model->delete();
    }

    /**
     * Rebuild all position from data nested
     *
     * @param  array $data
     * @param  int $parent
     * @param  int $level
     * @return void
     */
    public static function updateAllPosition($data, $parent, $level = 0)
    {
        $position = 0;
        foreach ($data as $item) {
            if ($model = static::where('id', $item['id'])->first()) {
                $model->update([
                    'position' => $position,
                    'level' => $level,
                    'parent_id' => $parent
                ]);
                if (isset($item['children'])) {
                    static::updateAllPosition($item['children'], $model->id, $level + 1);
                }
                $position++;
            }
        }
    }

    /**
     * @param $value
     * @return string
     */
    public function getAttributesHtmlAttribute($value)
    {
        $preffy = [];
        foreach ($this->getAttribute('attributes') as $attr => $value) {
            if(in_array($attr, ['url', 'icon', 'permission'])){
                continue;
            }
            $preffy[] = $attr . '="' . $value. '"';
        }

        return implode(' ', $preffy);
    }

    /**
     * @param $position
     * @param int $level
     * @param int $menu_id
     */
    public static function takeAPositionToEmpty($position, $level = 0, $menu_id = 1)
    {
        $items = static::where([
            ['position', '>=', $position],
            ['level', '=', $level],
            ['menu_id', '=', $menu_id]
        ])->get();

        foreach ($items as $item) {
            $item->increment('position');
        }
    }
}
