<?php

namespace rjz1371\UrnCache;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UrnCache {

    /**
     * Store cache.
     * 
     * @param        $key
     * @param        $value
     * @param string $group
     * @param int    $expire
     * @return bool
     */
    public static function put($key, $value, $group, $expire = 0) {
        self::init();
        $expire = intval($expire);
        $key = self::retrieveKey($key);
        $group = self::retrieveGroupPath($group);
        $cacheData['data'] = serialize($value);
        $cacheData['expire'] = 0;
        $filename = '';
        if (!file_exists($group)) {
            mkdir($group);
        } else {
            $files = array_diff(scandir($group), ['.', '..']);
            foreach ($files as $file) {
                $explode = explode('_', $file);
                if ($explode[0] == $key) {
                    $filename = $group . '/' . $file;
                    break;
                }
            }
        }
        if (empty($filename)) {
            $microtime = str_replace('.', '_', microtime(true));
            $filename = $group . '/' . $key . '_' . $microtime . '_' . Str::random(10) . '.urncache';
        }
        if ($expire <> 0) {
            $cacheData['expire'] = strtotime('+' . $expire . ' second');
        }
        File::put($filename, serialize($cacheData));

        return true;
    }

    /**
     * Retrieve cache
     * 
     * @param        $key
     * @param string $group
     * @return bool|mixed
     */
    public static function get($key, $group) {
        $find = false;
        $key = self::retrieveKey($key);
        $group = self::retrieveGroupPath($group);
        if (!file_exists($group)) {
            return false;
        }
        $files = array_diff(scandir($group), ['.', '..']);
        foreach ($files as $file) {
            $explode = explode('_', $file);
            if ($explode[0] == $key) {
                $filename = $group . '/' . $file;
                $find = true;
                break;
            }
        }
        if (!$find) {
            return false;
        }
        $data = unserialize(File::get($filename));
        if ($data['expire'] <> 0 and $data['expire'] <= time()) {
            unlink($filename);
            return false;
        }

        return unserialize($data['data']);
    }

    /**
     * Cache exists or not.
     * 
     * @param        $key
     * @param string $group
     * @return bool
     */
    public static function has($key, $group = '') {
        $find = false;
        $key = self::retrieveKey($key);
        $group = self::retrieveGroupPath($group);
        if (!file_exists($group)) {
            return false;
        }
        $files = array_diff(scandir($group), ['.', '..']);
        foreach ($files as $file) {
            $explode = explode('_', $file);
            if ($explode[0] == $key) {
                $filename = $group . '/' . $file;
                $find = true;
                break;
            }
        }
        if (!$find) {
            return false;
        }
        $data = unserialize(file_get_contents($filename));
        if ($data['expire'] <> 0 and $data['expire'] <= time()) {
            unlink($filename);
            return false;
        }

        return $find;
    }

    /**
     * Delete cache.
     * 
     * @param        $key
     * @param string $group
     * @return bool
     */
    public static function delete($key, $group) {
        $key = self::retrieveKey($key);
        $group = self::retrieveGroupPath($group);
        if (!file_exists($group)) {
            return false;
        }
        $files = array_diff(scandir($group), ['.', '..']);
        foreach ($files as $file) {
            $explode = explode('_', $file);
            if ($explode[0] == $key) {
                $filename = $group . '/' . $file;
                unlink($filename);
                return true;
            }
        }

        return false;
    }

    /**
     * Delete all cache in special group.
     * 
     * @param string $group
     * @return bool
     */
    public static function deleteByGroup($group) {
        $group = self::retrieveGroupPath($group);
        $files = array_diff(scandir($group), ['.', '..']);
        foreach ($files as $file) {
            unlink($group . '/' . $file);
        }

        return true;
    }

    /**
     * Delete all cache files and folders.
     * 
     * @return bool
     */
    public static function deleteAll() {
        $filename = storage_path('app/urncache');
        $files = array_diff(scandir($filename), ['.', '..']);
        foreach ($files as $file) {
            $pathname = $filename . '/' . $file;
            if (is_file($pathname)) {
                continue;
            }
            $currentDirFiles = array_diff(scandir($pathname), ['.', '..']);
            foreach ($currentDirFiles as $f) {
                unlink($pathname . '/' . $f);
            }
            rmdir($pathname);
        }

        return true;
    }

    /**
     * create urncache dir if not exists.
     */
    private static function init() {
        $pathname = storage_path('app/urncache');
        if (!file_exists($pathname)) {
            mkdir($pathname);
        }
    }

    /**
     * retrieve cache key name.
     */
    private static function retrieveKey($key) {
        $key = trim($key);
        return preg_replace('/[^a-zA-Z0-9\-]/', '-', $key);
    }

    /**
     * retrieve group path.
     */
    private static function retrieveGroupPath($group) {
        $group = trim($group);
        return storage_path('app/urncache/') . preg_replace('/[^a-zA-Z0-9_\-]/', '', $group);
    }
}