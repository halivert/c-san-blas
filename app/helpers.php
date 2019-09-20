<?php

if (!function_exists('sort_str')) {
    function sort_str($str)
    {
        $stringParts = str_split($str);
        sort($stringParts);
        return implode($stringParts);
    }
}

if (!function_exists('compare_permissions')) {
    function compare_permissions($permission, $requested)
    {
        if (!$requested) return false;

        $requestedParts = str_split($requested);
        $valid = true;
        foreach ($requestedParts as $char)
            $valid = ($valid and (strpos($permission, $char) !== false));
        return $valid;
    }
}

if (!function_exists('tz_offset_to_name')) {
    function tz_offset_to_name($offset)
    {
        $offset *= 3600;
        $abbrarray = timezone_abbreviations_list();
        foreach ($abbrarray as $abbr) {
            foreach ($abbr as $city) {
                if ($city['offset'] == $offset) {
                    return $city['timezone_id'];
                }
            }
        }

        return null;
    }
}

if (!function_exists('boolean_value')) {
	function boolean_value($var): bool {
		if ($var === true) return true;
		if ($var === 1) return true;
		if (is_string($var)) {
			if ("true" === strtolower($var)) return true;
			if (1 === intval($var)) return true;
		}

		return false;
	}
}
