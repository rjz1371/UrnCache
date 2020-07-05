<?php
/**
 * UrnCache Class
 * API Documentation:
 * @author    Reza jabbari
 * @since     20.11.2018
 * @copyright Reza jabbari - rezajabbari.com
 * @version   1.0.0
 * @license   BSD http://www.opensource.org/licenses/bsd-license.php
 */

class UrnCache {
	
	private $patch;
	
	/**
	 * UrnCache constructor.
	 * @param string $patch
	 */
	public function __construct($patch = '') {
		$this->patch = ($patch == '') ? getcwd() : $patch;
		$this->patch = rtrim( $this->patch, '/' );
	}
	
	/**
	 * Store cache
	 * @param        $cacheName
	 * @param        $data
	 * @param int    $expireMin
	 * @param string $group
	 * @return bool
	 */
	public function put($cacheName, $data, $expireMin = 0, $group = '') {
		if ( count( $data ) == 0 or $data == '' or $data == null ) {
			return false;
		}
		$expireMin = intval( $expireMin );
		$cacheName = trim( $cacheName );
		$group = trim( $group );
		$cacheName = preg_replace( '/[^a-zA-Z0-9\-]/', '-', $cacheName );
		$cacheData[ 'data' ] = serialize( $data );
		$cacheData[ 'expire' ] = 0;
		$filename = '';
		$cacheDir = $this->patch . '/UrnCache';
		if ( !file_exists( $cacheDir ) ) {
			mkdir( $cacheDir );
		}
		if ( $group != '' ) {
			$cacheDir .= '/' . preg_replace( '/[^a-zA-Z0-9_\-]/', '', $group );
		}
		if ( !file_exists( $cacheDir ) ) {
			mkdir( $cacheDir );
		}
		$files = scandir( $cacheDir );
		foreach ( $files as $file ) {
			if ( $file == '.' or $file == '..' or is_dir( $cacheDir . '/' . $file ) ) {
				continue;
			}
			$explode = explode( '_', $file );
			if ( $explode[ 0 ] == $cacheName ) {
				$filename = $cacheDir . '/' . $file;
				break;
			}
		}
		if ( $filename == '' ) {
			$microtime = str_replace( '.', '_', microtime( true ) );
			$filename = $cacheDir . '/' . $cacheName . '_' . $microtime . '_' . mt_rand( 10000, 999999 ) . '.UrnCache';
		}
		if ( $expireMin <> 0 ) {
			$cacheData[ 'expire' ] = strtotime( '+' . $expireMin . ' minute' );
		}
		file_put_contents( $filename, serialize( $cacheData ) );
		
		return true;
	}
	
	/**
	 * Retrieve cache
	 * @param        $cacheName
	 * @param string $group
	 * @return bool|mixed
	 */
	public function get($cacheName, $group = '') {
		$find = false;
		$cacheName = trim( $cacheName );
		$group = trim( $group );
		$cacheName = preg_replace( '/[^a-zA-Z0-9\-]/', '-', $cacheName );
		$filename = $this->patch . '/UrnCache';
		if ( $group != '' ) {
			$filename .= '/' . preg_replace( '/[^a-zA-Z0-9_\-]/', '', $group );
		}
		if ( !file_exists( $filename ) ) {
			return false;
		}
		$files = scandir( $filename );
		foreach ( $files as $file ) {
			if ( $file == '.' or $file == '..' or is_dir( $filename . '/' . $file ) ) {
				continue;
			}
			$explode = explode( '_', $file );
			if ( $explode[ 0 ] == $cacheName ) {
				$filename = $filename . '/' . $file;
				$find = true;
				break;
			}
		}
		if ( !$find ) {
			return false;
		}
		$data = unserialize( file_get_contents( $filename ) );
		if ( $data[ 'expire' ] <> 0 and $data[ 'expire' ] <= time() ) {
			unlink( $filename );
			
			return false;
		}
		
		return unserialize( $data[ 'data' ] );
	}
	
	/**
	 * Cache exists or not
	 * @param        $cacheName
	 * @param string $group
	 * @return bool
	 */
	public function has($cacheName, $group = '') {
		$find = false;
		$cacheName = trim( $cacheName );
		$group = trim( $group );
		$cacheName = preg_replace( '/[^a-zA-Z0-9\-]/', '-', $cacheName );
		$filename = $this->patch . '/UrnCache';
		if ( $group != '' ) {
			$filename .= '/' . preg_replace( '/[^a-zA-Z0-9_\-]/', '', $group );
		}
		if ( !file_exists( $filename ) ) {
			return false;
		}
		$files = scandir( $filename );
		foreach ( $files as $file ) {
			if ( $file == '.' or $file == '..' or is_dir( $filename . '/' . $file ) ) {
				continue;
			}
			$explode = explode( '_', $file );
			if ( $explode[ 0 ] == $cacheName ) {
				$filename = $filename . '/' . $file;
				$find = true;
				break;
			}
		}
		if ( !$find ) {
			return false;
		}
		$data = unserialize( file_get_contents( $filename ) );
		if ( $data[ 'expire' ] <> 0 and $data[ 'expire' ] <= time() ) {
			unlink( $filename );
			
			return false;
		}
		
		return $find;
	}
	
	/**
	 * Delete cache
	 * @param        $cacheName
	 * @param string $group
	 * @return bool
	 */
	public function delete($cacheName, $group = '') {
		$cacheName = trim( $cacheName );
		$group = trim( $group );
		$cacheName = preg_replace( '/[^a-zA-Z0-9\-]/', '-', $cacheName );
		$filename = $this->patch . '/UrnCache';
		if ( $group != '' ) {
			$filename .= '/' . preg_replace( '/[^a-zA-Z0-9_\-]/', '', $group );
		}
		if ( !file_exists( $filename ) ) {
			return false;
		}
		$files = scandir( $filename );
		foreach ( $files as $file ) {
			if ( $file == '.' or $file == '..' or is_dir( $filename . '/' . $file ) ) {
				continue;
			}
			$explode = explode( '_', $file );
			if ( $explode[ 0 ] == $cacheName ) {
				$filename = $filename . '/' . $file;
				unlink( $filename );
				
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Delete cache in special group
	 * @param string $group
	 * @return bool
	 */
	public function deleteByGroup($group = '') {
		$group = trim( $group );
		$filename = $this->patch . '/UrnCache';
		if ( !file_exists( $filename ) ) {
			return false;
		}
		if ( $group == '' ) {
			$files = array_diff( scandir( $filename ), ['.', '..'] );
			foreach ( $files as $file ) {
				if ( is_dir( $filename . '/' . $file ) ) {
					continue;
				}
				unlink( $filename . '/' . $file );
			}
			
			return true;
		}
		$files = array_diff( scandir( $filename ), ['.', '..'] );
		foreach ( $files as $file ) {
			if ( is_file( $filename . '/' . $file ) ) {
				continue;
			}
			if ( $file == $group ) {
				$currentDirFiles = array_diff( scandir( $filename . '/' . $file ), ['.', '..'] );
				foreach ( $currentDirFiles as $f ) {
					unlink( $filename . '/' . $group . '/' . $f );
				}
				rmdir( $filename . '/' . $group );
				
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Delete all cache files and folders
	 * @return bool
	 */
	public function deleteAll() {
		$filename = $this->patch . '/UrnCache';
		if ( !file_exists( $filename ) ) {
			return false;
		}
		$files = array_diff( scandir( $filename ), ['.', '..'] );
		foreach ( $files as $file ) {
			if ( is_file( $filename . '/' . $file ) ) {
				unlink( $filename . '/' . $file );
			} else {
				$currentDirFiles = array_diff( scandir( $filename . '/' . $file ), ['.', '..'] );
				foreach ( $currentDirFiles as $f ) {
					unlink( $filename . '/' . $file . '/' . $f );
				}
				rmdir( $filename . '/' . $file );
			}
		}
		
		return true;
	}
	
	/**
	 * Get UrnCache directory patch
	 * @return string
	 */
	public function getUrnCachePath() {
		return $this->patch;
	}
	
	/**
	 * Set UrnCache directory patch
	 * @param $patch
	 */
	public function setUrnCachePath($patch) {
		$this->patch = rtrim( $patch, '/' );
	}
}
