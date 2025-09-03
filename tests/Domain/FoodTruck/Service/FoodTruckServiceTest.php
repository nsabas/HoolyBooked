<?php

namespace App\Tests\Domain\FoodTruck\Service;

use App\Application\Booking\Port\Database\BookingDatabasePort;
use App\Domain\FoodTruck\Service\FoodTruckService;
use App\Domain\Model\Campus;
use App\Domain\Model\FoodTruck;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FoodTruckServiceTest extends TestCase
{
    private FoodTruckService $service;
    private BookingDatabasePort|MockObject $bookingDatabasePort;
    private FoodTruck|MockObject $foodTruck;
    private Campus|MockObject $campus;

    protected function setUp(): void
    {
        $this->bookingDatabasePort = $this->createMock(BookingDatabasePort::class);
        $this->foodTruck = $this->createMock(FoodTruck::class);
        $this->campus = $this->createMock(Campus::class);

        $this->service = new FoodTruckService($this->bookingDatabasePort);
    }

    /**
     * @test
     */
    public function it_returns_true_when_food_truck_is_available_for_booking(): void
    {
        // Given
        $startAt = new \DateTimeImmutable('2023-06-15 10:00:00'); // Jeudi
        $endAt = new \DateTimeImmutable('2023-06-15 16:00:00');

        $expectedStartOfWeek = $startAt->modify('monday this week'); // 2023-06-12
        $expectedEndOfWeek = $startAt->modify('sunday this week');   // 2023-06-18

        // Le food truck n'a pas été réservé cette semaine sur ce campus
        $this->bookingDatabasePort
            ->expects($this->once())
            ->method('hasBookingBetweenFor')
            ->with(
                $this->identicalTo($this->foodTruck),
                $this->identicalTo($this->campus),
                $this->callback(function ($date) use ($expectedStartOfWeek) {
                    return $date->format('Y-m-d H:i:s') === $expectedStartOfWeek->format('Y-m-d H:i:s');
                }),
                $this->callback(function ($date) use ($expectedEndOfWeek) {
                    return $date->format('Y-m-d H:i:s') === $expectedEndOfWeek->format('Y-m-d H:i:s');
                })
            )
            ->willReturn(false);

        // Il n'y a pas de réservation qui chevauche
        $this->bookingDatabasePort
            ->expects($this->once())
            ->method('hasOverlappingBooking')
            ->with(
                $this->identicalTo($this->foodTruck),
                $this->identicalTo($startAt),
                $this->identicalTo($endAt)
            )
            ->willReturn(false);

        // When
        $result = $this->service->isAvailableForBooking(
            $this->foodTruck,
            $this->campus,
            $startAt,
            $endAt
        );

        // Then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function it_returns_false_when_food_truck_has_overlapping_booking(): void
    {
        // Given
        $startAt = new \DateTimeImmutable('2023-06-15 10:00:00');
        $endAt = new \DateTimeImmutable('2023-06-15 16:00:00');

        $expectedStartOfWeek = $startAt->modify('monday this week');
        $expectedEndOfWeek = $startAt->modify('sunday this week');

        // Le food truck n'a pas été réservé cette semaine sur ce campus
        $this->bookingDatabasePort
            ->expects($this->once())
            ->method('hasBookingBetweenFor')
            ->with(
                $this->identicalTo($this->foodTruck),
                $this->identicalTo($this->campus),
                $this->callback(function ($date) use ($expectedStartOfWeek) {
                    return $date->format('Y-m-d H:i:s') === $expectedStartOfWeek->format('Y-m-d H:i:s');
                }),
                $this->callback(function ($date) use ($expectedEndOfWeek) {
                    return $date->format('Y-m-d H:i:s') === $expectedEndOfWeek->format('Y-m-d H:i:s');
                })
            )
            ->willReturn(false);

        // Mais il y a une réservation qui chevauche
        $this->bookingDatabasePort
            ->expects($this->once())
            ->method('hasOverlappingBooking')
            ->with(
                $this->identicalTo($this->foodTruck),
                $this->identicalTo($startAt),
                $this->identicalTo($endAt)
            )
            ->willReturn(true);

        // When
        $result = $this->service->isAvailableForBooking(
            $this->foodTruck,
            $this->campus,
            $startAt,
            $endAt
        );

        // Then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_food_truck_already_booked_this_week_on_campus(): void
    {
        // Given
        $startAt = new \DateTimeImmutable('2023-06-15 10:00:00');
        $endAt = new \DateTimeImmutable('2023-06-15 16:00:00');

        $expectedStartOfWeek = $startAt->modify('monday this week');
        $expectedEndOfWeek = $startAt->modify('sunday this week');

        // Le food truck a déjà été réservé cette semaine sur ce campus
        $this->bookingDatabasePort
            ->expects($this->once())
            ->method('hasBookingBetweenFor')
            ->with(
                $this->identicalTo($this->foodTruck),
                $this->identicalTo($this->campus),
                $this->callback(function ($date) use ($expectedStartOfWeek) {
                    return $date->format('Y-m-d H:i:s') === $expectedStartOfWeek->format('Y-m-d H:i:s');
                }),
                $this->callback(function ($date) use ($expectedEndOfWeek) {
                    return $date->format('Y-m-d H:i:s') === $expectedEndOfWeek->format('Y-m-d H:i:s');
                })
            )
            ->willReturn(true);

        // hasOverlappingBooking ne devrait pas être appelé car l'exception est lancée avant
        $this->bookingDatabasePort
            ->expects($this->never())
            ->method('hasOverlappingBooking');

        // Then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Food truck has been already booked this week on this campus');

        // When
        $this->service->isAvailableForBooking(
            $this->foodTruck,
            $this->campus,
            $startAt,
            $endAt
        );
    }

    /**
     * @test
     * @dataProvider weekDatesProvider
     */
    public function it_calculates_week_boundaries_correctly(
        string $inputDate,
        string $expectedMonday,
        string $expectedSunday
    ): void {
        // Given
        $startAt = new \DateTimeImmutable($inputDate . ' 10:00:00');
        $endAt = new \DateTimeImmutable($inputDate . ' 16:00:00');

        $expectedStartOfWeek = new \DateTimeImmutable($expectedMonday . ' 10:00:00');
        $expectedEndOfWeek = new \DateTimeImmutable($expectedSunday . ' 10:00:00');

        $this->bookingDatabasePort
            ->expects($this->once())
            ->method('hasBookingBetweenFor')
            ->with(
                $this->identicalTo($this->foodTruck),
                $this->identicalTo($this->campus),
                $this->callback(function ($date) use ($expectedStartOfWeek) {
                    return $date->format('Y-m-d') === $expectedStartOfWeek->format('Y-m-d');
                }),
                $this->callback(function ($date) use ($expectedEndOfWeek) {
                    return $date->format('Y-m-d') === $expectedEndOfWeek->format('Y-m-d');
                })
            )
            ->willReturn(false);

        $this->bookingDatabasePort
            ->expects($this->once())
            ->method('hasOverlappingBooking')
            ->willReturn(false);

        // When
        $result = $this->service->isAvailableForBooking(
            $this->foodTruck,
            $this->campus,
            $startAt,
            $endAt
        );

        // Then
        $this->assertTrue($result);
    }

    /**
     * Fournit différentes dates avec leurs lundis et dimanches correspondants
     */
    public static function weekDatesProvider(): array
    {
        return [
            'Lundi' => ['2023-06-12', '2023-06-12', '2023-06-18'],
            'Mardi' => ['2023-06-13', '2023-06-12', '2023-06-18'],
            'Mercredi' => ['2023-06-14', '2023-06-12', '2023-06-18'],
            'Jeudi' => ['2023-06-15', '2023-06-12', '2023-06-18'],
            'Vendredi' => ['2023-06-16', '2023-06-12', '2023-06-18'],
            'Samedi' => ['2023-06-17', '2023-06-12', '2023-06-18'],
            'Dimanche' => ['2023-06-18', '2023-06-12', '2023-06-18'],
        ];
    }

    /**
     * @test
     */
    public function it_handles_different_time_zones_correctly(): void
    {
        // Given
        $timezone = new \DateTimeZone('Europe/Paris');
        $startAt = new \DateTimeImmutable('2023-06-15 10:00:00', $timezone);
        $endAt = new \DateTimeImmutable('2023-06-15 16:00:00', $timezone);

        $this->bookingDatabasePort
            ->expects($this->once())
            ->method('hasBookingBetweenFor')
            ->willReturn(false);

        $this->bookingDatabasePort
            ->expects($this->once())
            ->method('hasOverlappingBooking')
            ->willReturn(false);

        // When
        $result = $this->service->isAvailableForBooking(
            $this->foodTruck,
            $this->campus,
            $startAt,
            $endAt
        );

        // Then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function it_works_with_datetime_interface(): void
    {
        // Given - Utilise DateTime au lieu de DateTimeImmutable pour tester l'interface
        $startAt = new \DateTimeImmutable('2023-06-15 10:00:00');
        $endAt = new \DateTimeImmutable('2023-06-15 16:00:00');

        $this->bookingDatabasePort
            ->expects($this->once())
            ->method('hasBookingBetweenFor')
            ->willReturn(false);

        $this->bookingDatabasePort
            ->expects($this->once())
            ->method('hasOverlappingBooking')
            ->willReturn(false);

        // When
        $result = $this->service->isAvailableForBooking(
            $this->foodTruck,
            $this->campus,
            $startAt,
            $endAt
        );

        // Then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function it_handles_edge_case_when_booking_spans_multiple_days(): void
    {
        // Given
        $startAt = new \DateTimeImmutable('2023-06-15 22:00:00'); // Jeudi soir
        $endAt = new \DateTimeImmutable('2023-06-16 02:00:00');   // Vendredi matin

        $expectedStartOfWeek = $startAt->modify('monday this week');
        $expectedEndOfWeek = $startAt->modify('sunday this week');

        $this->bookingDatabasePort
            ->expects($this->once())
            ->method('hasBookingBetweenFor')
            ->with(
                $this->identicalTo($this->foodTruck),
                $this->identicalTo($this->campus),
                $this->callback(function ($date) use ($expectedStartOfWeek) {
                    return $date->format('Y-m-d H:i:s') === $expectedStartOfWeek->format('Y-m-d H:i:s');
                }),
                $this->callback(function ($date) use ($expectedEndOfWeek) {
                    return $date->format('Y-m-d H:i:s') === $expectedEndOfWeek->format('Y-m-d H:i:s');
                })
            )
            ->willReturn(false);

        $this->bookingDatabasePort
            ->expects($this->once())
            ->method('hasOverlappingBooking')
            ->with(
                $this->identicalTo($this->foodTruck),
                $this->identicalTo($startAt),
                $this->identicalTo($endAt)
            )
            ->willReturn(false);

        // When
        $result = $this->service->isAvailableForBooking(
            $this->foodTruck,
            $this->campus,
            $startAt,
            $endAt
        );

        // Then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function it_preserves_original_datetime_objects(): void
    {
        // Given
        $originalStartAt = new \DateTimeImmutable('2023-06-15 10:00:00');
        $originalEndAt = new \DateTimeImmutable('2023-06-15 16:00:00');

        // Créer des copies pour vérifier qu'elles ne sont pas modifiées
        $startAtCopy = clone $originalStartAt;
        $endAtCopy = clone $originalEndAt;

        $this->bookingDatabasePort
            ->expects($this->once())
            ->method('hasBookingBetweenFor')
            ->willReturn(false);

        $this->bookingDatabasePort
            ->expects($this->once())
            ->method('hasOverlappingBooking')
            ->willReturn(false);

        // When
        $this->service->isAvailableForBooking(
            $this->foodTruck,
            $this->campus,
            $originalStartAt,
            $originalEndAt
        );

        // Then - Vérifier que les objets DateTime originaux n'ont pas été modifiés
        $this->assertEquals($startAtCopy, $originalStartAt);
        $this->assertEquals($endAtCopy, $originalEndAt);
    }
}
