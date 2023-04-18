<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use App\Controller\CurrencyRateApiController;

class ResourceResolvingListener {

	public function __invoke(ControllerArgumentsEvent $event): void
    {
		$controller = $event->getController();

		if (!$event->isMainRequest()) {
			return;
		}
		if (!is_array($controller) || !$controller[0] instanceof CurrencyRateApiController) {
			return;
		}
		if (!$event->getRequest()->attributes->has('resource')) {
			return;
		}

		$arguments = $event->getArguments();
		[$resource] = $arguments;
		$arguments[0] =
			'App\\Entity\\' . str_replace('-', '', ucwords($resource, ' -'));

		$event->setArguments($arguments);
	}
}
