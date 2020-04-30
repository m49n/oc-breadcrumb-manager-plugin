<?php namespace Dubk0ff\BreadcrumbManager\Classes;

use Cache;
use Cms\Classes\Page;
use Dubk0ff\BreadcrumbManager\Models\Breadcrumb as BreadcrumbModel;
use Dubk0ff\BreadcrumbManager\Models\Settings;
use Lang;
use October\Rain\Exception\ExceptionBase;
use October\Rain\Router\Rule;

/**
 * Class BreadcrumbItem
 * @package Dubk0ff\BreadcrumbManager\Classes
 */
class BreadcrumbItem
{
    /** @var int|null  */
    public $id;

    /** @var  */
    public $breadcrumbs;

    /**
     * BreadcrumbItem constructor.
     * @param int|null $id
     */
    public function __construct(int $id = null)
    {
        $this->id = $id;
    }

    /**
     * @param bool $isCache
     * @return $this
     * @throws ExceptionBase
     */
    public function getBreadcrumbs(bool $isCache = false)
    {
        $this->breadcrumbs = $isCache
            ? $this->getBreadcrumbsCache()
            : $this->getBreadcrumbsModel();

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getBreadcrumbsCache()
    {
         return Cache::remember($this->getCacheKey(), Settings::get('breadcrumbs_cache_expire'), function () {
            return $this->getBreadcrumbsModel();
        });
    }

    /**
     * @return array
     * @throws ExceptionBase
     */
    protected function getBreadcrumbsModel()
    {
        $model = (new BreadcrumbModel)->getBreadcrumb($this->id);

        if (! $model) {
            throw new ExceptionBase(sprintf(Lang::get('dubk0ff.breadcrumbmanager::lang.exceptions.breadcrumbs_not_found'), $this->id));
        }

        foreach ($model->getParentsAndSelf() as $item) {
            $this->parseBreadcrumbModel($item);
        }

        return $this->breadcrumbs;
    }

    /**
     * @param BreadcrumbModel $model
     */
    protected function parseBreadcrumbModel(BreadcrumbModel $model)
    {
        $model->attributes['data'] = (array) json_decode($model->attributes['data']);
        $this->breadcrumbs[] = $model->attributes;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        $code = [
            'id' => $this->id
        ];

        foreach ($this->breadcrumbs as $breadcrumb) {
            if ($breadcrumb['data_type'] !== 'cms') {
                continue;
            }

            if (! array_key_exists('cms_title_field', $breadcrumb['data'])) {
                $code['titles'][$breadcrumb['id']] = 'Your best of the best headlines!';
            }

            $parameters = $this->getParameters($breadcrumb['data']['cms_page']);

            $code['parameters'] = array_key_exists('parameters', $code)
                ? array_merge($code['parameters'], $parameters)
                : $parameters;
        }

        return (new ContentViewer('breadcrumbs', $code))
            ->getStubFileContent('breadcrumbs')
            ->getParseContent();
    }

    /**
     * @param string $fileName
     * @return Page|null
     */
    protected function getPage(string $fileName)
    {
        return (new Page)
            ->newQuery()
            ->whereFileName($fileName)
            ->first();
    }

    /**
     * @param Page $page
     * @return Rule
     */
    protected function getRule(Page $page)
    {
        return new Rule($page->fileName, $page->url);
    }

    /**
     * @param string $fileName
     * @return array
     */
    protected function getParameters(string $fileName)
    {
        $parameters = [];
        $rule = $this->getRule($this->getPage($fileName));

        foreach ($rule->segments as $segment) {
            if (strpos($segment, ':') !== false) {
                $parameters[ltrim($segment, ':')] = 'your_url_slug';
            }
        }

        return $parameters;
    }

    /**
     * @return string
     */
    protected function getCacheKey()
    {
        return 'breadcrumbmanager::breadcrumb.id.' . $this->id;
    }

    public function forgetCache()
    {
        Cache::forget($this->getCacheKey());
    }

    public function importPages()
    {
        Page::all()->each(function ($item) {
            $data = [
                'name'  => $item->title,
                'data_type'  => 'cms',
                'cms_page' => $item->baseFileName
            ];

            if (isset($item->crumb_title) && ! empty($item->crumb_title)) {
                $data['cms_title_field'] = 'crumb_title';
            }

            BreadcrumbModel::create($data);
        });
    }
}