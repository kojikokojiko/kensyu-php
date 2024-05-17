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
class Response {
    /**
     * @var int The HTTP status code of the response.
     */
    private $statusCode;

    /**
     * @var string The body of the response.
     */
    private $body;

    /**
     * @var array The headers of the response.
     */
    private $headers;

    /**
     * Response constructor.
     *
     * Initializes the response with the given status code, body, and headers.
     *
     * @param int $statusCode The HTTP status code of the response.
     * @param string $body The body of the response.
     * @param array $headers The headers of the response.
     */
    public function __construct(int $statusCode, string $body, array $headers = []) {
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->headers = $headers;
    }

    /**
     * Get the HTTP status code of the response.
     *
     * @return int The HTTP status code.
     */
    public function getStatusCode(): int {
        return $this->statusCode;
    }

    /**
     * Get the body of the response.
     *
     * @return string The body.
     */
    public function getBody(): string {
        return $this->body;
    }

    /**
     * Get the headers of the response.
     *
     * @return array The headers.
     */
    public function getHeaders(): array {
        return $this->headers;
    }

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
