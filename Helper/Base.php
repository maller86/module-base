<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the license that is available in LICENSE file.
 *
 * DISCLAIMER
 *
 * Do not edit this file if you wish to upgrade this extension to newer version in the future.
 */

namespace FjodorMaller\Base\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use FjodorMaller\Base\Api\FjodorMallerHelperInterface;

/**
 * Class Base
 */
abstract class Base extends AbstractHelper implements FjodorMallerHelperInterface
{
    /**
     * Returns the scopes to check configurations.
     *
     * @return array
     */
    protected function getScopes()
    {
        return [
            ScopeInterface::SCOPE_STORE,
            ScopeInterface::SCOPE_WEBSITE,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        ];
    }

    /**
     * @inheritdoc
     */
    public function isEnabled($store = null, array $paths = [], $scope = null)
    {
        return $this->isFlag([
                self::XML_PATH_MODULE_GENERAL_ENABLED,
                static::XML_PATH_MODULE_GENERAL_ENABLED,
            ] + $paths, $store, $scope);
    }

    /**
     * @inheritdoc
     */
    public function isLoggingEnabled($store = null, array $paths = [], $scope = null)
    {
        return $this->isFlag([
                self::XML_PATH_MODULE_LOGGING_ENABLED,
                static::XML_PATH_MODULE_LOGGING_ENABLED,
            ] + $paths, $store, $scope);
    }

    /**
     * @inheritdoc
     */
    public function getLoggingLocation($store = null, array $paths = [], $scope = null)
    {
        return trim($this->getValue([
                    self::XML_PATH_MODULE_LOGGING_LOCATION,
                    static::XML_PATH_MODULE_LOGGING_LOCATION,
                ] + $paths, $store, $scope), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * @inheritdoc
     */
    public function getLoggingFilename($store = null, array $paths = [], $extension = null, $scope = null)
    {
        if (null === $extension) {
            $extension = 'log';
        }
        $result = $this->getValue([
                self::XML_PATH_MODULE_LOGGING_FILENAME,
                static::XML_PATH_MODULE_LOGGING_FILENAME,
            ] + $paths, $store, $scope);
        $result = basename($result);
        $result = strtolower($result);
        if (false === strpos($result, '.') &&
            substr($result, -(strlen($extension))) != $extension) {
            $result .= '.' . $extension;
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getValue($paths, $store = null, $scope = null)
    {
        $paths = (array)$paths;
        if (count($paths)) {
            if (null === $scope) {
                $scope = $this->getScopes();
            }
            $scope = (array)$scope;
            foreach ($scope as $_scope) {
                foreach ($paths as $path) {
                    if (is_string($path) &&
                        null !== ($result = $this->scopeConfig->getValue(strtolower($path), $_scope, $store))) {
                        return $result;
                    }
                }
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function isFlag($paths, $store = null, $scope = null)
    {
        $paths = (array)$paths;
        if (count($paths)) {
            if (null === $scope) {
                $scope = $this->getScopes();
            }
            $scope = (array)$scope;
            foreach ($scope as $_scope) {
                foreach ($paths as $path) {
                    if (is_string($path) && $this->scopeConfig->isSetFlag(strtolower($path), $_scope, $store)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Returns an array exploded by separator from given string.
     *
     * @param string        $string
     * @param string        $separator
     * @param null|\Closure $filter
     *
     * @return array
     */
    public static function toArray($string, $separator = ',', $filter = null)
    {
        $result = explode($separator, $string);
        $result = array_map('trim', $result);
        $result = array_filter($result, $filter);
        $result = array_values($result);

        return $result;
    }

    /**
     * Returns a option array by given data.
     *
     * @param array $data
     *
     * @return array
     */
    public static function toOptions(array $data)
    {
        $result = [];
        foreach ($data as $value => $label) {
            $result[] = [
                'value' => $value,
                'label' => $label,
            ];
        }

        return $result;
    }
}
