<?php

declare(strict_types=1);

namespace App\EventListener;

use Aropixel\AdminBundle\Event\ConfigureMenuEvent;
use Aropixel\AdminBundle\Menu\AbstractMenuListener;

class ConfigureMenuListener extends AbstractMenuListener
{
    /**
     * @param ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();
        $this->factory = $event->getFactory();
        $this->em = $event->getEntityManager();
        $this->routeName = $request->get('_route');
        $this->routeParameters = $request->get('_route_params');

        $this->menu = $event->getAppMenu('main');
        if (!$this->menu) {
            $this->menu = $this->createRoot();
        }

        //DASHBOARD ______________________________________________
        //

        $this->addCategory('Accueil');
        $this->addItem('Tableau de bord', '_admin', 'fas fa-globe');

        //$routeHome = array('route' => 'home_edit', 'routeParameters' => array('id' => 1));
        //$this->addItem('Accueil', $routeHome, 'fas fa-home');

        //BLOG ______________________________________________
        //

        $groupItem = $this->createGroupItem('Blog', 'fas fa-newspaper');
        $this->addSubItem($groupItem, 'Posts', 'post_index');
        $this->addGroupItem($groupItem);

        //CONTACT ______________________________________________
        //

        $this->addItem('Mails', 'contact_index', 'far fa-envelope');

        // PARAMETRES ______________________________________________
        //
        $this->addItem('Paramètres', 'param_index', 'fas fa-cog');

        // ADMINISTRATION ______________________________________________
        //

        $this->addCategory('Administration');
        $this->addItem('Utilisateurs', 'user_index', 'fas fa-users');

//        }

        //______________________________________________
        //
        //
        $event->addAppMenu($this->menu, false, 'main');
    }
}
