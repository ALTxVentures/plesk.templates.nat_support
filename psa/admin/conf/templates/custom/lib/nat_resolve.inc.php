<?php
if (!function_exists('nat_resolve'))
{
	function nat_resolve($ipAddress)
	{
		
		$nat_translation = include('nat_translation_db.php');

		if (array_key_exists($ipAddress, $nat_translation))
		{
			$ipAddress = $nat_translation[$ipAddress];
		}
		else if (in_array($ipAddress, $nat_translation))
		{
			$ipAddress = null;
		}
		else
		{
			// ip not in the nat_translation array, let it be...
		}

		return $ipAddress;

	}
}

?>