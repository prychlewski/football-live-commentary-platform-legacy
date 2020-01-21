<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class BaseRepository extends ServiceEntityRepository
{
    protected function genericAdd(object $object): void
    {
        $this->_em->persist($object);
        $this->_em->flush();
    }

    protected function genericUpdate(object $object): void
    {
        // Alias for save
        $this->genericAdd($object);
    }

    protected function genericDelete(object $object): void
    {
        $this->_em->remove($object);
        $this->_em->flush();
    }
}
