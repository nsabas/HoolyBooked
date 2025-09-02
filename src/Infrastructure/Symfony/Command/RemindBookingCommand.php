<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Command;

use App\Application\Booking\Port\Database\BookingDatabasePort;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Scheduler\Attribute\AsCronTask;

#[AsCronTask('0 18 * * *')]
#[AsCommand(name: 'app:remind_booking', description: 'Remind booking that start tomorrow')]
class RemindBookingCommand extends Command
{
    public function __construct(
        private BookingDatabasePort $bookingDatabasePort,
        private MailerInterface $mailer,
        private string $hoolyContactMail
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bookings = $this->bookingDatabasePort->getBookingStartAt(new \DateTimeImmutable('tomorrow'));

        foreach ($bookings as $booking) {
            $email = (new Email())
                ->to($booking->getEmail())
                ->from($this->hoolyContactMail)
                ->subject('Rappel réservation')
                ->text(
                    sprintf(
                        "Votre réservation du %s pour l'emplacement %s au %s, c'est demain !",
                        $booking->getStartAt()->format('d/m/Y'),
                        $booking->getSlot()->getName(),
                        $booking->getSlot()->getFullAddress()
                    )
                );

            $this->mailer->send($email);
        }

        return Command::SUCCESS;
    }
}
