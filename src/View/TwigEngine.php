<?php


namespace WebRover\Framework\View;


use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Twig\Environment;
use Twig\Error\Error;

class TwigEngine extends \Symfony\Bridge\Twig\TwigEngine implements EngineInterface
{
    protected $locator;

    public function __construct(Environment $environment, TemplateNameParserInterface $parser, FileLocatorInterface $locator)
    {
        parent::__construct($environment, $parser);

        $this->locator = $locator;
    }

    /**
     * {@inheritdoc}
     */
    public function render($name, array $parameters = [])
    {
        try {
            return parent::render($name, $parameters);
        } catch (Error $e) {
            if ($name instanceof TemplateReference && !method_exists($e, 'setSourceContext')) {
                try {
                    // try to get the real name of the template where the error occurred
                    $name = $e->getTemplateName();
                    $path = (string) $this->locator->locate($this->parser->parse($name));
                    $e->setTemplateName($path);
                } catch (\Exception $e2) {
                }
            }

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws Error if something went wrong like a thrown exception while rendering the template
     */
    public function renderResponse($view, array $parameters = [], Response $response = null)
    {
        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($this->render($view, $parameters));

        return $response;
    }
}