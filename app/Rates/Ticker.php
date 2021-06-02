<?php


namespace App\Rates;


class Ticker extends Base
{

    protected $_method = 'ticker';

    public function getAll() {
        return $this->get();
    }

}