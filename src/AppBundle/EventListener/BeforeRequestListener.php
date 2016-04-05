<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class BeforeRequestListener
{
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $filters = $this->em->getFilters()
            ->enable('fortune_cookie_discontinued');

        $filters->setParameter('discontinued', false);
    }
}