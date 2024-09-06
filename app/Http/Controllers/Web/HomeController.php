<?php

namespace App\Http\Controllers\Web;

use App\Domain\Microsites\Models\Microsite;
use App\Domain\Microsites\ViewModels\FormMicrosite;
use App\Http\Controllers\Controller;
use App\Support\ViewModels\HomeViewModel;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Welcome', new HomeViewModel());
    }

    public function formMicrosite(
        Microsite $microsite
    ): Response|RedirectResponse {
        $template = config('microsites.forms')[$microsite->microsites_type_id];
        return Inertia::render($template, new FormMicrosite($microsite));
    }

    public function auth(): JsonResponse
    {
        $login = "ufSm2zJPLw6KIewiXD6Sa6nJ2NI44WGl";
        $secretKey = "Z1f3jaeqmcFGXKwi";
        $seed = date('c');
        $nonce = (string) rand();

        $tranKey = base64_encode(hash(
            'sha256',
            $login.$nonce.$seed.$secretKey,
            true
        ));
        $body = [
            "auth" => [
                "login" => $login,
                "tranKey" => $tranKey,
                "nonce" => $nonce,
                "seed" => $seed,
            ],
        ];

        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])
            ->post(
                'https://challenge-bootcamp-evertec.up.railway.app/api/login',
                $body
            );
        $token = $response->json()['data']['token'];

        $question = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer $token",
        ])
            ->post('https://challenge-bootcamp-evertec.up.railway.app/api/question');
        $answer = $question->json();

        $key = 'base64:45n+LJrFVW9CqCLu6DvWEOKCrJxtJxLj/Q8AUWcGBds=';
        $encrypt = new Encrypter(base64_decode(substr($key, 7)), 'AES-256-CBC');
        $answer = json_decode($encrypt->decrypt($answer), true);

        $message = 'password_hash';

        $url = str_replace('http://', 'https://', $answer['data']['next']);
        $responseAnswer = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer $token",
        ])->post($url, ['answer' => $encrypt->encrypt($message)]);
        $answer1 = json_decode(
            $encrypt->decrypt($responseAnswer->json()),
            true
        );

        $row = 0;
        $filePath = base_path('city-information-6000.csv');
        $badData = [];
        if (file_exists($filePath)) {
            if (($handle = fopen($filePath, "r")) !== false) {
                while (($data = fgetcsv($handle, 10000, ",")) !== false) {
                    $row++;
                    $errors = "";
                    if ($row > 1) {
                        $value = trim($data[4]);
                        $type = trim($data[3]);
                        switch ($type) {
                            case 'CC':
                                if (!preg_match(
                                    "/^[1-9][0-9]{4,9}$/",
                                    $value
                                )) {
                                    $errors .= "-Invalid CC format";
                                }

                                break;
                            case 'CE':
                                if (!preg_match(
                                    "/^([a-zA-Z]{1,5})?[1-9][0-9]{3,7}$/",
                                    $value
                                )) {
                                    $errors .= "-Invalid CE format";
                                }

                                break;
                            case 'NIT':
                                if (!preg_match(
                                    "/^[1-9][0-9]{6,10}(\-[0-9])?$/",
                                    $value
                                )) {
                                    $errors .= "-Invalid NIT format";
                                }
                                break;
                            case 'RUT':
                                if (!preg_match(
                                    "/^[1-9][0-9]{4,9}(\-[0-9])?$/",
                                    $value
                                )) {
                                    $errors .= "-Invalid RUT format";
                                }
                                break;
                            case 'PPN':
                                if (!preg_match(
                                    "/^[a-zA-Z0-9]{4,12}$/",
                                    $value
                                )) {
                                    $errors .= "-Invalid PPN format";
                                }
                                break;
                        }

                        if (!filter_var(
                            trim($data[5]),
                            FILTER_VALIDATE_EMAIL
                        )) {
                            $errors .= "-Invalid email format";
                        }

                        if (!is_numeric($data[9]) || strlen(trim($data[9])) !== 10) {
                            $errors .= "-Length of data[9] should be 10";
                        }

                        if ($errors !== "") {
                            $data['errors'] = $errors;
                            $badData[$row] = $data;
                        }
                    }
                }
                fclose($handle);
            }
        }

        $keys = array_keys($badData);
        sort($keys, SORT_NUMERIC);
        $output = implode('-', array_unique($keys));

        $url1 = str_replace('http://', 'https://', $answer1['data']['next']);

        $responseAnswerOne = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer $token",
        ])->post($url1, ['answer' => $encrypt->encrypt($output)]);
        $answer2 = json_decode(
            $encrypt->decrypt($responseAnswerOne->json()),
            true
        );

        return response()->json($answer2);
    }

    public function compareByLine($a, $b)
    {
        return $a['line'] <=> $b['line'];
    }

}
