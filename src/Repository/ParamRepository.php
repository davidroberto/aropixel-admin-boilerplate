<?php

declare(strict_types=1);

/**
 * Créé par Aropixel @2016.
 * Par: Joël Gomez Caballe
 * Date: 24/10/2016 à 22:53.
 */

namespace App\Repository;

use App\Entity\Param;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Param|null find($id, $lockMode = null, $lockVersion = null)
 * @method Param|null findOneBy(array $criteria, array $orderBy = null)
 * @method Param[]    findAll()
 * @method Param[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParamRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Param::class);
    }

    public function get($id, $defaultValue = null)
    {
        $return = $defaultValue;
        $param = $this->find($id);
        if (!is_null($param)) {
            $return = $param->getValue();
        }

        return $return;
    }
}
