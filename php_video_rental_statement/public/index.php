<?php

$codedir = '../src';

require_once("$codedir/Movie.php");
require_once("$codedir/Rental.php");
require_once("$codedir/Customer.php");

$prognosisNegative = new Movie("Prognosis Negative", 'new_relase');
$sackLunch = new Movie("Sack Lunch", 'childrens');
$painAndYearning = new Movie("The Pain and the Yearning", 'regular');

$customer = new Customer("Susan Ross");

$customer->addRental(
  new Rental($prognosisNegative, 3)
);
$customer->addRental(
  new Rental($painAndYearning, 1)
);
$customer->addRental(
  new Rental($sackLunch, 1)
);

if( isset( $argv[1] ) && $argv[1] == 'html')
{
	echo $customer->getHtmlStatement();
}
else {
	$statement = $customer->getPaperStatement();

	echo '<pre>';
	echo $statement;
	echo '</pre>';
}