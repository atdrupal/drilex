<?php

namespace atphp\drilex\drupal;

class Response
{

    private $title;
    private $content;
    private $code;
    private $messages;

    public function __construct($title, $content, $code = 200, array $messages = [])
    {
        $this->title = $title;
        $this->content = $content;
        $this->code = $code;
        $this->messages = $messages;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

}
