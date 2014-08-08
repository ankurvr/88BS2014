<?php

class Resource_Tool_CoordDistance {
	
	
	public static $RadiusKM = 6371.009;
	public static $RadiusMiles = 3958.761;
	
	
	/**
	 * @static
	 * @param $Point1, $Point2, $Unit
	 * @return double
	 */
	public static function DistAB($Point1,$Point2, $Unit='km') {

		switch (strtolower($Unit)) {
			case 'km'	:
				$EarthMeanRadius = self::$RadiusKM; // km
				break;
			case 'm'	:
				$EarthMeanRadius = self::$RadiusKM * 1000; // m
				break;
			case 'miles'	:
				$EarthMeanRadius = self::$RadiusMiles; // miles
				break;
			case 'yards'	:
			case 'yds'	:
				$EarthMeanRadius = self::$RadiusMiles * 1760; // miles
				break;
			case 'feet'	:
			case 'ft'	:
				$EarthMeanRadius = self::$RadiusMiles * 1760 * 3; // miles
				break;
			case 'nm'	:
				$EarthMeanRadius = 3440.069; // miles
				break;
		}
				
		$deltaLatitude = deg2rad($Point2['latitude'] - $Point1['latitude']);
		$deltaLongitude = deg2rad($Point2['longitude'] - $Point1['longitude']);
		
		$a = sin($deltaLatitude / 2) * sin($deltaLatitude / 2) 
				+ cos(deg2rad($Point1['latitude'])) * cos(deg2rad($Point2['latitude'])) 
				* sin($deltaLongitude / 2) * sin($deltaLongitude / 2);
		
		$c = 2 * atan2(sqrt($a), sqrt(1-$a));
		$distance = $EarthMeanRadius * $c;
		
		return $distance;		
	}
}