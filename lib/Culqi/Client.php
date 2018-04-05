<?php
namespace Culqi;

use Culqi\Error as Errors;

/**
 * Class Client
 *
 * @package Culqi
 */
class Client {
    public function request($method, $url, $api_key, $data = NULL) {
        try {
            $url_params = is_array($data) ? '?' . http_build_query($data) : '';
            $headers= array("Authorization" => "Bearer ".$api_key, "Content-Type" => "application/json", "Accept" => "application/json");
            $options = array(
                'timeout' => 120
            );
            if($method == "GET") {
                $response = \Requests::get(Culqi::BASE_URL. $url . $url_params, $headers, $options);
            } else if($method == "POST") {
                $response = \Requests::post(Culqi::BASE_URL . $url, $headers, json_encode($data), $options);
            } else if($method == "PATCH") {
                $response = \Requests::patch(Culqi::BASE_URL . $url, $headers, json_encode($data), $options);
            } else if($method == "DELETE") {
                $response = \Requests::delete(Culqi::BASE_URL. $url . $url_params, $headers, $options);
            }
        } catch (\Exception $e) {
            throw new Errors\UnableToConnectException();
        }
        
        if ($response->status_code >= 200 && $response->status_code <= 206) {
            return json_decode($response->body);
        }
        if ($response->status_code == 400) {
            throw Errors\InputValidationException::fromJson($response->body);
        }
        if ($response->status_code == 401) {
            throw Errors\AuthenticationException::fromJson($response->body);
        }
        if ($response->status_code == 402) {
            throw Errors\CardException::fromJson($response->body);
        }
        if ($response->status_code == 404) {
            throw new Errors\NotFoundException();
        }
        if ($response->status_code == 405) {
            throw new Errors\MethodNotAllowedException();
        }
        throw new Errors\UnhandledException();
    }
}
