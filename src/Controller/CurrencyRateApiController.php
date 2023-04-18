<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Hydrator\ReflectionHydrator;
use App\Exception\ResourceNotFoundException;


class CurrencyRateApiController extends AbstractFOSRestController
{
    private $manager;

    public function __construct(
        EntityManagerInterface $manager
    ){
        $this->manager = $manager;
    }

    /**
     * @Rest\Get("/{resource}")
     */
    public function currencyRateList(
        string $resource,
        Request $request
    ): View {

        $repository = $this->manager->getRepository($resource);

        if ($query = $request->query->all()) {
            $args = ['order' => null, 'limit' => null, 'offset' => null];
            $query = array_merge($args, $query);
            $args = array_intersect_key($query, $args);
            $query = array_diff_key($query, $args);
            $resource = $repository->findBy($query, ...$args);
        } else {
            $resource = $repository->findAll();
        }
        return View::create($resource, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/{resource}/{id}")
     */
    public function showCurrencyRate(
        string $resource,
        string $id
    ): View {

        $repository = $this->manager->getRepository($resource);
        $this->doctrine->

        $entity = $repository->findOneBy(['id' => $id]);

        if (!$entity) {
            throw new ResourceNotFoundException($resource, $id);
        }
        return View::create($entity, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/{resource}")
     */
    public function createCurrencyRate(
        string $resource,
        Request $request
    ): View {

        $args = $request->request->all();

        if (!$args) {
            $args = (array) json_decode($request->getContent());
        }

        $entity = new $resource();

        $hydrator = new ReflectionHydrator();

        $hydrator->hydrate($args, $entity);

        $this->manager->persist($entity);
        $this->manager->flush();
        return View::create($entity, Response::HTTP_OK);
    }
}
