<?php

/*
 * %LICENSE% - see LICENSE
 *
 * $Id: class.servicewebrss.php,v 1.3 2013-01-14 21:04:52 dkolev Exp $
 */

/**
 * @package VESHTER
 *
 */


/**
 * Web Service
 *
 * @version $Revision: 1.3 $
 * @package VESHTER
 *
 */

class CServiceWebRSS extends CServiceWeb
{
    function __construct() 
    {
        parent::__construct();
        $this->SetVersion('$Revision: 1.3 $');
    }
    
    function __destruct() 
    {
        parent::__destruct();
    }
}



/*

<?xml version="1.0"?>
<rss version="2.0">
<channel>
<title>Lift Off News</title>
<link>http://liftoff.msfc.nasa.gov/</link>
<description>Liftoff to Space Exploration.</description>
<language>en-us</language>
<pubDate>Tue, 10 Jun 2003 04:00:00 GMT</pubDate>
<lastBuildDate>Tue, 10 Jun 2003 09:41:01 GMT</lastBuildDate>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>Weblog Editor 2.0</generator>
<managingEditor>editor@example.com</managingEditor>
<webMaster>webmaster@example.com</webMaster>
<ttl>5</ttl>

<item>
<title>Star City</title>
<link>http://liftoff.msfc.nasa.gov/news/2003/news-starcity.asp</link>
<description>How do Americans get ready to work with Russians aboard the
International Space Station? They take a crash course in culture, language
and protocol at Russia's Star City.</description>
<pubDate>Tue, 03 Jun 2003 09:39:21 GMT</pubDate>
<guid>http://liftoff.msfc.nasa.gov/2003/06/03.html#item573</guid>
</item>

<item>
<title>Space Exploration</title>
<link>http://liftoff.msfc.nasa.gov/</link>
<description>Sky watchers in Europe, Asia, and parts of Alaska and Canada
will experience a partial eclipse of the Sun on Saturday, May 31st.</description>
<pubDate>Fri, 30 May 2003 11:06:42 GMT</pubDate>
<guid>http://liftoff.msfc.nasa.gov/2003/05/30.html#item572</guid>
</item>

<item>
<title>The Engine That Does More</title>
<link>http://liftoff.msfc.nasa.gov/news/2003/news-VASIMR.asp</link>
<description>Before man travels to Mars, NASA hopes to design new engines
that will let us fly through the Solar System more quickly.  The proposed
VASIMR engine would do that.</description>
<pubDate>Tue, 27 May 2003 08:37:32 GMT</pubDate>
<guid>http://liftoff.msfc.nasa.gov/2003/05/27.html#item571</guid>
</item>

<item>
<title>Astronauts' Dirty Laundry</title>
<link>http://liftoff.msfc.nasa.gov/news/2003/news-laundry.asp</link>
<description>Compared to earlier spacecraft, the International Space
Station has many luxuries, but laundry facilities are not one of them.
Instead, astronauts have other options.</description>
<pubDate>Tue, 20 May 2003 08:56:02 GMT</pubDate>
<guid>http://liftoff.msfc.nasa.gov/2003/05/20.html#item570</guid>
</item>
</channel>
</rss>

Including in XHTML
The following tag should be placed into the head of an XHTML document to provide a link to an RSS Feed.

<link href="rss.xml" rel="alternate" type="application/rss+xml" title="Sitewide RSS Feed" />

*/

/*
 *
 * Changelog:
 * $Log: class.servicewebrss.php,v $
 * Revision 1.3  2013-01-14 21:04:52  dkolev
 * Merge to prototype
 *
 * Revision 1.2.4.1  2011-11-25 22:17:14  dkolev
 * Cleaned up constructors. Imported new captcha functionality
 *
 * Revision 1.2  2010-07-04 18:32:38  dkolev
 * Sniff improvements
 *
 * Revision 1.1  2008-05-31 04:29:34  dkolev
 * Initial import
 *
 *
 *
 */

?>