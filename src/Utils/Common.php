<?php

namespace App\Utils;

class Common
{
	public static function dump(...$args)
	{
		echo '<pre>';
		var_dump($args);
		echo '</pre>';
	}
}
