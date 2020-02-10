<?php

require_once("MovieClassification.php");

class Movie
{
    private $title;
    private $classification;

    public function __construct(string $title, string $classification)
    {
        $this->title = $title;
        $this->classification = new MovieClassification( $classification );
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getClassification(): MovieClassification
    {
        return $this->classification;
    }

    public function setClassification(MovieClassification $classification)
    {
        return $this->classification = $classification;
    }
}
