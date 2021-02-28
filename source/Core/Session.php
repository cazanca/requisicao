<?php


namespace Source\Core;


use Source\Support\Message;

/**
 * Class Session
 * @package Source\Core
 */
class Session
{
    /**
     * Session constructor.
     */
    public function __construct()
    {
        if (!session_id()){
            session_start();
        }
    }

    public function __get($name)
    {
        if (!empty($_SESSION[$name])){
            return $_SESSION[$name];
        }

        return null;
    }

    public function __isset($name)
    {
        return $this->has($name);
    }

    public function all(): ?object
    {
        return (object)$_SESSION;
    }

    public function set(string $key, $value): Session
    {
        $_SESSION[$key] = (is_array($value) ? (object)$value : $value);
        return $this;
    }

    public function unset(string $key): Session
    {
        unset($_SESSION[$key]);
        return $this;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function regenerate(): Session
    {
        session_regenerate_id(true);
        return $this;
    }

    public function destroy(): Session
    {
        session_destroy();
        return $this;
    }

    public function flash(): ?Message
    {
        if ($this->has("flash")){
            $flash = $this->flash;
            $this->unset("flash");
            return $flash;
        }
        return null;
    }

    /*
     * CSRF
     */
    public function csrf(): void
    {
        $_SESSION['csrf_token'] = md5(uniqid(rand(), true));
    }
}