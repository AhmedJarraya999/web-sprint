<?php

//recherche des stays sna3t kil etity sghira fiha les attribules eli bech ncherchihom
//mazel nasnaa functionstaysearch fel stayrepository
namespace App\Data;

use DateTime;

class SearchData2
{
    /**
     * @var string
     */
    private $i;

    /**
     * @var DateTime
     */
    private $j;

    public function __construct()
    {
        $this->i = "";
        $this->j = null;
    }

    public function getJ(): ?\DateTimeInterface
    {
        return $this->j;
    }

    public function setJ(?\DateTimeInterface $startdate): self
    {
        $this->j = $startdate;

        return $this;
    }

    public function getI(): ?string
    {
        return $this->description;
    }

    public function setI(string $description): self
    {
        $this->description = $description;

        return $this;
    }

}
