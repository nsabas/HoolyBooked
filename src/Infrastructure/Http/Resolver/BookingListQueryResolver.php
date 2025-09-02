<?php

namespace App\Infrastructure\Http\Resolver;

use App\Application\Booking\Query\ListBookingQuery;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

#[AutoconfigureTag('controller.argument_value_resolver', ['priority' => 150])]
class BookingListQueryResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() !== ListBookingQuery::class) {
            return [];
        }

        yield new ListBookingQuery(
            campusUid: $request->query->get('campus'),
            date: $request->query->get('date') ? new \DateTimeImmutable($request->query->get('date')) : null,
        );
    }
}
