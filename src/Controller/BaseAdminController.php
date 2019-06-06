<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 26.08.2018
 * Time: 15:00
 */

namespace Aplab\AplabAdminBundle\Controller;


use Aplab\AplabAdminBundle\Component\Helper\AdminControllerHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class BaseAdminController
 * @package Aplab\AplabAdminBundle\Controller
 */
abstract class BaseAdminController extends AbstractController
{
    /**
     * @var AdminControllerHelper
     */
    protected $adminControllerHelper;

    /**
     * @var Route
     */
    protected $routeAnnotation;

    /**
     * BaseAdminController constructor.
     * @param AdminControllerHelper $adminControllerHelper
     * @throws \ReflectionException
     */
    final public function __construct(AdminControllerHelper $adminControllerHelper)
    {
        $this->adminControllerHelper = $adminControllerHelper;
        if (!isset($this->entityClassName)) {
            throw new \LogicException(get_class($this) . ' must have a protected $entityClassName = Entity::class');
        }
        $reader = $adminControllerHelper->getAnnotationsReader();
        $this->routeAnnotation = $reader->getClassAnnotation(new \ReflectionClass(static::class), Route::class);
    }

    /**
     * @return mixed
     */
    public function getEntityClassName() {
        /** @noinspection PhpUndefinedFieldInspection */
        return $this->entityClassName;
    }

    /**
     * @return Route
     */
    public function getRouteAnnotation(): Route
    {
        return $this->routeAnnotation;
    }

    /**
     * @param $routename
     * @return mixed
     */
    protected function routeToControllerName($routename) {
        $routes = $this->get('router')->getRouteCollection();
        return $routes->get($routename)->getDefaults()['_controller'];
    }
}