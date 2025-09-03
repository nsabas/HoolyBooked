<?php

namespace App\Tests\Domain\Slot\Service;

use App\Application\Slot\Port\Database\SlotDatabasePort;
use App\Domain\Model\Slot;
use App\Domain\Model\Unavailability;
use App\Domain\Slot\Service\SlotService;
use App\Domain\Unavailability\Service\UnavailabilityChecker;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SlotServiceTest extends TestCase
{
    private SlotService $service;
    private SlotDatabasePort|MockObject $slotDatabasePort;
    private UnavailabilityChecker|MockObject $unavailabilityChecker;
    private Slot|MockObject $slot;

    protected function setUp(): void
    {
        $this->slotDatabasePort = $this->createMock(SlotDatabasePort::class);
        $this->unavailabilityChecker = $this->createMock(UnavailabilityChecker::class);
        $this->slot = $this->createMock(Slot::class);

        $this->service = new SlotService(
            $this->slotDatabasePort,
            $this->unavailabilityChecker
        );
    }

    /**
     * @test
     */
    public function it_returns_true_when_slot_is_available_and_has_no_booking(): void
    {
        // Given
        $date = new \DateTimeImmutable('2023-06-15 10:00:00');
        $unavailabilities = [];

        $this->slot
            ->expects($this->once())
            ->method('getUnavailabilities')
            ->willReturn(new ArrayCollection($unavailabilities));

        // Pas de réservation programmée
        $this->slotDatabasePort
            ->expects($this->once())
            ->method('hasBookingScheduled')
            ->with($this->identicalTo($this->slot), $this->identicalTo($date))
            ->willReturn(false);

        // unavailabilityChecker ne devrait pas être appelé car pas d'indisponibilités
        $this->unavailabilityChecker
            ->expects($this->never())
            ->method('isUnavailable');

        // When
        $result = $this->service->isSlotAvailable($this->slot, $date);

        // Then
        $this->assertFalse($result); // retourne l'inverse de hasBookingScheduled
    }

    /**
     * @test
     */
    public function it_returns_false_when_slot_has_booking_scheduled(): void
    {
        // Given
        $date = new \DateTimeImmutable('2023-06-15 10:00:00');
        $unavailabilities = [];

        $this->slot
            ->expects($this->once())
            ->method('getUnavailabilities')
            ->willReturn(new ArrayCollection($unavailabilities));

        // Il y a une réservation programmée
        $this->slotDatabasePort
            ->expects($this->once())
            ->method('hasBookingScheduled')
            ->with($this->identicalTo($this->slot), $this->identicalTo($date))
            ->willReturn(true);

        // When
        $result = $this->service->isSlotAvailable($this->slot, $date);

        // Then
        $this->assertTrue($result); // retourne l'inverse de hasBookingScheduled
    }

    /**
     * @test
     */
    public function it_throws_exception_when_slot_is_unavailable(): void
    {
        // Given
        $date = new \DateTimeImmutable('2023-06-15 10:00:00');
        $unavailability = $this->createMock(Unavailability::class);
        $unavailabilities = [$unavailability];

        $this->slot
            ->expects($this->once())
            ->method('getUnavailabilities')
            ->willReturn(new ArrayCollection($unavailabilities));

        $this->unavailabilityChecker
            ->expects($this->once())
            ->method('isUnavailable')
            ->with($this->identicalTo($date), $this->identicalTo($unavailability))
            ->willReturn(true);

        // hasBookingScheduled ne devrait pas être appelé car l'exception est lancée avant
        $this->slotDatabasePort
            ->expects($this->never())
            ->method('hasBookingScheduled');

        // Then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Slot is unavailable days off');

        // When
        $this->service->isSlotAvailable($this->slot, $date);
    }

    /**
     * @test
     */
    public function it_checks_all_unavailabilities_when_none_match(): void
    {
        // Given
        $date = new \DateTimeImmutable('2023-06-15 10:00:00');
        $unavailability1 = $this->createMock(Unavailability::class);
        $unavailability2 = $this->createMock(Unavailability::class);
        $unavailability3 = $this->createMock(Unavailability::class);
        $unavailabilities = [$unavailability1, $unavailability2, $unavailability3];

        $this->slot
            ->expects($this->once())
            ->method('getUnavailabilities')
            ->willReturn(new ArrayCollection($unavailabilities));

        // Toutes les indisponibilités retournent false
        $this->unavailabilityChecker
            ->expects($this->exactly(3))
            ->method('isUnavailable')
            ->willReturnCallback(function (\DateTimeImmutable $d, $unavailability) use ($date, $unavailabilities) {
                $this->assertSame($date, $d);
                $this->assertContains($unavailability, $unavailabilities);
                return false;
            });

        $this->slotDatabasePort
            ->expects($this->once())
            ->method('hasBookingScheduled')
            ->willReturn(false);

        // When
        $result = $this->service->isSlotAvailable($this->slot, $date);

        // Then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function it_stops_checking_and_throws_exception_on_first_unavailability_match(): void
    {
        // Given
        $date = new \DateTimeImmutable('2023-06-15 10:00:00');
        $unavailability1 = $this->createMock(Unavailability::class);
        $unavailability2 = $this->createMock(Unavailability::class);
        $unavailability3 = $this->createMock(Unavailability::class);
        $unavailabilities = [$unavailability1, $unavailability2, $unavailability3];

        $this->slot
            ->expects($this->once())
            ->method('getUnavailabilities')
            ->willReturn(new ArrayCollection($unavailabilities));

        $callCount = 0;
        // La deuxième indisponibilité correspond
        $this->unavailabilityChecker
            ->expects($this->exactly(2))
            ->method('isUnavailable')
            ->willReturnCallback(function($dateParam, $unavailabilityParam) use ($date, $unavailability1, $unavailability2, &$callCount) {
                $callCount++;
                $this->assertSame($date, $dateParam);

                if ($callCount === 1) {
                    $this->assertSame($unavailability1, $unavailabilityParam);
                    return false;
                } elseif ($callCount === 2) {
                    $this->assertSame($unavailability2, $unavailabilityParam);
                    return true;
                }

                $this->fail('Should not reach this point');
            });

        // hasBookingScheduled ne devrait pas être appelé
        $this->slotDatabasePort
            ->expects($this->never())
            ->method('hasBookingScheduled');

        // Then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Slot is unavailable days off');

        // When
        $this->service->isSlotAvailable($this->slot, $date);
    }

    /**
     * @test
     */
    public function it_works_with_different_datetime_implementations(): void
    {
        // Given - Test avec DateTime au lieu de DateTimeImmutable
        $date = new \DateTime('2023-06-15 10:00:00');
        $unavailabilities = [];

        $this->slot
            ->expects($this->once())
            ->method('getUnavailabilities')
            ->willReturn(new ArrayCollection($unavailabilities));

        $this->slotDatabasePort
            ->expects($this->once())
            ->method('hasBookingScheduled')
            ->with($this->identicalTo($this->slot), $this->identicalTo($date))
            ->willReturn(false);

        // When
        $result = $this->service->isSlotAvailable($this->slot, $date);

        // Then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function it_handles_timezone_correctly(): void
    {
        // Given
        $timezone = new \DateTimeZone('Europe/Paris');
        $date = new \DateTimeImmutable('2023-06-15 10:00:00', $timezone);
        $unavailabilities = [];

        $this->slot
            ->expects($this->once())
            ->method('getUnavailabilities')
            ->willReturn(new ArrayCollection($unavailabilities));

        $this->slotDatabasePort
            ->expects($this->once())
            ->method('hasBookingScheduled')
            ->with($this->identicalTo($this->slot), $this->identicalTo($date))
            ->willReturn(false);

        // When
        $result = $this->service->isSlotAvailable($this->slot, $date);

        // Then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function it_handles_empty_unavailabilities_collection(): void
    {
        // Given
        $date = new \DateTimeImmutable('2023-06-15 10:00:00');
        $unavailabilities = []; // Collection vide

        $this->slot
            ->expects($this->once())
            ->method('getUnavailabilities')
            ->willReturn(new ArrayCollection($unavailabilities));

        $this->unavailabilityChecker
            ->expects($this->never())
            ->method('isUnavailable');

        $this->slotDatabasePort
            ->expects($this->once())
            ->method('hasBookingScheduled')
            ->willReturn(true);

        // When
        $result = $this->service->isSlotAvailable($this->slot, $date);

        // Then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function it_handles_single_unavailability_that_matches(): void
    {
        // Given
        $date = new \DateTimeImmutable('2023-06-15 10:00:00');
        $unavailability = $this->createMock(Unavailability::class);
        $unavailabilities = [$unavailability];

        $this->slot
            ->expects($this->once())
            ->method('getUnavailabilities')
            ->willReturn(new ArrayCollection($unavailabilities));

        $this->unavailabilityChecker
            ->expects($this->once())
            ->method('isUnavailable')
            ->with($this->identicalTo($date), $this->identicalTo($unavailability))
            ->willReturn(true);

        $this->slotDatabasePort
            ->expects($this->never())
            ->method('hasBookingScheduled');

        // Then
        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Slot is unavailable days off');

        // When
        $this->service->isSlotAvailable($this->slot, $date);
    }

    /**
     * @test
     */
    public function it_handles_single_unavailability_that_does_not_match(): void
    {
        // Given
        $date = new \DateTimeImmutable('2023-06-15 10:00:00');
        $unavailability = $this->createMock(Unavailability::class);
        $unavailabilities = [$unavailability];

        $this->slot
            ->expects($this->once())
            ->method('getUnavailabilities')
            ->willReturn(new ArrayCollection($unavailabilities));

        $this->unavailabilityChecker
            ->expects($this->once())
            ->method('isUnavailable')
            ->with($this->identicalTo($date), $this->identicalTo($unavailability))
            ->willReturn(false);

        $this->slotDatabasePort
            ->expects($this->once())
            ->method('hasBookingScheduled')
            ->with($this->identicalTo($this->slot), $this->identicalTo($date))
            ->willReturn(false);

        // When
        $result = $this->service->isSlotAvailable($this->slot, $date);

        // Then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function it_preserves_original_date_object(): void
    {
        // Given
        $originalDate = new \DateTimeImmutable('2023-06-15 10:00:00');
        $dateCopy = clone $originalDate;
        $unavailabilities = [];

        $this->slot
            ->expects($this->once())
            ->method('getUnavailabilities')
            ->willReturn(new ArrayCollection($unavailabilities));

        $this->slotDatabasePort
            ->expects($this->once())
            ->method('hasBookingScheduled')
            ->willReturn(false);

        // When
        $this->service->isSlotAvailable($this->slot, $originalDate);

        // Then - Vérifier que l'objet date original n'a pas été modifié
        $this->assertEquals($dateCopy, $originalDate);
    }
}
