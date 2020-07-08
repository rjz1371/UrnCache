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
     * @param int    $expire
     * @param string $group
     * @return bool
     */
    public static function put($key, $value, $group, $expire = 0) {
        if (empty($value) or is_null($value)) {
            return false;
        }
        $expire = intval($expire);
        $key = trim($key);
        $group = trim($group);
        $key = preg_replace('/[^a-zA-Z0-9\-]/', '-', $key);
        $cacheData['data'] = serialize($value);
        $cacheData['expire'] = 0;
        $filename = '';
        $cacheDir = storage_path('app/urncache/');
        if (!file_exists($cacheDir)) {
            mkdir($cacheDir);
        }
        $cacheDir .= preg_replace('/[^a-zA-Z0-9_\-]/', '', $group);
        if (!file_exists($cacheDir)) {
            mkdir($cacheDir);
        }
        if (file_exists($cacheDir)) {
            $files = scandir($cacheDir);
            foreach ($files as $file) {
                if ($file == '.' or $file == '..' or is_dir($cacheDir . '/' . $file)) {
                    continue;
                }
                $explode = explode('_', $file);
                if ($explode[0] == $key) {
                    $filename = $cacheDir . '/' . $file;
                    break;
                }
            }
        }
        if ($filename == '') {
            $microtime = str_replace('.', '_', microtime(true));
            $filename = $cacheDir . '/' . $key . '_' . $microtime . '_' . Str::random(10) . '.urncache';
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
     * @param        $cacheName
     * @param string $group
     * @return bool|mixed
     */
    public static function get($key, $group) {
        $find = false;
        $key = trim($key);
        $group = trim($group);
        $key = preg_replace('/[^a-zA-Z0-9\-]/', '-', $key);
        $filename = storage_path('app/urncache/');
        if ($group != '') {
            $filename .= '/' . preg_replace('/[^a-zA-Z0-9_\-]/', '', $group);
        }
        if (!file_exists($filename)) {
            return false;
        }
        $files = scandir($filename);
        foreach ($files as $file) {
            if ($file == '.' or $file == '..' or is_dir($filename . '/' . $file)) {
                continue;
            }
            $explode = explode('_', $file);
            if ($explode[0] == $key) {
                $filename = $filename . '/' . $file;
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
        $key = trim($key);
        $group = trim($group);
        $key = preg_replace('/[^a-zA-Z0-9\-]/', '-', $key);
        $filename = storage_path('app/urncache/');
        if ($group != '') {
            $filename .= preg_replace('/[^a-zA-Z0-9_\-]/', '', $group);
        }
        if (!file_exists($filename)) {
            return false;
        }
        $files = scandir($filename);
        foreach ($files as $file) {
            if ($file == '.' or $file == '..' or is_dir($filename . '/' . $file)) {
                continue;
            }
            $explode = explode('_', $file);
            if ($explode[0] == $key) {
                $filename = $filename . '/' . $file;
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
     * @param        $cacheName
     * @param string $group
     * @return bool
     */
    public static function delete($cacheName, $group) {
        $cacheName = trim($cacheName);
        $group = trim($group);
        $cacheName = preg_replace('/[^a-zA-Z0-9\-]/', '-', $cacheName);
        $filename = storage_path('app/urncache/');
        if ($group != '') {
            $filename .= preg_replace('/[^a-zA-Z0-9_\-]/', '', $group);
        }
        if (!file_exists($filename)) {
            return false;
        }
        $files = scandir($filename);
        foreach ($files as $file) {
            if ($file == '.' or $file == '..' or is_dir($filename . '/' . $file)) {
                continue;
            }
            $explode = explode('_', $file);
            if ($explode[0] == $cacheName) {
                $filename = $filename . '/' . $file;
                unlink($filename);
                return true;
            }
        }

        return false;
    }

    /**
     * Delete cache in special group.
     * 
     * @param string $group
     * @return bool
     */
    public static function deleteByGroup($group = '') {
        $group = trim($group);
        $filename = storage_path('app/urncache/');
        if (!file_exists($filename)) {
            return false;
        }
        if ($group == '') {
            $files = array_diff(scandir($filename), ['.', '..']);
            foreach ($files as $file) {
                if (is_dir($filename . '/' . $file)) {
                    continue;
                }
                unlink($filename . '/' . $file);
            }

            return true;
        }
        $files = array_diff(scandir($filename), ['.', '..']);
        foreach ($files as $file) {
            if (is_file($filename . '/' . $file)) {
                continue;
            }
            if ($file == $group) {
                $currentDirFiles = array_diff(scandir($filename . '/' . $file), ['.', '..']);
                foreach ($currentDirFiles as $f) {
                    unlink($filename . '/' . $group . '/' . $f);
                }
                rmdir($filename . '/' . $group);
                return true;
            }
        }

        return false;
    }

    /**
     * Delete all cache files and folders.
     * 
     * @return bool
     */
    public static function deleteAll() {
        $filename = storage_path('app/urncache/');
        if (!file_exists($filename)) {
            return false;
        }
        $files = array_diff(scandir($filename), ['.', '..']);
        foreach ($files as $file) {
            if (is_file($filename . '/' . $file)) {
                unlink($filename . '/' . $file);
            } else {
                $currentDirFiles = array_diff(scandir($filename . '/' . $file), ['.', '..']);
                foreach ($currentDirFiles as $f) {
                    unlink($filename . '/' . $file . '/' . $f);
                }
                rmdir($filename . '/' . $file);
            }
        }

        return true;
    }
}