<?php

namespace Marcusklaas\HangmanBundle\Repository;

use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository
{
    /**
     * @return int[]
     */
    public function getIdList()
    {
        $queryBuilder = $this->createQueryBuilder('game');
        $queryBuilder->select('game.id');

        $query = $queryBuilder->getQuery();

        return array_map(function (array $row) {
            return reset($row);
        }, $query->getResult());
    }
}