<?php
/*
 * %LICENSE% - see LICENSE
*
* $Id: class.cachemanager.php,v 1.2 2013-01-14 21:04:52 dkolev Exp $
*/

/**
 * @package VESHTER
 *
 */

/**
 * Cache writer used by the cache manager
 *
 * @version $Revision: 1.2 $
 * @package VESHTER
 *
 */
class CCacheWriter extends CObject
{
    /**
     * Id for this document in the persistent cache
     * @var string
     */
    protected $cacheId;

    /**
     *
     * Whether the cache is valid
     * @var bool
     */
    protected $isCacheValid = false;

    /**
     * Timeout (in seconds) which will invalidate the persistent cache
     * @var int Seconds
     */
    protected $cacheTTL = 0;

    function __construct($cache_id, $ttl = 0)
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.2 $');

        if (CString::IsNullOrEmpty($cache_id))
        {
            throw new CExceptionNotInitialized('Cache name cannot be null');
        }

        $this->cacheId = $cache_id;

        if ($ttl < 0)
        {
            throw new CExceptionInvalidParameter('Cache TTL cannot be negative');
        }

        $this->cacheTTL = $ttl;
    }

    function __destruct()
    {
        parent::__destruct();
    }


    private function GetCachePath()
    {
        return CString::Format('%s%s.bin', CCacheManager::$persistentCacheDir, $this->cacheId);
    }

    function RestoreFromCache()
    {
        //reset the cache
        $this->isCacheValid = false;

        //only do this if there is a cache timeout specified
        if ($this->cacheTTL > 0)
        {
            $path = $this->GetCachePath();
            if ($fid = @fopen($path, 'r'))
            {
                if (flock($fid,LOCK_SH)) // acquire a shared lock
                {
                    $filesize = filesize($path);
                    if (file_exists($path) && $filesize > 0)
                    {
                        //the file cache is stale. modification occured outside the timeout threshold
                        if (time()-filemtime($this->GetCachePath()) > $this->cacheTTL)
                        {
                            $this->Notify('Cache file is stale');

                            return false;
                        }
                        else
                        {
                            $source = fread($fid, $filesize);
                            $this->persistentCacheIsValid = true;

                            @touch($path);

                            return $source;
                        }
                    }
                    else
                    {
                        $this->Notify('Cache file does not exist');

                        return false;
                    }

                    flock($fid,LOCK_UN); // release the lock
                }
                else
                {
                    $this->Warn('Cannot lock cache file');

                    return false;
                }
            }
            else
            {
                //no cache file to open
            }
        }
        else
        {
            $this->Notify('Cache is not requested');

            return false;
        }
    }

    function StoreInCache($source)
    {
        $path = $this->GetCachePath();

        $fid = @fopen($path, 'w');

        if (flock($fid,LOCK_EX)) // acquire an exlusive lock
        {
            $written = fwrite($fid,$source);

            flock($fid,LOCK_UN); // release the lock
            fclose($fid);

            //if there is content but nothing was written, throw an exception
            if (!CString::IsNullOrEmpty($source) && !$written)
            {
                throw new CExceptionInvalidData('Failed to write cache data');
            }
            else
            {
                //empty cache content
            }
        }
        else
        {
            $this->Warn('Cannot lock cache file');

            return false;
        }
        return true;
    }

    function RemoveFromCache()
    {
        $this->isCacheValid = false;
        @unlink($this->GetCachePath());
    }

    function IsCacheValid()
    {
        return $this->isCacheValid;
    }

    function SetCacheTimeout($timeout)
    {
        $this->cacheTTL = $timeout;
    }
}

/**
 * Cache manager for VESHTER sites
 *
 * @version $Revision: 1.2 $
 * @package VESHTER
 *
 */
class CCacheManager extends CObject
{
    /**
     * Persistent cache for documents
     * @var string
     */
    static public $persistentCacheDir = _PATH_TEMP;

    function __construct()
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.2 $');
    }

    function __destruct()
    {
        parent::__destruct();
    }

    protected function GenerateCacheName($cache_name, $is_global)
    {
        if ($is_global)
        {
            $key = CString::Format('VESHTER:%s:%s', CEnvironment::GetServerName(), $cache_name);
            $this->Notify(CString::Format('Generating global key equal to %s', $key));

        }
        else
        {
            $key = CString::Format("VESHTER:%s%s:%s:%s", CEnvironment::GetServerName(), CEnvironment::GetScriptVirtualName(), CEnvironment::GetContext(), $cache_name);
            $this->Notify(CString::Format('Generating local key equal to %s', $key));
        }
         
        //encrypt the key using sha1 encryption in case anyone gets a hold the session variables

        return sha1($key);
    }

    /**
     * Creates a local/page cache writer
     * @param $cache_name
     * @param $ttl
     * @return CCacheWriter
     */
    function CreateLocalLevelCacheWriter($cache_name, $ttl)
    {
        return new CCacheWriter($this->GenerateCacheName($cache_name, false), $ttl);
    }

    /**
     * Creates a global/site cache writer
     * @param $cache_name
     * @param $ttl
     * @return CCacheWriter
     */
    function CreateGlobalLevelCacheWriter($cache_name, $ttl)
    {
        return new CCacheWriter($this->GenerateCacheName($cache_name, true), $ttl);
    }

}


/*
 *
* Changelog:
* $Log: class.cachemanager.php,v $
* Revision 1.2  2013-01-14 21:04:52  dkolev
* Merge to prototype
*
* Revision 1.1.2.4  2012-11-14 15:49:03  dkolev
* Made cache manager less rigid
*
* Revision 1.1.2.3  2012-11-14 15:45:58  dkolev
* Made cache manager less rigid
*
* Revision 1.1.2.2  2012-02-19 20:53:23  dkolev
* Minor fix for caching when there is no content to cache
*
* Revision 1.1.2.1  2011-11-25 22:17:15  dkolev
* Cleaned up constructors. Imported new captcha functionality
*
*
*
*/

?>