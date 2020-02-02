<?php

namespace App\Repository;

use App\Entity\FootballMatch;
use App\Entity\Comment;
use Doctrine\Common\Persistence\ManagerRegistry;

class CommentRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function add(Comment $comment): void
    {
        $this->genericAdd($comment);
    }

    public function update(Comment $comment): void
    {
        $this->genericUpdate($comment);
    }

    public function delete(Comment $comment): void
    {
        $this->genericDelete($comment);
    }

    public function findByFootballMatch(FootballMatch $footballMatch)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $query = $queryBuilder->select('c.id, c.content, c.date')
            ->from(Comment::class, 'c')
            ->where('c.footballMatch = :footballMatch')
            ->setParameter('footballMatch', $footballMatch)
            ->getQuery();

        return $query->execute();
    }
}
