<?php
/**
 * Created by PhpStorm.
 * User: polyanin
 * Date: 04.09.2018
 * Time: 16:39
 */

namespace App\Repository;


use App\Entity\HistoryUploadImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class HistoryUploadImageRepository
 * @package App\Repository
 */
class HistoryUploadImageRepository extends ServiceEntityRepository
{
    /**
     * HistoryUploadImageRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HistoryUploadImage::class);
    }

    /**
     * @param HistoryUploadImage $item
     */
    public function deleteSamePath(HistoryUploadImage $item)
    {
        $db = $this->getEntityManager()->createQueryBuilder()
            ->delete(HistoryUploadImage::class, 'h')
            ->where('h.path = :path')
            ->andWhere('h.favorites != 1')
            ->andWhere('h.id != :id')
            ->setParameter('path', $item->getPath())
            ->setParameter('id', $item->getId());
        $db->getQuery()->execute();
    }
}
