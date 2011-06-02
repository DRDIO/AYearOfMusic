<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en"> 
    <head> 
      <meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> 
      <meta http-equiv="Content-Language" content="EN" /> 

<?php if(!strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')): ?>

    <style type="text/css" media="screen">
      body {
        font: 13px Arial;
        background: #E5FAFF;
        color: #776045;
        margin: 4px; }
      .header {
        font-size: 72px;
        margin: 4px;
        font-weight: bold;
        height: 64px;
        overflow: hidden; }
      .content a {
        display: block;
        text-decoration: none;
        float: right;
        background: #0092B2;
        color: white;
        margin: 4px;
        padding: 4px 6px;
        border-radius: 4px;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px; }
      .content a:hover {
        background: #A8C545 !important; }
      .content strong, .content span, .content em {
        display: block; }
      .content strong {
        font-size: 15px; }
      .content em {
        font-size: 12px; }
      .content br {
        font-size: 0.01px;
        clear: both;
        height: 0; }
      .footer {
        font-size: 11px;
        font-weight: normal;
        margin: 4px 4px 12px 4px;
        display: block;
        text-align: right; }
      .footer a {
        color: #776045;
        border-bottom: 1px dotted #776045;
        text-decoration: none;
      }
  
<?php else: ?>
  
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">

    <style type="text/css" media="only screen and (max-device-width: 480px)">

      body {
        font: 13px Arial;
        background: #E5FAFF;
        color: #776045;
        margin: 4px; }
      .header {
        font-size: 18px;
        margin: 4px;
        font-weight: bold; }
      .content a {
        display: block;
        text-decoration: none;
        background: #0092B2;
        color: white;
        padding: 4px 6px;
        border-radius: 4px;
	margin-bottom: 4px;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px; }
      .content a:hover {
        background: #A8C545 !important; }
      .content strong, .content span, .content em {
        display: block; }
      .content strong {
        font-size: 15px; }
      .content em {
        font-size: 12px; }
      .content br {
        font-size: 0.01px;
        clear: both;
        height: 0; }
      .footer {
        font-size: 11px;
        font-weight: normal;
        margin: 0px 4px 12px 4px;
        display: block;
        text-align: right; }
      .footer a {
        color: #776045;
        border-bottom: 1px dotted #776045;
        text-decoration: none;
      }

<?php endif; ?>

    </style>
  </head>
  <body>

<?php

$serverName = $_SERVER['SERVER_NAME'];
$serverSub  = substr($serverName, 0, -strlen('.ayearofmusic.com'));
$search     = str_replace(array(' ', '.', '_', '-'), '+', urldecode($serverSub));

$searchMatches = array();

if ($search) {
    $searchUrl = 'http://www.pollstar.com/eventSearch.aspx?SearchBy=' . $search;
    $rawSearch = file_get_contents($searchUrl);
    $pattern   = '/resultsCity\.aspx\?ID=(\d+)/';
    preg_match_all($pattern, $rawSearch, $searchMatches, PREG_SET_ORDER);
    if (sizeof($searchMatches) == 0) {
        $headers = get_headers($searchUrl);
        foreach ($headers as $header) {
            if (strpos($header, 'Location:') === 0) {
                $rawSearch = $header;
                $pattern = '/resultsCity\.aspx\?ID=(\d+)/';
                preg_match_all($pattern, $rawSearch, $searchMatches, PREG_SET_ORDER);
                break;
            }
        }  
    }
}

if (sizeof($searchMatches) == 0) {
    $searchMatches[0][1] = 7191;
}

foreach ($searchMatches as $city) {
$id = $city[1];

$feedUrl = "http://www.pollstar.com/pollstarRSS.aspx?feed=city&id=" . $id;
$rawFeed = file_get_contents($feedUrl);
$xml4 = new SimpleXmlElement($rawFeed);

$pattern = "/<tr.*?(\d{1,2}\/\d{1,2}\/\d{4}).*?ID=(\d+).*?>(.*?)<.*?ID=(\d+).*?>(.*?)<.*?tr>/i";
$subject = $xml4->channel->item->description;
if ($subject == 'No concert dates found.') {
    continue;
}

preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER);
$local   = $xml4->channel->title;
?>

    <div class="header">A Year Of <?php echo $local; ?> Music &#9835;</div>
    <div class="content">

<?php

foreach($matches as $match) {
    $match[3] = str_replace('&', '&amp;', $match[3]);
    $match[5] = str_replace('&', '&amp;', $match[5]);

    echo '<a href="http://www.pollstar.com/resultsArtist.aspx?ID=' . $match[2] . '" target="blank"><strong>' . $match[3]. '</strong><span>' . $match[5] . '</span><em>' . $match[1] . '</em></a>';
}

break;

}

?>
    <br />
    </div>

    <div class="footer">
      Data by <a href="http://pollstar.com/" target="_blank">Pollstar</a>.
      Design by <a href="http://dierobotdie.com/" target="_blank">Die Robot Die</a>.</div>
      
    <script type="text/javascript">
	    document.write(unescape("%3Cscript src='http://www.google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	    var pageTracker = _gat._getTracker("UA-723316-16"); pageTracker._trackPageview();
    </script>
  </body>
</html>
