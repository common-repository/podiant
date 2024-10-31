<?php

/**
 * The Podiant API wrapper.
 *
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 */

/**
 * The Podiant API wrapper.
 *
 * This class defines methods for interacting with the Podiant API, and is
 * a thin wrapper around WordPress' `wp_remote_get` function.
 *
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_API {
    /**
    * The API domain.
    *
    * @since 1.0
    * @access private
    * @var string $domain The API domain.
    */
    private $domain;

    /**
    * The API key used to retrieve information on a podcast.
    *
    * @since 1.0
    * @access private
    * @var string $pull_key The API key used to retrieve information on a podcast.
    */
    private $pull_key;

    /**
     * Instantiates the class with the API key.
     *
     * @since 1.0
     * @param string $pull_key The API key used to retrieve information on a podcast.
     */
    public function __construct($pull_key) {
        $this->pull_key = $pull_key;
        $this->domain = 'api.podiant.co';
    }

    /**
     * Performs an HTTP request.
     *
     * @since 1.0
     */
    private function perform($method, $path, $params=array()) {
        if (substr($path, 0, 1) !== '/') {
            $path = "/$path";
        }

        if (substr($path, strlen($path) - 1) !== '/') {
            if (strlen($path) > 1) {
                $path .= '/';
            }
        }

        $url = "https://{$this->domain}{$path}";
        $headers = array(
            'Accept' => 'application/vnd.api+json'
        );

        switch ($method) {
            case 'GET':
                $url .= '?key=' . urlencode($this->pull_key);
                foreach ($params as $key => $value) {
                    $url .= '&' . urlencode($key) . '=' . urlencode($value);
                }

                break;
        }

        $user_agent = 'Podiant/' . PODIANT_VERSION;
        $http = new WP_Http();

        $response = $http->request(
            $url,
            array(
                'method' => $method,
                'headers' => $headers,
                'user_agent' => $user_agent
            )
        );

        if (is_wp_error($response)) {
            podiant_log(
                "API: $method $path 0",
                PODIANT_LOG_ERROR,
                array(
                    'extra' => array(
                        'message' => $response->get_error_message()
                    )
                )
            );

            throw new Exception($response->get_error_message());
        }

        $headers = $response['headers'];
        $content_type = $headers['content-type'];
        $status = $response['response']['code'];
        $body = $response['body'];

        if ($content_type == 'application/vnd.api+json') {
            $body = json_decode($body, true);
        } else {
            throw new Exception('Unexpected reply from server.');
        }

        if (isset($body['error'])) {
            podiant_log(
                "API: $method $path $status",
                PODIANT_LOG_ERROR,
                array(
                    'extra' => array(
                        'message' => $body['error']
                    )
                )
            );

            if (isset($body['error']['detail'])) {
                throw new Exception($body['error']['detail']);
            }

            throw new Exception($body['error']['title']);
        }

        podiant_log("API: $method $path $status", PODIANT_LOG_INFO);
        return $body;
    }

    /**
     * Returns info relating to the provided pull key.
     *
     * @since 1.0
     * @param string $path The path to read.
     */
    public function get($path='~/') {
        $response = $this->perform('GET', $path);
        return $response['data'];
    }

    /**
     * Iterates through a list of data objects, executing a callback
     * function for each item.
     *
     * @since 1.0
     * @param string $path The path to read.
     * @param callable $callback;
     */
    public function iterate($path, $callback) {
        $query = array();
        while (true) {
            $response = $this->perform('GET', $path, $query);
            $links = $response['links'];
            $data = $response['data'];

            foreach ($data as $item) {
                call_user_func($callback, $item);
            }

            if (isset($links['next']) && $links['next']) {
                $parts = wp_parse_url($links['next']);
                $path = $parts['path'];
                $query = array();
                parse_str($parts['query'], $query);

                if (isset($query['page'])) {
                    podiant_log(
                        'Moving to page ' . $query['page'],
                        PODIANT_LOG_DEBUG
                    );
                }

                if (isset($query['key'])) {
                    unset($query['key']);
                }
            } else {
                break;
            }
        }
    }
}
