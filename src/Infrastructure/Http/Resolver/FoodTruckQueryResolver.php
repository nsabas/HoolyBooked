<?php

namespace App\Infrastructure\Http\Resolver;

use App\Application\FoodTruck\Query\ListFoodTruckQuery;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

#[AutoconfigureTag('controller.argument_value_resolver', ['priority' => 150])]
class FoodTruckQueryResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() !== ListFoodTruckQuery::class) {
            return [];
        }

        yield new ListFoodTruckQuery(
            type: $request->query->get('type'),
        );
    }
}
