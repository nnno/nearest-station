<?php

if (!function_exists('api_ok')) {
    function api_ok($response = []) {
        if (empty($response)) {
            return response(null, 204);
        } else {
            return response()->json($response, 200);
        }
    }
}


