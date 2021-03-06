<?php

namespace Tester;

class Config
{
    private static $_config = [];
    private static $_location = [];

    public static function get($credentialsFile, $location = '')
    {
        if (false == is_file($credentialsFile)) {
            throw new \RuntimeException(sprint('Credential file "%s" doesn\'t exists', $credentialsFile));
        }

        self::$_config = require $credentialsFile;
        if ($location == '') {
            $location = self::$_config['default'];
        }

        if (isset(self::$_config[$location]) == false) {
            throw new \RuntimeException(sprintf('Invalid location, no "%s" in config file', $location));
        }

        self::$_location = $location;
        return self::$_config[self::$_location];
    }

    public static function getDomain()
    {
        if (isset(self::$_config[self::$_location]['domainName'])) {
            return trim(self::$_config[self::$_location]['domainName'], '/').'/';
        }

        return '';
    }

    public static Function getCloudFrontDomain()
    {
        if (isset(self::$_config[self::$_location]['cloudFrontDomainName'])) {
            return trim(self::$_config[self::$_location]['cloudFrontDomainName'], '/').'/';
        }

        return '';
    }
}