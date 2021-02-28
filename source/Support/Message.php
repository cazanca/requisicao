<?php


namespace Source\Support;


use Source\Core\Session;

class Message
{
    private $text;
    private $type;
    private $before;
    private $after;

    public function __toString()
    {
        return $this->render();
    }


    public function getText(): ?string
    {
        return $this->before . $this->text . $this->after;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function before(string $text): Message
    {
        $this->before = $text;
        return $this;
    }

    public function after(string $text): Message
    {
        $this->after = $text;
        return $this;
    }

    public function info(string $message): Message
    {
        $this->type = CONF_MESSAGE_INFO;
        $this->text = $this->filter($message);
        return $this;
    }

    public function success(string $message): Message
    {
        $this->type = CONF_MESSAGE_SUCCESS;
        $this->text = $this->filter($message);
        return $this;
    }

    public function warning(string $message): Message
    {
        $this->type = CONF_MESSAGE_WARNING;
        $this->text = $this->filter($message);
        return $this;
    }

    public function error(string $message): Message
    {
        $this->type = CONF_MESSAGE_ERROR;
        $this->text = $this->filter($message);
        return $this;
    }

    public function render()
    {
        return "<div class='".CONF_MESSAGE_CLASS." {$this->getType()}'>{$this->getText()}</div>";
    }

    public function flash(): void
    {
        (new Session())->set("flash", $this);
    }

    public function filter(string $message): string
    {
        return filter_var($message, FILTER_SANITIZE_STRIPPED);
    }
}