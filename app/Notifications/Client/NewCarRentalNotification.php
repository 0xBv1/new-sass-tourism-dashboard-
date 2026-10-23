<?php

namespace App\Notifications\Client;

use App\Models\CarRental;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCarRentalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private CarRental $carRental;

    public function __construct(CarRental $carRental)
    {
        $this->carRental = $carRental;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $totalPrice = $this->carRental->currency_exchange_rate * ($this->carRental->car_route_price + $this->carRental->stops->sum('price'));
        $lines = [];
        $lines[] = "You've booked Car rental";
        $lines[] = 'Date: ' . $this->carRental->pickup_date->toDateString() . ' ' . $this->carRental->pickup_time->format('H:i');
        $lines[] = 'Pickup: ' . $this->carRental->pickup->name;
        $lines[] = 'Destinations: ' . $this->carRental->destination->name;
        if ($this->carRental->stops->isNotEmpty()) {
            $lines[] = 'Stops: ' . $this->carRental->stops->implode('location.name', ', ');
        }
        $lines[] = 'Car Type: ' . $this->carRental->car_type;
        $lines[] = 'Members: Adults x (' . $this->carRental->adults . '), Children x (' . $this->carRental->children . ')';
        $lines[] = 'Rent Type: ' . ($this->carRental->oneway ? 'Oneway' : 'Rounded');
        $lines[] = 'Total Price: ' . $this->carRental->currency->symbol . $totalPrice;

        return (new MailMessage)
            ->subject('Car Rental Receipt')
            ->greeting("Dear " . $this->carRental->name)
            ->lines($lines)
            ->line('Wait for our contact.')
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
