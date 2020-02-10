<?php

class Rental
{
    private $movie;
    private $daysRented;

    public function __construct(Movie $movie, int $daysRented)
    {
        $this->movie = $movie;
        $this->daysRented = $daysRented;
    }

    public function getDaysRented(): int
    {
        return $this->daysRented;
    }

    public function getMovie(): Movie
    {
        return $this->movie;
    }

    public function getRentalCost(): float
    {
        $classification = $this->movie->getClassification();

        $rentalCost = $classification->getRentalPrice();

        if( $this->daysRented > $classification->getDaysIncluded() )
            $rentalCost += ($this->daysRented - $classification->getDaysIncluded()) * $classification->getCostPerDay();


        return $rentalCost;
    }

    public function getRentalPoints(): int
    {
        $rentalPoints = 1;
        
        if( $this->daysRented > 1 ) {
            $rentalPoints += $this->movie->getClassification()->getBonusPoints();
        }

        return $rentalPoints;
    }
}
