<?php namespace Dubk0ff\BreadcrumbManager\Classes;

use October\Rain\Filesystem\Filesystem;
use Twig;

/**
 * Class ContentViewer
 * @package Dubk0ff\BreadcrumbManager\Classes
 */
class ContentViewer
{
    /** @var array  */
    protected $stubs = [
        'breadcrumbs' => 'dubk0ff/breadcrumbmanager/stubs/breadcrumbs.stub',
        'template' => 'dubk0ff/breadcrumbmanager/stubs/template.stub'
    ];

    /** @var  */
    protected $content;

    /** @var string  */
    protected $variableName;

    /** @var array  */
    protected $variables;

    /**
     * ContentViewer constructor.
     * @param string $variableName
     * @param array $variables
     */
    public function __construct(string $variableName = 'variables', array $variables = [])
    {
        $this->variableName = $variableName;
        $this->variables = $variables;
    }

    /**
     * @return string
     */
    public function getParseContent()
    {
        return Twig::parse($this->content, [$this->variableName => $this->variables]);
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $stub
     * @return $this
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getStubFileContent(string $stub)
    {
        $this->content = (new Filesystem)->get(plugins_path($this->stubs[$stub]));

        return $this;
    }
}