<?php
declare(strict_types=1);
namespace App\Http;

/**
 * Class Request
 *
 * Represents an HTTP request.
 *
 * @package App\Http
 */
readonly class Request {
    /**
     * Request constructor.
     *
     * Initializes the request with the given method, URI, query parameters, and post parameters.
     *
     * @param string $method The HTTP method of the request.
     * @param string $uri The URI of the request.
     * @param array $get The query parameters of the request.
     * @param array $post The post parameters of the request.
     */
    public function __construct(
        public string $method,
        public string $uri,
        public array  $get,
        public array  $post,
        public array  $files
    ) {}

}
?>
