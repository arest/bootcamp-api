<?php

namespace AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use AdminBundle\Event\MenuEvent;
use AdminBundle\MenuEvents;

class AdminBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected $attr_class = '';

    public function sidebarMenu(FactoryInterface $factory, array $options)
    {
        $routes = $this->container->getParameter('simple_admin.routes');
        $security_checker = $this->container->get('security.authorization_checker');

        $menu = $factory->createItem('root', array('childrenAttributes' => array('class' => 'sidebar-menu')) );

        foreach ($routes as $route) {
            
            $route_code = "_app_crud_".$route."_list";

            if ( $security_checker->isGranted('view_route', $route_code) ) {
                $menu->addChild( "admin.menu.".$route, array(
                    'route' => $route_code,
                    'linkAttributes' => array(
                        'class' => $this->attr_class
                    ),
                    'attributes' => array(
                        'class' => 'treeview',
                    )
                    )
                )->setExtra('translation_domain', 'messages');
            }

        }

        $event_dispatcher = $this->container->get('event_dispatcher');
        $event = new MenuEvent($menu);
        $event_dispatcher->dispatch(MenuEvents::POST_BUILD, $event);

        return $menu;
    }

}