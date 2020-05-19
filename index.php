<?php

use Dotenv\Dotenv;
use GuzzleHttp\Client;

require_once('./vendor/autoload.php');

$projectId = 26;
$token = 'XXXXX';
$envScope = 'staging-env';

$gitlabUrl = "https://gitlab.something.io/api/v4/";

$guzzleClient = new Client(['base_uri' => $gitlabUrl]);

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$envVars = $_ENV;

foreach ($envVars as $name => $value) {
    try {
        $a = $guzzleClient->post("projects/{$projectId}/variables", [
            'form_params' => [
                "variable_type" => "env_var",
                "key" => (string) $name,
                "value" => (string) $value,
                "protected" => 1,
                "masked" => 0,
                "environment_scope" => $envScope
            ],
            'headers' => [
                'PRIVATE-TOKEN' => $token
            ]
        ]);
    } catch (\Exception $exception) {
        print_r($exception->getMessage());
        echo "\n";
        continue;
    }
    print_r($a->getBody()->getContents());
    echo "\n";
}
