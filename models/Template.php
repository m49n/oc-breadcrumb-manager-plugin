<?php namespace Dubk0ff\BreadcrumbManager\Models;

use Dubk0ff\BreadcrumbManager\Classes\TemplateItem;
use Model;

/**
 * Class Template
 * @package Dubk0ff\BreadcrumbManager\Models
 */
class Template extends Model
{
    use \October\Rain\Database\Traits\SoftDelete;
    use \October\Rain\Database\Traits\Validation;

    /** @var string  */
    public $table = 'dubk0ff_breadcrumbs_templates';

    /** @var array  */
    protected $guarded = ['*'];

    /** @var array  */
    public $rules = [
        'title' => 'required',
        'code'  => 'required'
    ];

    /** @var array  */
    protected $casts = [
        'is_active' => 'bool',
    ];

    /** @var array  */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function afterSave()
    {
        (new TemplateItem($this->id))->forgetCache();
    }

    /**
     * @param $fields
     * @param null $context
     */
    public function filterFields($fields, $context = null)
    {
        if ($context === 'create') {
            $fields->{'code'}->value = (new TemplateItem)->getDefaultCode();
        }
    }

    /**
     * @return Template|null
     */
    public function getTemplate($id)
    {
        return self::newQuery()
            ->whereId($id)
            ->whereIsActive(true)
            ->get()
            ->first();
    }

    /**
     * @return array
     */
    public function getTemplatesList()
    {
        return self::newQuery()
            ->whereIsActive(true)
            ->get()
            ->lists('title', 'id');
    }
}