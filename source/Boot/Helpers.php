<?php

/**
 * @param string $email
 * @return bool
 */
function is_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}


/**
 * @param string $password
 * @return bool
 */
function is_passwd(string $password): bool
{
    if (password_get_info($password)['algo'] || (mb_strlen($password) >= CONF_PASSWD_MIN_LEN && mb_strlen($password) <= CONF_PASSWD_MAX_LEN)) {
        return true;
    }
    return false;
}

/**
 * @param string $password
 * @return string
 */
function passwd(string $password): string
{
    return password_hash($password, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}

/**
 * @param string $password
 * @param string $hash
 * @return bool
 */
function passwd_verify(string $password, string $hash): bool
{
    return password_verify($password,$hash);
}

/**
 * @param string $hash
 * @return bool
 */
function passwd_rehash(string $hash): bool
{
    return password_needs_rehash($hash, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}

/**
 * @param $path
 * @return string
 */
function url(string $path = null): string
{
    if (strpos($_SERVER['HTTP_HOST'], "localhost")){
        if ($path){
            return CONF_URL_TEST . "/" . ($path[0] == '/' ? mb_substr($path, 1) : $path);
        }

        return CONF_URL_TEST;
    }

    if ($path){
        return CONF_URL_BASE . "/" . ($path[0] == '/' ? mb_substr($path, 1) : $path);
    }

    return CONF_URL_BASE;

}

/**
 * @return string
 */
function url_back(): string
{
    # $_SERVER['HTTP_REFERER'] - url anterior
    return ($_SERVER['HTTP_REFERER'] ?? url());
}

/**
 * @param string|null $path
 * @return string
 */
function theme(string $path = null): string
{
    if(strpos($_SERVER['HTTP_HOST'], "localhost")){
        if ($path) {
            return CONF_URL_TEST . "/themes/" . CONF_VIEW_THEME . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }

        return CONF_URL_TEST . "/themes/" . CONF_VIEW_THEME;
    }

    if ($path) {
        return CONF_URL_BASE . "/themes/" . CONF_VIEW_THEME . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return CONF_URL_BASE . "/themes/" . CONF_VIEW_THEME;
}

/**
 * @param string $url
 */
function redirect(string $url): void
{
    header("HTTP/1.1 302 Redirect");
    if (filter_var($url, FILTER_VALIDATE_URL)){
        header("Location: {$url}");
        exit();
    }

    $location = url($url);
    header("Location: {$location}");
    exit();
}

/**
 * @return PDO
 */
function db(): PDO
{
    return \Source\Core\Connect::getInstance();
}


/**
 * @return \Source\Support\Message
 */
function message(): \Source\Support\Message
{
    return new \Source\Support\Message();
}


function date_fmt(string $date = "now", string $format = "d/m/Y H\hi"): string
{
    return (new DateTime($date))->format($format);
}

/**
 * @return \Source\Core\Session
 */
function session(): \Source\Core\Session
{
    return new \Source\Core\Session();
}

/*
 * Model
 */

/**
 * @return \Source\Models\User
 */
function user(): \Source\Models\User
{
    return new \Source\Models\User();
}


/*
 * SECURITY | CSRF | XSS
 */

# $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS)

/**
 * @return string
 */
function csrf_input(): string
{
    session()->csrf();
    return "<input type='hidden' style='width:50%;' name='csrf' value='".(session()->csrf_token ?? "")."'/>";
}


/**
 * @param $request
 * @return bool
 */
function csrf_verify($request): bool
{
    #$session = new \Source\Core\Session();
    
    if (empty(session()->csrf_token) || empty($request['csrf']) || $request['csrf'] != session()->csrf_token) {
        return false;
    }
    return true;
}

/**
 * @return string|null
 */
function flash(): ?string
{
    $session = new \Source\Core\Session();
    if ($flash = $session->flash()) {
        echo $flash;
    }
    return null;
}

# SRLI - SITE REQUEST LIMIT INTERVAL
/**
 * @param string $key
 * @param int $limit
 * @param int $seconds
 * @return bool
 */
function request_limit(string $key, int $limit = 5, int $seconds = 60): bool
{
    $session = new \Source\Core\Session();
    if ($session->has($key) && $session->$key->time >= time() && $session->$key->requests < $limit) {
        $session->set($key,[
            "time" => time() + $seconds,
            "requests" => $session->$key->requests + 1
        ]);

        return false;
    }

    if ($session->has($key) && $session->$key->time >= time() && $session->$key->requests >= $limit) {
        return true;
    }

    $session->set($key,[
        "time" => time() + $seconds,
        "requests" => 1
    ]);

    return false;

}

# FFVR - FORM FIELD VALUE REPEAT
/**
 * @param string $field
 * @param string $value
 * @return bool
 */
function request_repeat(string $field, string $value): bool
{
    $session = new \Source\Core\Session();
    if ($session->has($field) && $session->$field == $value) {
        return true;
    }

    $session->set($field, $value);
    return false;
}

function auth()
{
    return \Source\Models\Auth::user();
}

function dd($data)
{
    echo "<pre>";
        var_dump($data);
    echo "</pre>";
    return;
}