<?php
declare(strict_types=1);
namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use PDO;

class ErrorController implements ControllerInterface {
    private string $errorMessage;
    private int $statusCode;

    public function __construct(string $errorMessage = "An error occurred", int $statusCode = 500) {
        $this->errorMessage = $errorMessage;
        $this->statusCode = $statusCode;
    }

    public function __invoke(Request $req, PDO $db): Response {
        // エラーページの生成ロジック
        $body = "<html><head><title>Error</title></head><body><h1>Error: {$this->statusCode}</h1><p>{$this->errorMessage}</p></body></html>";

        return new Response(
            $this->statusCode,
            $body,
            ['Content-Type: text/html']
        );
    }
}
