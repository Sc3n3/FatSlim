<?php namespace Sc3n3\FatSlim\Views;

class Blade extends \Slim\View
{
    /**
     * @var string The path to the Blade code directory WITHOUT the trailing slash
     */
    public $parserDirectory = null;

    /**
     * @var array The options for the Blade environment, see
     */
    public $parserOptions = array();

    /**
     * @var BladeEnvironment The Blade environment for rendering templates.
     */
    private $parserInstance = null;

    /**
     * Render Blade Template
     *
     * This method will output the rendered template content
     *
     * @param string $template The path to the Blade template, relative to the Blade templates directory.
     * @param null $data
     * @return string
     */
    public function render($template, $data = null)
    {

        if( preg_match('/^@([^\/]+)\//', $template, $match) ) {
            $template = str_replace($match[0], $match[1] .'::', $template);
        }

        $env = $this->getInstance();
        
        $data = array_merge($this->all(), (array) $data);
        try {
            $output = $env->render($template, $data);
        } catch (Exception $e) {
			throw new \RuntimeException($e->getMessage());
        }

        return $output;
    }

    /**
     * Creates new BladeEnvironment if it doesn't already exist, and returns it.
     *
     * @return \Blade_Environment
     */
    public function getInstance()
    {
        if (!$this->parserInstance) {

            $views = $this->getTemplatesDirectory().'/';

			if(isset($this->parserOptions['cache'])) {
				$cache = $this->parserOptions['cache'];
			} else {
				throw new \RuntimeException('Cannot set the Blade cache directory');
			}

            $this->parserInstance = new \duncan3dc\Laravel\BladeInstance($views, $cache);
        }

        return $this->parserInstance;
    }

    public function addNamespace($prefix, $path) {

        return $this->getInstance()->addNamespace($prefix, $path);
    }
}
