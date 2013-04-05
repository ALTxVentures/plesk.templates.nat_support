<?php
if (!function_exists('nat_resolve'))
{
	function nat_resolve($ipAddress)
	{
		
		require_once('nat_translation_db.php');

		/*

		$ipAddress->escapedPublicAddress = $ipAddress->escapedAddress;

		if (isset($nat_translation[$ipAddress->escapedAddress]))
			$ipAddress->escapedAddress = $nat_translation[$ipAddress->escapedAddress];

		$ipAddress->escapedPrivateAddress = $ipAddress->escapedAddress;
		*/

		
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