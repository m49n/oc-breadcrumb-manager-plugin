<?php namespace Dubk0ff\BreadcrumbManager\Components;

use Cms\Classes\ComponentBase;
use Dubk0ff\BreadcrumbManager\Classes\BreadcrumbItem;
use Dubk0ff\BreadcrumbManager\Classes\ContentViewer;
use Dubk0ff\BreadcrumbManager\Classes\TemplateItem;
use Dubk0ff\BreadcrumbManager\Models\Settings;
use Dubk0ff\BreadcrumbManager\Models\Template as TemplateModel;
use Lang;
use October\Rain\Exception\ExceptionBase;

class Breadcrumbs extends ComponentBase
{
    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'dubk0ff.breadcrumbmanager::lang.components.breadcrumbs.name',
            'description' => 'dubk0ff.breadcrumbmanager::lang.components.breadcrumbs.description'
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        return [
            'template' => [
                'title'       => 'dubk0ff.breadcrumbmanager::lang.components.breadcrumbs.properties.template_title',
                'description' => 'dubk0ff.breadcrumbmanager::lang.components.breadcrumbs.properties.template_description',
                'type'        => 'dropdown'
            ],
            'isJsonLd' => [
                'title'       => 'dubk0ff.breadcrumbmanager::lang.components.breadcrumbs.properties.isjsonld_title',
                'description' => 'dubk0ff.breadcrumbmanager::lang.components.breadcrumbs.properties.isjsonld_description',
                'type'        => 'checkbox',
                'default'     => false
            ]
        ];
    }

    /**
     * @return array
     */
    public function getTemplateOptions()
    {
        return (new TemplateModel)->getTemplatesList();
    }

    /**
     * @return mixed
     * @throws ExceptionBase
     */
    protected function getTemplateCode()
    {
        return (new TemplateItem($this->property('template', null)))
            ->getTemplate()
            ->getCode();
    }

    /**
     * @param array $breadcrumbs
     * @return string
     * @throws ExceptionBase
     */
    public function renderBreadcrumbs(array $breadcrumbs)
    {
        return (new ContentViewer('breadcrumbs', $breadcrumbs))
            ->setContent($this->getTemplateCode())
            ->getParseContent();
    }

    /**
     * @param array $breadcrumbs
     * @return string
     */
    public function renderJsonLd(array $breadcrumbs)
    {
        $json = [
            '@context' => 'http://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [],
        ];

        foreach ($breadcrumbs as $i => $breadcrumb) {
            $json['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'item' => [
                    '@id' => $breadcrumb['url'] ?: request()->fullUrl(),
                    'name' => $breadcrumb['title'],
                    'image' => $breadcrumb['image'] ?? null,
                ],
            ];
        }

        return json_encode($json);
    }

    /**
     * @return array
     * @throws ExceptionBase
     */
    public function makeBreadcrumbs()
    {
        $breadcrumbs = [];

        $parameters = $this->getBreadcrumbsParameters();
        $manager = (new BreadcrumbItem($parameters['id']))->getBreadcrumbs(Settings::get('use_breadcrumbs_cache', false));

        foreach ($manager->breadcrumbs as $key => $breadcrumb) {
            if (in_array($breadcrumb['id'], $parameters['hidden'] ?? [])) {
                continue;
            }

            switch ($breadcrumb['data_type']) {
                case 'static':
                    $breadcrumbs[$key]['title'] = $parameters['titles'][$breadcrumb['id']] ?? $breadcrumb['data']['static_title'];
                    $breadcrumbs[$key]['url'] = url($breadcrumb['data']['static_link']);
                    break;

                case 'static_plus':
                    $breadcrumbs[$key]['title'] = $parameters['titles'][$breadcrumb['id']] ?? $breadcrumb['data']['static_plus_title'];
                    $breadcrumbs[$key]['url'] = array_key_exists($key - 1, $breadcrumbs)
                        ? $breadcrumbs[$key - 1]['url'] . DIRECTORY_SEPARATOR . $breadcrumb['data']['static_plus_segment']
                        : url($breadcrumb['data']['static_plus_segment']);
                    break;

                case 'cms':
                    $breadcrumbs[$key]['title'] = (array_key_exists('cms_title_field', $breadcrumb['data']) && ! isset($parameters['titles'][$breadcrumb['id']]))
                        ? $this->controller->getPage()->newQuery()->whereFileName($breadcrumb['data']['cms_page'])->first()->attributes[$breadcrumb['data']['cms_title_field']] ?? 'Not found static crumb title'
                        : $parameters['titles'][$breadcrumb['id']] ?? 'Not found dynamic title in parameters';
                    $breadcrumbs[$key]['url'] = $this->controller->pageUrl($breadcrumb['data']['cms_page'], $parameters['parameters'] ?? []);
                    break;
            }
        }

        if (count($breadcrumbs) === 0) {
            throw new ExceptionBase(Lang::get('dubk0ff.breadcrumbmanager::lang.exceptions.breadcrumbs_zero'));
        }

        $breadcrumbs = array_values($breadcrumbs);

        if (array_key_exists('requestParams', $parameters) && count($parameters['requestParams']) > 0) {
            $breadcrumbs[count($breadcrumbs)-1]['url'] = $breadcrumbs[count($breadcrumbs)-1]['url'] . '?' . http_build_query($parameters['requestParams']);
        }

        return $breadcrumbs;
    }

    /**
     * @return mixed
     * @throws ExceptionBase
     */
    protected function getBreadcrumbsParameters()
    {
        if (! array_key_exists('breadcrumbs_parameters', $this->controller->vars)){
            throw new ExceptionBase(Lang::get('dubk0ff.breadcrumbmanager::lang.exceptions.parameters_not_found'));
        }

        return $this->controller->vars['breadcrumbs_parameters'];
    }
}