<?php

/*
 * This file is part of Phraseanet
 *
 * (c) 2005-2014 Alchemy
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\Model\Repositories;

use Alchemy\Phrasea\Model\Entities\User;
use Doctrine\ORM\EntityRepository;

/**
 * FtpExportRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FtpExportRepository extends EntityRepository
{
    /**
     * Returns exports that crashed. If a date is provided, only exports created
     * before this date are returned.
     *
     * @param \DateTime $before An optional date to search
     *
     * @return array
     */
    public function findCrashedExports(\DateTime $before = null)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->where($qb->expr()->gte('e.crash', 'e.nbretry'));

        if (null !== $before) {
            $qb->andWhere($qb->expr()->lte('e.created', ':created'));
            $qb->setParameter(':created', $before);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Returns a list of exports that can be achieved.
     *
     * @return array
     */
    public function findDoableExports()
    {
        $dql = 'SELECT f
            FROM Phraseanet:FtpExport f
                INNER JOIN f.elements e
            WHERE e.done = false';

        $query = $this->_em->createQuery($dql);

        return $query->getResult();
    }

    /**
     * Returns the exports initiated by a given user.
     *
     * @param User $user
     *
     * @return array
     */
    public function findByUser(User $user)
    {
        return $this->findBy(['user' => $user]);
    }
}
