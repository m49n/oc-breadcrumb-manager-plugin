<?php namespace Dubk0ff\BreadcrumbManager\Classes;

use Cache;
use Dubk0ff\BreadcrumbManager\Models\Settings;
use Dubk0ff\BreadcrumbManager\Models\Template as TemplateModel;
use Lang;
use October\Rain\Exception\ExceptionBase;

/**
 * Class TemplateItem
 * @package Dubk0ff\BreadcrumbManager\Classes
 */
class TemplateItem
{
    /** @var int|null  */
    public $id;

    /** @var  */
    public $template;

    /**
     * TemplateItem constructor.
     * @param int|null $id
     */
    public function __construct(int $id = null)
    {
        $this->id = $id;
    }

    /**
     * @return $this
     * @throws ExceptionBase
     */
    public function getTemplate()
    {
        $this->template = Settings::get('use_templates_cache', false)
            ? $this->getTemplateCache()
            : $this->getTemplateModel();

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getTemplateCache()
    {
         return Cache::remember($this->getCacheKey(), Settings::get('templates_cache_expire'), function () {
            return $this->getTemplateModel();
         });
    }

    /**
     * @return array
     * @throws ExceptionBase
     */
    protected function getTemplateModel()
    {
        $model = (new TemplateModel)->getTemplate($this->id);

        if (! $model) {
            throw new ExceptionBase(sprintf(Lang::get('dubk0ff.breadcrumbmanager::lang.exceptions.templates_not_found'), $this->id));
        }

        return $model->toArray();
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->template['code'];
    }

    /**
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getDefaultCode()
    {
        return (new ContentViewer)
            ->getStubFileContent('template')
            ->getContent();
    }

    /**
     * @return string
     */
    public function getCacheKey()
    {
        return 'breadcrumbmanager::template.id.' . $this->id;
    }

    public function forgetCache()
    {
        Cache::forget($this->getCacheKey());
    }
}