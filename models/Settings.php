<?php namespace Dubk0ff\BreadcrumbManager\Models;

use Model;

/**
 * Class Settings
 * @package Dubk0ff\BreadcrumbManager\Models
 */
class Settings extends Model
{
    /** @var array  */
    public $implement = [
        \System\Behaviors\SettingsModel::class
    ];

    /** @var string  */
    public $settingsCode = 'breadcrumb_manager_settings';

    /** @var string  */
    public $settingsFields = 'fields.yaml';

    public function initSettingsData()
    {
        $this->use_breadcrumbs_cache = false;
        $this->use_templates_cache = false;
        $this->breadcrumbs_cache_expire = 1440;
        $this->templates_cache_expire = 1440;
    }
}