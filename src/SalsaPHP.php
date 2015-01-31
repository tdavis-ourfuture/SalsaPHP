<?php

abstract class SalsaPHP
{
  /**
   * @var string The sessionCookie to be used for requests.
   */
  public static $sessionCookie;

  /**
   * @var string The Guzzle Client to be used for requests.
   */
  public static $apiClient;

  /**
   * @var string The Salsa Organization key to be used for requests.
   */
  public static $organizationKey;
 /**
   * @var string The Salsa User Name to be used for requests.
   */
  public static $userName;
 /**
   * @var string The Salsa Password to be used for requests.
   */
  public static $password;
  /**
   * @var string The base URL for the Salsa API.
   */
  public static $apiBase = 'https://api.salsa.com';
  /**
   * @var string The base URL for the Salsa API uploads endpoint.
   */
  public static $apiUploadBase = 'https://uploads.salsa.com';
  /**
   * @var string|null The version of the Salsa API to use for requests.
   */
  public static $apiVersion = null;
  /**
   * @var boolean Defaults to true.
   */
  public static $verifySslCerts = true;
  const VERSION = '1.18.0';

  /**
   * @return string The UserName used for requests.
   */
  public static function getUserName()
  {
    return self::$userName;
  }

  /**
   * Sets the UserName to be used for requests.
   *
   * @param string $userName
   */
  public static function setUserName($userName)
  {
    self::$userName = $userName;
  }

 /**
   * Initiates the guzzle client used for requests.
   *
   */
  public static function initClient()
  {
    self::$apiClient = new Guzzle\Http\Client(self::getApiBase());
    $cookiePlugin = new Guzzle\Plugin\Cookie\CookiePlugin(new Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar());

    self::$apiClient->addSubscriber($cookiePlugin);

    Connect::authenticate();
  }


  /**
   * @return Client The  guzzle client used for requests.
   */
  public static function getClient()
  {
    return     self::$apiClient;
  }

  /**
   * @return string The Password used for requests.
   */
  public static function getPassword()
  {
    return self::$password;
  }

  /**
   * Sets the Password to be used for requests.
   *
   * @param string $password
   */
  public static function setPassword($password)
  {
    self::$password = $password;
  }
  /**
   * @return string The API version used for requests. null if we're using the
   *    latest version.
   */
  public static function getApiVersion()
  {
    return self::$apiVersion;
  }
  /**
   * @return string The Organization Key used for requests.
   */
  public static function getOrganizationKey()
  {
    return self::$organizationKey;
  }
  /**
   * Sets the Organization Key  to be used for requests.
   *
   * @param string $organizationKey
   */
  public static function setOrganizationKey($organizationKey)
  {
    self::$organizationKey = $organizationKey;
  }
  /**
   * @return string The API Base URL used for requests.
   */
  public static function getApiBase()
  {
    return self::$apiBase;
  }

  /**
   * Sets the API Base URL to be used for requests.
   *
   * @param string $apiBase
   */
  public static function setApiBase($apiBase)
  {
    self::$apiBase = $apiBase;
  }
  /**
   * @param string $apiVersion The API version to use for requests.
   */
  public static function setApiVersion($apiVersion)
  {
    self::$apiVersion = $apiVersion;
  }

  /**
   * @return boolean
   */
  public static function getVerifySslCerts()
  {
    return self::$verifySslCerts;
  }

  /**
   * @param boolean $verify
   */
  public static function setVerifySslCerts($verify)
  {
    self::$verifySslCerts = $verify;
  }
  /**
   * @return string The SessionCookie used for requests.
   */
  public static function getSessionCookie()
  {
    return self::$sessionCookie;
  }

  /**
   * Sets the SessionCookie to be used for requests.
   *
   * @param string $sessionCookie
   */
  public static function setSessionCookie($sessionCookie)
  {
    self::$sessionCookie = $sessionCookie;
  }


}