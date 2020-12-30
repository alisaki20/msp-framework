<?php


namespace MSPFramework;


/**
 * Class Utilities
 * @package MSPFramework
 *
 * some useful function
 */
class Utilities {
    /**
     * get url of directory
     *
     * @param $dir string the directory to get its url. use __DIR__ variable should be enough.
     *
     * @return string|void
     */
    public static function get_dir_url( $dir )
    {
        return site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', self::get_dir_path( $dir ) ) );
    }

    /**
     * get path of directory
     *
     * @param $dir string directory to get its path. use __DIR__ variable should be enough.
     *
     * @return string
     */
    public static function get_dir_path( $dir )
    {
        return trailingslashit( str_replace( '\\', '/', realpath( $dir ) ) );
    }
}