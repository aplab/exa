<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 18.08.2018
 * Time: 9:46
 */

namespace Aplab\AplabAdminBundle\Controller;


use Aplab\AplabAdminBundle\Component\ActionMenu\ActionMenuManager;
use Aplab\AplabAdminBundle\Component\Menu\MenuManager;
use Aplab\AplabAdminBundle\Component\Toolbar\ToolbarManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController
{
    /**
     * @param MenuManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Aplab\AplabAdminBundle\Component\Menu\Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function mainMenu(MenuManager $manager)
    {
        $menu = $manager->getMenu('MainMenu');
        return $this->render(
            '@AplabAdmin/main-menu.html.twig', [
                'json' => $menu->__toJson()
            ]
        );
    }

    /**
     * @param ActionMenuManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Aplab\AplabAdminBundle\Component\ActionMenu\Exception
     */
    public function actionMenu(ActionMenuManager $manager)
    {
        $menu = $manager->getInstance();
        return $this->render(
            '@AplabAdmin/action-menu.html.twig', [
                'json' => $menu->__toJson()
            ]
        );
    }

    /**
     * @param ToolbarManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Aplab\AplabAdminBundle\Component\Toolbar\Exception
     */
    public function toolbar(ToolbarManager $manager)
    {
        $menu = $manager->getInstance();
        return $this->render(
            '@AplabAdmin/toolbar.html.twig', [
                'json' => $menu->__toJson()
            ]
        );
    }
}