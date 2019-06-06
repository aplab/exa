<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 19.08.2018
 * Time: 16:02
 */

namespace App\Repository;


use App\Entity\Bind\Container;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class BindContainerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Container::class);
    }

    /**
     * @param null $selected
     * @return array|mixed
     */
    public function getOptionsDataList($selected = null)
    {
        $tmp = $this->findAll();
        array_walk($tmp, function ($v, $k) use (& $tmp, $selected) {
            $tmp[$k] = array(
                'value' => $v->getId(),
                'text' => $v->getName(),
                'selected' => $v === $selected
            );
        });
        return $tmp;
    }
}
