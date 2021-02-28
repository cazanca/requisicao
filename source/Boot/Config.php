<?php

ini_set("display_errors", "1");

# Database Config
define("CONF_DB_HOST", "localhost");
define("CONF_DB_USER", "root");
define("CONF_DB_PASS", "");
define("CONF_DB_NAME", "stock");

/*
 * SITE
 */
define("CONF_SITE_NAME", "Stock");
/*
 * URL
 */
# Substituir url base com o dominio da aplicação
define("CONF_URL_BASE", "http://www.localhost/dev/stockV2");
define("CONF_URL_TEST", "http://www.localhost/dev/stockV2");
define("CONF_URL_ADMIN", "/admin");
define("CONF_URL_ERROR", "/404");

/*
 * DATES
 */
define("CONF_DATE_MZ", "d/m/Y H:i:s");
define("CONF_TIMEZONE_MZ", "Africa/Maputo");
define("CONF_DATE_APP", "Y-m-d H:i:s");

/*
 * SESSION
 */
define("CONF_SES_PATH", __DIR__ . "/../../storage/sessions");

/*
 * PASSWORD
 */
define("CONF_PASSWD_MIN_LEN", 8);
define("CONF_PASSWD_MAX_LEN", 40);
define("CONF_PASSWD_ALGO", PASSWORD_DEFAULT);
define("CONF_PASSWD_OPTION", ["cost" => 10]);
/*
 * MESSAGE
 * Exemplo: Bootstrap -> class: alert | info: alert-info
 */

define("CONF_MESSAGE_CLASS", "floating-alert");
define("CONF_MESSAGE_INFO", "alert-info");
define("CONF_MESSAGE_SUCCESS", "alert-success");
define("CONF_MESSAGE_WARNING", "alert-warning");
define("CONF_MESSAGE_ERROR", "alert-error");

/**
 * VIEW
 */
define("CONF_VIEW_PATH", __DIR__ . "/../../public/views");
define("CONF_VIEW_THEME", "default");
define("CONF_VIEW_EXT", "php");


/**
 * UPLOAD
 */
define("CONF_UPLOAD_DIR", "storage");
define("CONF_UPLOAD_IMAGE_DIR", "images");
define("CONF_UPLOAD_FILE_DIR", "files");
define("CONF_UPLOAD_MEDIA_DIR", "medias");

/**
 * IMAGES
 */
define("CONF_IMAGE_CACHE", CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR . "/cache");
define("CONF_IMAGE_SIZE", 2000);
define("CONF_IMAGE_QUALITY", ["jpg" => 75, "png" => 5]);

/**
 * MAIL
 */
# Gerir atraves da Interface - guardar na DB as Config do Mail
define("CONF_MAIL_HOST", "smtp.gmail.com");
define("CONF_MAIL_PORT", "587");
define("CONF_MAIL_USER", "apikey");
define("CONF_MAIL_PASS", "*************");
define("CONF_MAIL_SENDER", ["name" => "User Test", "address" => "email@gmail.com"]);
define("CONF_MAIL_SUPPORT", "support@gmail.com");
define("CONF_MAIL_OPTION_LANG", "br");
define("CONF_MAIL_OPTION_HTML", true);
define("CONF_MAIL_OPTION_AUTH", true);
define("CONF_MAIL_OPTION_SECURE", "tls");
define("CONF_MAIL_OPTION_CHARSET", "utf-8");
