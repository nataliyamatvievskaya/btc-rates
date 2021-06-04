<?php


namespace App;


use App\Rates\Enum\Fields;

/**
 * Comission logic
 * Class Comission
 * @package App
 */
class Comission
{
	/**
	 * @param $rate
	 */
    static public function add($rate) {
        return round($rate + $rate * APP_COMISSION, 2);
    }

}