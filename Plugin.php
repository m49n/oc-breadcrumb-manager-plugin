<?php namespace Dubk0ff\BreadcrumbManager;

use Backend;
use Config;
use Event;
use Lang;
use System\Classes\PluginBase;

/**
 * Class Plugin
 * @package Dubk0ff\BreadcrumbManager
 */
class Plugin extends PluginBase
{
    /**
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'dubk0ff.breadcrumbmanager::lang.plugin.name',
            'description' => 'dubk0ff.breadcrumbmanager::lang.plugin.description',
            'author'      => 'Dubk0ff',
            'icon'        => 'icon-link'
        ];
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/breadcrumbs.php', 'breadcrumbs');

        Config::set('breadcrumbs.data_types', Lang::get('dubk0ff.breadcrumbmanager::lang.config.data_types'));
        Config::set('breadcrumbs.title_fields', Lang::get('dubk0ff.breadcrumbmanager::lang.config.title_fields'));

        Event::listen('backend.form.extendFields', function($widget) {
            if (! $widget->model instanceof \Cms\Classes\Page) {
                return;
            }
            $widget->addFields(
                [
                    'settings[crumb_title]' => [
                        'label'   => 'dubk0ff.breadcrumbmanager::lang.models.crumb_title.label',
                        'type'    => 'text',
                        'tab'     => 'dubk0ff.breadcrumbmanager::lang.models.crumb_title.tab',
                        'span'    => 'full',
                        'comment' => 'dubk0ff.breadcrumbmanager::lang.models.crumb_title.comment',
                    ],
                ],
                'primary'
            );
        });
    }

    /**
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Dubk0ff\BreadcrumbManager\Components\Breadcrumbs' => 'breadcrumbs',
        ];
    }

    /**
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'dubk0ff.breadcrumbmanager.access_breadcrumbs' => [
                'tab'   => 'dubk0ff.breadcrumbmanager::lang.plugin.tab',
                'label' => 'dubk0ff.breadcrumbmanager::lang.plugin.access.breadcrumbs'
            ],
            'dubk0ff.breadcrumbmanager.access_templates' => [
                'tab'   => 'dubk0ff.breadcrumbmanager::lang.plugin.tab',
                'label' => 'dubk0ff.breadcrumbmanager::lang.plugin.access.templates'
            ],
            'dubk0ff.breadcrumbmanager.access_settings' => [
                'tab'   => 'dubk0ff.breadcrumbmanager::lang.plugin.tab',
                'label' => 'dubk0ff.breadcrumbmanager::lang.plugin.access.settings'
            ]
        ];
    }

    /**
     * @return array
     */
    public function registerSettings()
    {
        return [
            'breadcrumb_manager_breadcrumbs' => [
                'label'       => 'dubk0ff.breadcrumbmanager::lang.plugin.settings.breadcrumbs.label',
                'description' => 'dubk0ff.breadcrumbmanager::lang.plugin.settings.breadcrumbs.description',
                'category'    => 'dubk0ff.breadcrumbmanager::lang.plugin.settings.category',
                'icon'        => 'icon-link',
                'url'         => Backend::url('dubk0ff/breadcrumbmanager/breadcrumbs'),
                'permissions' => ['dubk0ff.breadcrumbmanager.access_breadcrumbs'],
                'order'       => 500,
            ],
            'breadcrumb_manager_templates' => [
                'label'       => 'dubk0ff.breadcrumbmanager::lang.plugin.settings.templates.label',
                'description' => 'dubk0ff.breadcrumbmanager::lang.plugin.settings.templates.description',
                'category'    => 'dubk0ff.breadcrumbmanager::lang.plugin.settings.category',
                'icon'        => 'icon-files-o',
                'url'         => Backend::url('dubk0ff/breadcrumbmanager/templates'),
                'permissions' => ['dubk0ff.breadcrumbmanager.access_templates'],
                'order'       => 600,
            ],
            'breadcrumb_manager_settings' => [
                'label'       => 'dubk0ff.breadcrumbmanager::lang.plugin.settings.settings.label',
                'description' => 'dubk0ff.breadcrumbmanager::lang.plugin.settings.settings.description',
                'category'    => 'dubk0ff.breadcrumbmanager::lang.plugin.settings.category',
                'icon'        => 'icon-cog',
                'class'       => 'Dubk0ff\BreadcrumbManager\Models\Settings',
                'permissions' => ['dubk0ff.breadcrumbmanager.access_settings'],
                'order'       => 700
            ]
        ];
    }
}