<?php
declare(strict_types=1);
namespace App\Http;

/**
 * Class Response
 *
 * Represents an HTTP response.
 *
 * @package App\Http
 */
readonly class Response {
    /**
     * Response constructor.
     *
     * Initializes the response with the given status code, body, and headers.
     *
     * @param int $statusCode The HTTP status code of the response.
     * @param string $body The body of the response.
     * @param array $headers The headers of the response.
     */
    public function __construct(
        public int    $statusCode,
        public string $body,
        public array  $headers = []
    ) {}

    /**
     * Send the response.
     *
     * This method sets the HTTP status code, sends the headers, and echoes the body.
     *
     * @return void
     */
    public function send(): void {
        http_response_code($this->statusCode);
        foreach ($this->headers as $header) {
            header($header);
        }
        echo $this->body;
    }
}
?>
