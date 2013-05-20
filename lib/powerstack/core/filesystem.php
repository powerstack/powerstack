<?php
/*
* Copyright (c) 2013 onwards Christopher Tombleson <chris@powerstack-php.org>
*
* Permission is hereby granted, free of charge, to any person obtaining a copy of this
* software and associated documentation files (the "Software"), to deal in the Software
* without restriction, including without limitation the rights to use, copy, modify, merge,
* publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons
* to whom the Software is furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING
* BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
* IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
* IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE
* OR OTHER DEALINGS IN THE SOFTWARE.
*/
/**
* Filesystem
* Filesystem class for Powerstack
*
* @author Christopher Tombleson <chris@powerstack-php.org>
* @package Powerstack
* @subpackage Core
*/
namespace Powerstack\Core;

class Filesystem {

    /**
    * Move
    * Move a file
    *
    * @access public
    * @param string $orig   File to move
    * @param string $new    New file path
    * @return bool true on success, otherwise false
    */
    public static function move($orig, $new) {
        if (self::copy($orig, $new)) {
            return self::remove($orig);
        }

        return false;
    }

    /**
    * Copy
    * Copy a file
    *
    * @access public
    * @param string $orig   File to copy
    * @param string $new    Where to copy file to
    * @return bool true on success, otherwise false
    */
    public static function copy($orig, $new) {
        return copy($orig, $new);
    }

    /**
    * Remove
    * Remove a file of directory
    *
    * @access public
    * @param string $path       Path to file/dir to remove
    * @param bool   $dir        Removing directory. (optional default is false)
    * @param bool   $recursive  Recursivly remove files and directories. (optional default is false)
    * @return bool true on success, otherwise false
    */
    public static function remove($path, $dir=false, $recursive=false) {
        if ($dir) {
            if (!$recursive) {
                return rmdir($path);
            }

            $list = self::listAll($path, true);

            if (!empty($list['files'])) {
                foreach ($list['files']  as $file) {
                    unlink($file);
                }
            }

            if (!empty($list['dirs'])) {
                foreach ($list['dirs'] as $dir) {
                    rmdir($dir);
                }
            }

            return true;
        } else {
            return unlink($path);
        }
    }

    /**
    * Read File
    * Read a File
    *
    * @access public
    * @param string $file   File to read
    * @return mixed contents of file on success, otherwise false
    */
    public static function readFile($file) {
        if (!file_exists($file)) {
            throw new CoreException("Unable to read file: " . $file . " as it does not exist");
        }

        if (!is_readable($file)) {
            throw new CoreException("Unable to read file: " . $file . " as it is not readable");
        }

        if (function_exists('file_get_contents')) {
            $content = file_get_contents($file);

            if ($content === false) {
                return false;
            }

            return $content;
        }

        $fh = fopen($file, 'r');

        if (!$fh) {
            return false;
        }

        $content = fread($fh);

        if ($content === false) {
            fclose($fh);
            return false;
        }

        fclose($fh);
        return $content;
    }


    /**
    * Write File
    * Write to a file
    *
    * @access public
    * @param string $file   File to write to
    * @param string $data   Data to write to file
    * @return bool true on success, otherwise false
    */
    public static function writeFile($file, $data) {
        if (!is_writeable(dirname($file))) {
            throw new CoreException("Unable to write file: " .$file . " directory is not writeable");
        }

        if (function_exists('file_put_contents')) {
            if (file_put_contents($file, $data) !== false) {
                return true;
            }

            return false;
        }

        $fh = fopen($file, 'w+');

        if (!$fh) {
            return false;
        }

        if (fwrite($fh, $data) === false) {
            fclose($fh);
            return false;
        }

        return fclose($fh);
    }

    /**
    * Append File
    * Append a file
    *
    * @access public
    * @param string $file   File to append to
    * @param string $data   Data to append
    * @return bool true on success, otherwise false
    */
    public static function appendFile($file, $data) {
        if (!file_exists($file)) {
            throw new CoreException("Unable to append to file: " . $file . " as file does not exist");
        }

        if (!is_writeable($file)) {
            throw new CoreException("Unable to append to file: " . $file . " as file is not writable");
        }

        if (function_exists('file_put_contents')) {
            if (file_put_contents($file, $data, FILE_APPEND) !== false) {
                return true;
            }

            return false;
        }

        $fh = fopen($file, 'a+');

        if (!$fh) {
            return false;
        }

        if (fwrite($fh, $data) === false) {
            fclose($fh);
            return false;
        }

        return fclose($fh);
    }

    /**
    * List All
    * List All files and directories
    *
    * @access public
    * @param string $dir        Directory to list all the things in
    * @param bool   $recursive  Recursivly scan. (optional default is false)
    * @return array containing the files and directories found
    */
    public static function listAll($dir, $recursive=false) {
        $list = array(
            'dirs' => self::listDirs($dir, $recursive),
            'files' => self::listFiles($dir, $recursive),
        );

        return $list;
    }

    /**
    * List Dirs
    * List Directories
    *
    * @access public
    * @param sting  $dir        Directory to scan
    * @param bool   $recursive  Recursivly scan. (optional default is false)
    * @param array  $dirs       Array of found directories. (optional default is empty array)
    * @return array of found directories
    */
    public static function listDirs($dir, $recursive=false, $dirs=array()) {
        $path = realpath($dir);
        $items = scandir($path);

        foreach ($items as $item) {
            if (is_dir($path . '/' . $item) && $item != '.' && $item != '..') {
                $dirs[] = $path . '/' . $item;

                if ($recursive) {
                    $dirs = self::listDirs($path . '/' . $item, $recursive, $dirs);
                }
            }
        }

        return $dirs;
    }

    /**
    * List Files
    * List Files
    *
    * @access public
    * @param sting  $dir        Directory to scan
    * @param bool   $recursive  Recursivly scan. (optional default is false)
    * @param array  $files      Array of found files. (optional default is empty array)
    * @return array of found files
    */
    public static function listFiles($dir, $recursive=false, $files=array()) {
        $path = realpath($dir);
        $items = scandir($path);

        foreach ($items as $item) {
            if (is_file($path . '/' . $item) && $item != '.' && $item != '..') {
                $files[] = $path . '/' . $item;
            } else if (is_dir($path . '/' .$item) && $recursive && $item != '.' && $item != '..') {
                $files = self::listFiles($path . '/' . $item, $recursive, $files);
            }
        }

        return $files;
    }

    /**
    * Mkdir
    * Make a directory
    *
    * @see http://php.net/mkdir
    * @access public
    * @param string $dir        Path to new dir
    * @param int    $chmod      Mode/chmod permissions for new file. (optional default is 0755)
    * @param bool   $recursive  MAke directories recursivly
    * @return bool true on sucess, otherwise false
    */
    public static function mkdir($dir, $chmod=0755, $recursive=false) {
        if (!is_writeable(dirname($dir))) {
            throw new CoreException("Directory: " . dirname($dir) . " is not writeable, unable to create new directory");
        }

        return mkdir($dir, $chmod, $recursive);
    }

    /**
    * Find
    * Find a file or pattern
    *
    * @see http://php.net/glob
    * @access public
    * @param string $path       Path to search in
    * @param string $pattern    Glob pattern
    * @return array for found items
    */
    public static function find($path, $pattern) {
        return glob($path . $pattern);
    }
}
?>
