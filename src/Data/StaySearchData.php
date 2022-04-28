<?php

namespace App\Data;

use DateTime;

class StaySearchData
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var DateTime
     */
    private $date;

    public function __construct()
    {
        $this->text = "";
        $this->date = null;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $startdate): self
    {
        $this->date = $startdate;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $description): self
    {
        $this->text = $description;

        return $this;
    }


}
