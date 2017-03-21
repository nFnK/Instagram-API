<?php

namespace InstagramAPI;

/**
 * Bridge between HttpInterface object & mapper & response.
 */
class Request
{
    protected $params = [];
    protected $posts = [];
    protected $requireLogin = false;
    protected $checkStatus = true;
    protected $signedPost = true;

    public function __construct(
        $url)
    {
        $this->url = $url;

        return $this;
    }

    public function addParams(
        $key,
        $value)
    {
        if ($value === true) {
            $value = 'true';
        }
        $this->params[$key] = $value;

        return $this;
    }

    public function addPost(
        $key,
        $value)
    {
        $this->posts[$key] = $value;

        return $this;
    }

    public function requireLogin(
        $requireLogin = false)
    {
        $this->requireLogin = $requireLogin;

        return $this;
    }

    public function setCheckStatus(
        $checkStatus = true)
    {
        $this->checkStatus = $checkStatus;

        return $this;
    }

    public function setSignedPost(
        $signedPost = true)
    {
        $this->signedPost = $signedPost;

        return $this;
    }

    public function getResponse(
        $baseClass,
        $includeHeader = false)
    {
        $instagramObj = Instagram::getInstance();

        if ($this->params) {
            $endPoint = $this->url.'?'.http_build_query($this->params);
        } else {
            $endPoint = $this->url;
        }

        $payload = json_encode($this->posts);
        if ($this->posts) {
            if ($this->signedPost) {
                $post = Signatures::generateSignature($payload);
            } else {
                $post = http_build_query($this->posts);
            }
        } else {
            $post = null;
        }

        $response = $instagramObj->http->api($endPoint, $post, $this->requireLogin, false);

        $responseObject = $instagramObj->http->getMappedResponseObject(
            $baseClass,
            $response[1], // [0] = Token. [1] = The actual server response.
            $this->checkStatus, // Whether to validate that API response "status" MUST be Ok.
            ($includeHeader ? $response : null) // null = Reuse $response[1].
        );

        return $responseObject;
    }
}
