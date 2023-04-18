<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ResourceNotFoundException extends Exception
{
  public function __construct(string $resource, string $id)
  {
    $message = "A resource with the specified $id was not found that matches the $resource.";
    $code = Response::HTTP_NOT_FOUND;
    parent::__construct($message, $code);
  }
}
