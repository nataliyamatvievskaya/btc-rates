<?php


namespace App\Cache;


use App\Rates\Enum\Fields;

class Comission
{
    static public function add(&$rates = []) {

        $rates = array_map(
			function ($rate) {
				$res =  $rate;
				foreach (Fields::$forComissionAdd as $field) {
					$res[$field] = round($res[$field] + $res[$field] * APP_COMISSION, 2);
				}
				return  $res;
			},
            $rates
        );
    }

}