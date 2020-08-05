<?php

namespace Lpp\Controller;

use Symfony\Component\HttpFoundation\Request;
use Lpp\Entity\TestAutoLoading;
use Symfony\Component\HttpFoundation\JsonResponse;

class ItemController
{
    public function test(Request $request)
    {
        $test = new TestAutoLoading();
        return new JsonResponse($test->go());
    }
}