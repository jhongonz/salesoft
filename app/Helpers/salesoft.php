<?php

if (!function_exists('toDecimal'))
{
	function toDecimal($value,$decimal = 2)
	{
		$value = floatval($value);
		$number = number_format($value,$decimal,'.','');

		return $number;
	}
}