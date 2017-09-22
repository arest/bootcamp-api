<?php

namespace AdminBundle\Router;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * App dynamic router
 */
class RoutesLoader implements LoaderInterface
{
    protected $route_patterns;

    public function __construct( $route_patterns )
    {
        $this->route_patterns = $route_patterns;
    }

    /**
     * @var boolean
     *
     * Route is loaded
     */
    private $loaded = false;

    /**
     * Loads a resource.
     *
     * @param mixed  $resource The resource
     * @param string $type     The resource type
     *
     * @return RouteCollection
     *
     * @throws RuntimeException Loader is added twice
     */
    public function load($resource, $type = null)
    {
        if ($this->loaded) {

            throw new \RuntimeException('Do not add this loader twice');
        }

        $routes = new RouteCollection();

        foreach ($this->route_patterns as $route_pattern) {

            $routes->add('_app_crud_'.$route_pattern.'_list', new Route($route_pattern.'/list', array(
                '_controller'   =>  'app.crud_controller.'.$route_pattern.':listAction',
            )));

            $routes->add('_app_crud_'.$route_pattern.'_create', new Route($route_pattern.'/create', array(
                '_controller'   =>  'app.crud_controller.'.$route_pattern.':createAction',
            )));

            $routes->add('_app_crud_'.$route_pattern.'_edit', new Route($route_pattern.'/edit/{id}', array(
                '_controller'   =>  'app.crud_controller.'.$route_pattern.':editAction',
            )));

            $routes->add('_app_crud_'.$route_pattern.'_remove', new Route($route_pattern.'/remove/{id}', array(
                '_controller'   =>  'app.crud_controller.'.$route_pattern.':removeAction',
            )));
            $routes->add('_app_crud_'.$route_pattern.'_export', new Route($route_pattern.'/export', array(
                '_controller'   =>  'app.crud_controller.'.$route_pattern.':exportAction',
            )));
        }
        $this->loaded = true;

        return $routes;
    }

    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return boolean This class supports the given resource
     */
    public function supports($resource, $type = null)
    {
        return 'simple_admin' === $type;
    }

    /**
     * Gets the loader resolver.
     *
     * @return LoaderResolverInterface A LoaderResolverInterface instance
     */
    public function getResolver()
    {
    }

    /**
     * Sets the loader resolver.
     *
     * @param LoaderResolverInterface $resolver A LoaderResolverInterface
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
    }
}