<?php

namespace ES\FotoBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * FotoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FotoRepository extends EntityRepository
{
    public function ultimaFoto($id_showroom) {
        $q = $this->createQueryBuilder('m');
        $q->add('orderBy', 'm.id DESC');
        $q->where('m.showroom =:id_showroom');
        $q->setParameter('id_showroom', $id_showroom);
        $q->setMaxResults(1);
        $dql = $q->getQuery();        
        $dql->getSingleResult();
        $results = $dql->execute();
        return $results;
    }

}