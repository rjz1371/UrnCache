# UrnCache
php cache class with grouping capability

**Quick Start**

    <?php
        require_once 'classes/GjCache.class.php';
         
        // cache folder will be in the project root directory.
        $cache = new GjCache();
         
        // cache folder will be in "my-folder" in the project root directory.
        $cache = new GjCache( getcwd() . '/' . 'my-folder' );
        
        // Put cache data without expire time ( forever cache ) and grouping.
        $data = [['username' => 'rjz1371', 'age' => 25],
                 ['username' => 'reza', 'age' => 20],
                 ['username' => 'alex', 'age' => 56]];
        $cache->put( 'cache-name', $data, 0 );
        
        // Put cache data with expire time ( expire after 120 minute ).
        $data = [['username' => 'rjz1371', 'age' => 25],
                 ['username' => 'reza', 'age' => 20],
                 ['username' => 'alex', 'age' => 56]];
        $cache->put( 'cache-name', $data, 120 );
        
        // Put cache data with grouping.
        $data = [['username' => 'rjz1371', 'age' => 25],
                 ['username' => 'reza', 'age' => 20],
                 ['username' => 'alex', 'age' => 56]];
        $cache->put( 'cache-name', $data, 0, 'special-group' );
        
        // Checking cache exists or not ( $result may be true or false ).
        $result = $cache->has('cache-name');
        
        // Checking cache exists in "special-group" or not ( $result may be true or false ).
        $result = $cache->has('cache-name', 'special-group');
        
        // Retrive cache data.
        $result = $cache->get( 'cache-name' );
        
        // Retrive cache data in "special-group".
        $result = $cache->get( 'cache-name', 'special-group' );
        
        // Delete cache.
        $result = $cache->delete( 'cache-name' );
        
        // Delete cache in "special-group".
        $result = $cache->delete( 'cache-name', 'special-group' );
        
        // Delete all cache in "special-group".
        $result = $cache->deleteByGroup( 'special-group' );
        
        // Delete all cache files and group folders.
        $result = $cache->deleteAll();
        
        // Set path for cache folder.
        $result = $cache->setGjCachePath( getcwd() . '/' . 'my-folder' );
        
        // get cache folder patch.
        $result = $cache->getGjCachePath();
    ?>
