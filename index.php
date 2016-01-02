<?php
/**************************
	Internal function
	Get base price based on zone (domestic)
	Format is array(First 500g, Next 250g, First 2500g, Next 500g)
	TODO: Local town delivery and between Sabah & Sarawak
**************************/
function getBasePrice($z) {
	if ($z == 2) return array(4.5, 1, 16, 2); // Within Semenanjung/Sabah/Sarawak 
	if ($z == 4) return array(6.5, 1.5, 26, 3.5); // Betweeen Semenanjung and Sarawak
	if ($z == 5) return array(7, 2, 31, 4); // Between Semenanjung and Sabah
	return null;
}

/**************************
	Internal function
	Get postage price using weight and zone 
**************************/
function calcActualPrice($w, $z) {
	$isFirstBlock = ($w <= 2);
	$base = getBasePrice($z);
	if ($base == null) return null;
	if ($isFirstBlock) {
		$multiplier = $w / 0.25;
		if ($multiplier <= 2) return $base[0];
		return $base[0] + ($base[1] * (ceil($multiplier)-2));
	} else {
		$multiplier = $w / 0.5;
		if ($multiplier <= 5) return $base[2];
		return $base[2] + ($base[3] * (ceil($multiplier)-5));
	}
}

/**************************
	Public function
	Get postage price using weight and zone 
**************************/
function getTotalPostage($w, $z) {
	$price = calcActualPrice($w, $z);
	if ($price == null) return null;
	$price = $price*1.25; // Handling fees & fuel surcharge
	$price = $price*1.06; // GST
	return round($price,2);
}

// Example
echo getTotalPostage(1.24,5);

?>
