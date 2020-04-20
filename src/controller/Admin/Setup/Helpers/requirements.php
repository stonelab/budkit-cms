<?php

namespace Budkit\Cms\Controller\Admin\Setup\Helpers;

use Budkit\Filesystem\Directory;

/**
 * Performs system requirements test
 *
 * @category  Application
 * @package   Data Model
 * @license   http://www.gnu.org/licenses/gpl.txt.  GNU GPL License 3.01
 * @version   1.0.0
 * @since     Jan 14, 2012 4:54:37 PM
 * @author    Livingstone Fultang <livingstone.fultang@stonyhillshq.com>
 * 
 */
final class Requirements {


    /**
     * Test file upload size limits
     * @todo Implement file upload system requirements test at install
     */
    public function testFileUploads() {
        
    }

    /**
     * Test for available memore
     * @todo Implement system memory test at install
     */
    public function testMemory() {
        
    }

    /**
     * Test float?
     */
    public function testFloat() {
        
    }

    /**
     * Checks required modules
     * 
     * @param string $name
     * @param array $directive
     * @return boolean 
     */
    public function testModule($name, $directive = array()) {

        $return = array(
            "title" => $directive['title'], "name" => $name, "current" => "", "test" => false
        );

        if (is_array($directive)) {
            //If the extension is loaded
            if (!extension_loaded($name)) {
                $return["current"] = t("Not Loaded");
                //If we require this module loaded, then fail
                if ($directive["loaded"]) {
                    $return["test"] = "Failed";
                }
                //If we require this module to be installed
                if ($directive["installed"] && function_exists('dl')) {
                    if (!dl($name)) {
                        $return["test"] = "Failed";
                        $return["current"] = t("Not Installed");
                    }
                }
            } else {
                $return["current"] = _("Loaded");
                if ($directive["loaded"]) {
                    $return["test"] = "Passed";
                }
            }

            //@TODO If we have alternative modules
            if (!$return['test'] && isset($directive['alternate'])) {
                //$altName = 
            }
        }

        return $return;
    }

    /**
     * Test for folder permissions
     * @param string $path
     * @param array $directive
     * @return boolean
     */
    public function testServerVersions($name, $directive = array()) {

        $return = array(
            "title" => $name, "name" => $directive["minimal"].$directive["version"], "current" => $directive["current"], "test" => "Failed"
        );

        if($this->checkVersion($directive)){
            $return["test"] = "Passed";
        }

        return $return;
    }


    /**
     * Test for folder permissions
     * @param string $path
     * @param array $directive
     * @return boolean
     */
    public function testFolderPermissions($path, $directive = array()) {

        $directory = new Directory();

        //Test install directory is writable, readable
        //Test we are not trying to overide an installation
        $return = array(
            "title" => $directive['path'], "name" => "-",  "current" => t("Not Writable"), "test" => "Failed"
        );

        if (is_array($directive)) {
            //If the extension is loaded
            $return['status'] = ((bool) $directive['writable']) ? "Writable" : "Not Writable";

            if ($directory->isWritable($path) && (bool) $directive['writable']) {
                $return['current'] = t("Is Writable");
                $return['test'] = "Passed";
            } elseif (!$directory->isWritable($path) && !(bool) $directive['writable']) {
                $return['test'] = "Passed";
            }

            if ($directory->isWritable($path)) {
                $return['current'] = t("Is Writable");
            }
        }
        //Return test result;
        return $return;
    }

    /**
     * Test PHP Directives before install
     * 
     * @param string $name
     * @param array $directive
     * @return boolean 
     */
    public function testDirective($name, $directive = array()) {

        $return = array(
            "title" => $name, "status" => (!$directive['status']) ? 'Off' : 'On', "current" => "", "test" => true
        );

        //For now we can only check boolean variables
        if (isset($name) && !empty($name) && is_array($directive)) {

            $return['current'] = ini_get($name);
            $setting = ($return['current'] == 0 || strtolower($return['current']) === 'off' || empty($return['current']) || !$return['current']) ? false : true;

            //Test
            if ($directive['status'] <> $setting) {
                $return['test'] = false;
            }

            //Literalize
            $return['current'] = (!$setting) ? _t('Off') : _t('On');
        }

        return $return;
    }

    /**
     * Converts human readable file size (e.g. 10 MB, 200.20 GB) into bytes. 
     * 
     * @param string $str 
     * @return int the result is in bytes 
     * @author Livingstone Fultang <livingstone@budkit.org> modified to include M, K, KiB etc...
     */
    public static function sizeToBytes($string) {
        $bytes = 0;
        $bytesTable = array(
            'B' => 1,
            'K' => 1024,
            'KB' => 1024,
            'M' => 1024 * 1024,
            'MB' => 1024 * 1024,
            'G' => 1024 * 1024 * 1024,
            'T' => 1024 * 1024 * 1024 * 1024,
            'P' => 1024 * 1024 * 1024 * 1024 * 1024,
        );

        preg_match('/(\d+)(\w+)/', $string, $units);
        $unit   = strtoupper($units[2]);
        $bytes  = floatval($units[1]);
        if (array_key_exists($unit, $bytesTable)) {
            $bytes *= $bytesTable[$unit];
        }

        $bytes = intval(round($bytes, 2));

        return $bytes;
    }

    /**
     * Tests required system resource limits
     * 
     * @param type $name
     * @param type $directive
     */
    public function testLimit($name, $directive = array()) {
        $operator = array("equals", "greater", "less");
        $return = array(
            "title" => $name, "name"=>$directive['status'], "current" => "", "status" => $directive['status'], "test" => "Failed"
        );
        if (!isset($directive['compare']) || !in_array($directive['compare'], $operator))
            return $return;
        //For now we can only check boolean variables
        if (isset($name) && !empty($name) && is_array($directive)) {
            $return['current'] = ini_get($name);
            if(isset($directive['type'])&& $directive['type']=="bytesize"){
                $current = static::sizeToBytes( $return['current'] );
                $status = static::sizeToBytes( $return['status'] );
            }
            $return['test'] = false;
            switch ($directive['compare']):
                case "equals":
                    $return['test'] = (intval($current) == intval($status)) ? "Passed" : "Failed";
                    break;
                case "greater":
                    $return['test'] = (intval($current) > intval($status)) ?  "Passed" : "Failed";
                    break;
                case "less":
                    $return['test'] = (intval($current) < intval($status)) ?  "Passed" : "Failed";
                    break;
            endswitch;
        }
        return $return;
    }

    /**
     * Checks the current version 
     * 
     * @param string $component
     * @return boolean
     */
    public function checkVersion($component) {

        return version_compare($component['current'], $component['version'], $component['minimal']);
    }


}

