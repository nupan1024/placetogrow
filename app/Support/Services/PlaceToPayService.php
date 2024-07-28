<?php

namespace App\Support\Services;

use Illuminate\Support\Carbon;

class PlaceToPayService
{
    private function auth(): array
    {
        $seed = Carbon::now()->toIso8601String();
        $nonce = (string) time();

        $tranKey = base64_encode(hash(
            'sha256',
            $nonce.$seed.config('placetoplay.secret_key'),
            true
        ));

        return [
          'login' => config('placetoplay.login'),
          'tranKey' => $tranKey,
          'seed' => $seed,
          'nonce' => $nonce,
        ];
    }

}
