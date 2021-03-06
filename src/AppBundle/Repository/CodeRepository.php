<?php

namespace AppBundle\Repository;

/**
 * CodeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CodeRepository extends \Doctrine\ORM\EntityRepository
{
    public function getCode($email)
    {

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT  l.code
        FROM AppBundle:Code l
        WHERE l.emailAddress = :email
        AND  l.date in (
    select  max(a.date)
    from AppBundle:Code a)'
        )
            ->setParameter('email', $email);

        // returns an array of Product objects
        return $query->getResult();

    }
}
