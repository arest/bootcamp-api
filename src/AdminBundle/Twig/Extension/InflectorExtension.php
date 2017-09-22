<?php
namespace AdminBundle\Twig\Extension;

use Doctrine\Common\Util\Inflector;

class InflectorExtension extends \Twig_Extension
{

    
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('tableize', array($this, 'tableize')),
            new \Twig_SimpleFilter('classify', array($this, 'classify')),
            new \Twig_SimpleFilter('camelize', array($this, 'camelize')),
            new \Twig_SimpleFilter('className', array($this, 'className')),

        );
    }

    public function tableize($value)
    {
        return Inflector::tableize($value);
    }

    public function classify($value)
    {
        return Inflector::classify($value);
    }

    public function camelize($value)
    {
        return Inflector::camelize($value);
    }

    public function className($object)
    {
        return (new \ReflectionClass($object))->getShortName();
    }

    public function getName()
    {
        return 'app_inflector_extension';
    }
}