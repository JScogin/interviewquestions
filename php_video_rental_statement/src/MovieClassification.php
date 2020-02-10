<?php

class MovieClassification
{
    private static $classifications = [
    	'new_relase' => [
    		'rentalPrice' => 0,
    		'daysIncluded' => 0,
    		'costPerDay' => 3,
    		'bonusPoints' => 1
    	],
		'childrens' => [
    		'rentalPrice' => 1.5,
    		'daysIncluded' => 3,
    		'costPerDay' => 1.5,
    		'bonusPoints' => 0
    	],
		'regular' => [
    		'rentalPrice' => 2,
    		'daysIncluded' => 2,
    		'costPerDay' => 1.5,
    		'bonusPoints' => 0
    	],
    ];

    private $type;
    private $rentalPrice;
    private $daysIncluded;
    private $costPerDay;
    private $bonusPoints;

    public function __construct( $type )
    {
    	$this->type = $type;
    	$this->rentalPrice = self::$classifications[$type]['rentalPrice'];
    	$this->daysIncluded = self::$classifications[$type]['daysIncluded'];
    	$this->costPerDay = self::$classifications[$type]['costPerDay'];
    	$this->bonusPoints = self::$classifications[$type]['bonusPoints'];
    }

	public function getRentalPrice(): float
	{
		return $this->rentalPrice;
	}
	public function getDaysIncluded(): int
	{
		return $this->daysIncluded;
	}
	public function getCostPerDay(): float
	{
		return $this->costPerDay;
	}
	public function getBonusPoints(): int
	{
		return $this->bonusPoints;
	}
}