<?php

namespace Infrastructure\Http\Resolver;

use App\Application\Booking\Query\ListBookingQuery;
use App\Infrastructure\Http\Resolver\BookingListQueryResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class BookingListQueryResolverTest extends TestCase
{
    private BookingListQueryResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = new BookingListQueryResolver();
    }

    public function testResolveReturnsEmptyArrayWhenArgumentIsNotListBookingQuery(): void
    {
        $request = new Request();
        $argument = new ArgumentMetadata('foo', 'string', false, false, null);

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertSame([], $result);
    }

    public function testResolveReturnsListBookingQueryWithCampusAndDate(): void
    {
        $request = new Request(query: [
            'campus' => 'paris-uid',
            'date' => '2023-06-15',
        ]);

        $argument = new ArgumentMetadata('query', ListBookingQuery::class, false, false, null);

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertCount(1, $result);
        $this->assertInstanceOf(ListBookingQuery::class, $result[0]);
        $this->assertSame('paris-uid', $result[0]->campusUid);
        $this->assertEquals(new \DateTimeImmutable('2023-06-15'), $result[0]->date);
    }

    public function testResolveReturnsListBookingQueryWithNullDateWhenDateNotProvided(): void
    {
        $request = new Request(query: [
            'campus' => 'lyon-uid',
        ]);

        $argument = new ArgumentMetadata('query', ListBookingQuery::class, false, false, null);

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertCount(1, $result);
        $this->assertInstanceOf(ListBookingQuery::class, $result[0]);
        $this->assertSame('lyon-uid', $result[0]->campusUid);
        $this->assertNull($result[0]->date);
    }
}
