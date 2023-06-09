<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Doctrine\ORM\EntityManagerInterface;

class AssociationNormalizingListener {

	private $entityManager;

<<<<<<< HEAD
	public function __construct(EntityManagerInterface $entityManager)
    {
		$this->entityManager = $entityManager;
	}

	public function __invoke(ControllerArgumentsEvent $event)
    {
=======
	public function __construct(EntityManagerInterface $entityManager) 
	{
		$this->entityManager = $entityManager;
	}

	public function __invoke(ControllerArgumentsEvent $event) 
	{
>>>>>>> 0eefbbb9efd920b4341f024fe68364311fc63d58
		$request = $event->getRequest();

		if (
			!$event->isMainRequest() ||
			!$request->attributes->has('resource') ||
			$request->getMethod() !== 'POST'
		) {
			return;
		}

		$requestBody = $request->request;
		[$resource] = $event->getArguments();
		$classMetadata = $this->entityManager->getClassMetadata($resource);

		if (!($associatedResourceNames = $classMetadata->getAssociationNames())) {
			return;
		}
		foreach ($associatedResourceNames as $associatedResourceName) {
			if (
				!($associatedResourceValue = $requestBody->get(
					$associatedResourceName,
					false
				))
			) {
				continue;
			}
			$associatedResourceFqcn = $classMetadata->getAssociationMapping(
				$associatedResourceName
			)['targetEntity'];

			if (!is_array($associatedResourceValue) && $associatedResourceValue) {
				$proxyReference = $this->entityManager->getReference(
					$associatedResourceFqcn,
					$associatedResourceValue
				);
				$requestBody->set($associatedResourceName, $proxyReference);
			} elseif (!empty($associatedResourceValue)) {
				$proxyReferences = [];
				foreach ($associatedResourceValue as $value) {
					$proxyReferences[] = $this->entityManager->getReference(
						$associatedResourceFqcn,
						$value
					);
				}
				$requestBody->set($associatedResourceName, $proxyReferences);
			}
		}

		return;
	}
}
