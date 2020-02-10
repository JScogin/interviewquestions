<?php

class Customer
{
    private $name;
    private $rentals = array();

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addRental(Rental $rental)
    {
        array_push($this->rentals, $rental);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatementData(): array
    {
        $totalAmount = 0;
        $frequentRenterPoints = 0;
        $movies = [];

        // determine amounts for each line
        foreach ($this->rentals as $rental) {
            $totalAmount += $rental->getRentalCost();
            $frequentRenterPoints += $rental->getRentalPoints();
            $movies[$rental->getMovie()->getTitle()] =  $rental->getRentalCost();
        }

        return [
            'totalAmount' => $totalAmount,
            'frequentRenterPoints' => $frequentRenterPoints,
            'movies' => $movies
        ];
    }

    public function getHtmlStatement(): string
    {
        $data = $this->getStatementData();
        $result = "<h1>Rental Record for <i>" . $this->getName() . "</i></h1>";

        // determine amounts for each line
        foreach ($data['movies'] as $name => $cost ) {
            $result .= "{$name}: {$cost}<br/>";
        }

        // add footer lines
        $result .= "<p>Amount owed is " . $data['totalAmount'] . "<p>";
        $result .= "<p>You earned " . $data['frequentRenterPoints'] .
                " frequent renter points</p>";

        return $result;
    }

    public function getPaperStatement(): string
    {
        $data = $this->getStatementData();
        $result = "Rental Record for " . $this->getName() . "\n";

        // determine amounts for each line
        foreach ($data['movies'] as $name => $cost ) {
            $result .= "\t{$name}\t{$cost}\n";
        }

        // add footer lines
        $result .= "Amount owed is " . $data['totalAmount'] . "\n";
        $result .= "You earned " . $data['frequentRenterPoints'] .
                " frequent renter points";

        return $result;
    }
}
