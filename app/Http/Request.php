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
class Request {
    /**
     * @var string The HTTP method of the request (e.g., GET, POST).
     */
    private $method;

    /**
     * @var string The URI of the request.
     */
    private $uri;

    /**
     * @var array The query parameters of the request.
     */
    private $get;

    /**
     * @var array The post parameters of the request.
     */
    private $post;

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
    public function __construct(string $method, string $uri, array $get, array $post) {
        $this->method = $method;
        $this->uri = $uri;
        $this->get = $get;
        $this->post = $post;
    }

    /**
     * Get the HTTP method of the request.
     *
     * @return string The HTTP method.
     */
    public function getMethod(): string {
        return $this->method;
    }

    /**
     * Get the URI of the request.
     *
     * @return string The URI.
     */
    public function getUri(): string {
        return $this->uri;
    }

    /**
     * Get the query parameters of the request.
     *
     * @return array The query parameters.
     */
    public function getQueryParams(): array {
        return $this->get;
    }

    /**
     * Get the post parameters of the request.
     *
     * @return array The post parameters.
     */
    public function getPostParams(): array {
        return $this->post;
    }
}
?>
