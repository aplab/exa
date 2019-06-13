<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 26.08.2018
 * Time: 15:00
 */

namespace App\Controller;


use App\Component\Helper\AdminControllerHelper;
use LogicException;
use ReflectionClass;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class BaseAdminController
 * @package App\Controller
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
     * @var ReflectionClass
     */
    protected $entityClass;

    /**
     * BaseAdminController constructor.
     * @param AdminControllerHelper $adminControllerHelper
     * @throws ReflectionException
     */
    final public function __construct(AdminControllerHelper $adminControllerHelper)
    {
        $this->adminControllerHelper = $adminControllerHelper;
        if (!isset($this->entityClassName)) {
            throw new LogicException(get_class($this) . ' must have a protected $entityClassName = Entity::class');
        }
        $this->entityClass = new ReflectionClass($this->getEntityClassName());
        $this->adminControllerHelper->getHtmlTitle()->setTitle($this->getEntityClass()->getShortName());
        $reader = $adminControllerHelper->getAnnotationsReader();
        $this->routeAnnotation = $reader->getClassAnnotation(new ReflectionClass(static::class), Route::class);
    }

    /**
     * @return mixed
     */
    public function getEntityClassName() {
        /** @noinspection PhpUndefinedFieldInspection */
        return $this->entityClassName;
    }

    /**
     * @return AdminControllerHelper
     */
    public function getAdminControllerHelper(): AdminControllerHelper
    {
        return $this->adminControllerHelper;
    }

    /**
     * @return ReflectionClass
     */
    public function getEntityClass(): ReflectionClass
    {
        return $this->entityClass;
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
        /** @noinspection PhpParamsInspection */
        $routes = $this->get('router')->getRouteCollection();
        return $routes->get($routename)->getDefaults()['_controller'];
    }
}
