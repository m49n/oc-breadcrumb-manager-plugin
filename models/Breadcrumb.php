<?php namespace Dubk0ff\BreadcrumbManager\Models;

use Cms\Classes\Page;
use Dubk0ff\BreadcrumbManager\Classes\BreadcrumbItem;
use Model;

/**
 * Breadcrumb Model
 */
class Breadcrumb extends Model
{
    use \October\Rain\Database\Traits\SoftDelete;
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Purgeable;
    use \October\Rain\Database\Traits\NestedTree;

    /** @var string  */
    public $table = 'dubk0ff_breadcrumbs';

    /** @var array  */
    protected $guarded = ['*'];

    /** @var array  */
    protected $fillable = [
        'name',
        'data_type',
        'cms_page',
        'cms_title_field'
    ];

    /** @var array  */
    public $rules = [
        'name'      => 'required',
        'data_type' => 'required'
    ];

    /** @var array  */
    protected $jsonable = ['data'];

    /** @var array  */
    protected $purgeable = [
        'static_title',
        'static_link',
        'static_plus_title',
        'static_plus_segment',
        'cms_page',
        'cms_title_field'
    ];

    /** @var array  */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * @param $id
     * @return Breadcrumb|null
     */
    public function getBreadcrumb($id)
    {
        return self::newQuery()
            ->whereId($id)
            ->get()
            ->first();
    }

    /**
     * @return array
     */
    public function getParentsOptions()
    {
        return self::newQuery()
            ->withTrashed()
            ->with('parent')
            ->whereNotNull('parent_id')
            ->get()
            ->filter(function ($item) {
                return $item->parent;
            })
            ->unique()
            ->sortBy('name')
            ->lists('name', 'id');
    }

    /**
     * @return array
     */
    public function getDataTypeOptions()
    {
        return config('breadcrumbs.data_types');
    }

    /**
     * @return array
     */
    public function getCmsPageOptions()
    {
        return Page::getNameList();
    }

    /**
     * @return array
     */
    public function getCmsTitleFieldOptions()
    {
        return config('breadcrumbs.title_fields');
    }

    public function beforeValidate()
    {
        if ($this->data_type === 'static' && $this->isDirty(['data_type', 'static_title', 'static_link'])) {
            $this->rules['static_title'] = 'required';
            $this->rules['static_link'] = 'required';
        }

        if ($this->data_type === 'static_plus' && $this->isDirty(['data_type', 'static_plus_title', 'static_plus_segment'])) {
            $this->rules['static_plus_title'] = 'required';
            $this->rules['static_plus_segment'] = 'required';
        }

        if ($this->data_type === 'cms' && $this->isDirty(['data_type', 'cms_page'])) {
            $this->rules['cms_page'] = 'required';
        }
    }

    public function beforeSave()
    {
        $attributes = [];

        foreach ($this->purgeable as $item) {
            if ($this->getOriginalPurgeValue($item)) {
                $attributes[$item] = $this->getOriginalPurgeValue($item);
            }
        }

        $this->data = count($attributes) === 0
            ? $this->data
            : $attributes;
    }

    public function afterSave()
    {
        (new BreadcrumbItem($this->id))->forgetCache();
    }

    /**
     * @return string
     */
    public function getStaticTitleAttribute()
    {
        return array_get($this->data, 'static_title', '');
    }

    /**
     * @return string
     */
    public function getStaticLinkAttribute()
    {
        return array_get($this->data, 'static_link', '');
    }

    /**
     * @return string
     */
    public function getStaticPlusTitleAttribute()
    {
        return array_get($this->data, 'static_plus_title', '');
    }

    /**
     * @return string
     */
    public function getStaticPlusSegmentAttribute()
    {
        return array_get($this->data, 'static_plus_segment', '');
    }

    /**
     * @return string
     */
    public function getCmsPageAttribute()
    {
        return array_get($this->data, 'cms_page', '');
    }

    /**
     * @return string
     */
    public function getCmsTitleFieldAttribute()
    {
        return array_get($this->data, 'cms_title_field', '');
    }

    /**
     * @param $fields
     * @param null $context
     */
    public function filterFields($fields, $context = null)
    {
        if (in_array($context, ['create', 'update'])) {
            switch ($fields->{'data_type'}->value) {
                case 'static':
                    $fields->{'static_plus_title'}->value = null;
                    $fields->{'static_plus_segment'}->value = null;
                    $fields->{'cms_page'}->value = null;
                    $fields->{'cms_title_field'}->value = null;
                    break;

                case 'static_plus':
                    $fields->{'static_title'}->value = null;
                    $fields->{'static_link'}->value = null;
                    $fields->{'cms_page'}->value = null;
                    $fields->{'cms_title_field'}->value = null;
                    break;

                case 'cms':
                    $fields->{'static_title'}->value = null;
                    $fields->{'static_link'}->value = null;
                    $fields->{'static_plus_title'}->value = null;
                    $fields->{'static_plus_segment'}->value = null;
                    break;
            }
        }

        if ($context === 'preview') {
            if ($this->trashed()) {
                $fields->{'_information'}->hidden = true;
                $fields->{'_code'}->hidden = true;
            } else {
                $fields->{'_code'}->value = (new BreadcrumbItem($this->id))->getBreadcrumbs()->getCode();
            }
        }
    }
}