<!--EDIT 2.12.12 -->
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Toolbox</title>
                     
                <link rel="stylesheet" href="style.css">
    </head>
    <body style="background-color:#F5F5F5; font-family: 'Helvetica', Arial, sans-serif; max-width:600px; margin: 0 auto;">
<h2 style="padding-top:50px; color:#666;">Clear Read API - Toolbox</h2> 

<form name="form" method="post" action="toolbox"> 
    <label for="url"><b>Enter URL:   </b></label><input style="width:100%; height:30px;" type="text" name="url" value="<?php echo $_POST['url']?>" />
    <ds><input value="id" name="id" type="submit"/><input type="submit" name="delete" value="delete"/><input value="raw" name="raw" type="submit"/><input value="cached" name="cached" type="submit"/></ds> 
    </form> 
    <hr/>
<div class="content">
<?php echo '<br /><br />';
if ($_POST['url'] != '') {

if ($_POST['delete']) {
$url = md5($_POST['url']);
$id = substr($url, 0, 3);
if (@unlink('buffer/'.$id.'/'.$url.'.clr')) {

echo 'Deleted: '.$url;

} else { echo 'No such file<br /><br /> URL:'.$_POST['url']; }

} else if ($_POST['id']) {

echo md5($_POST['url']).'<br /> URL:'.$_POST['url'];

} else if ($_POST['raw']) {

$xml = simplexml_load_file('http://api.thequeue.org/v1/fivefilters/v3/makefulltextfeed.php?url='.rawurlencode($_POST['url']));
echo '<h2>'.$xml->channel->item->title.'</h2>';
echo '<small>'.$xml->channel->item->pubDate.'</small>';
echo $xml->channel->item->description;

} else if ($_POST['cached']) {
echo '<small>API Reference: <a target="_blank" href="http://api.thequeue.org/v1/clear?url='.$_POST['url'].'">http://api.thequeue.org/v1/clear?url='.$_POST['url'].'</a></small>';
$xml = simplexml_load_file('http://api.thequeue.org/v1/clear?url='.$_POST['url']);
echo '<h2>'.$xml->channel->item->title.'</h2>';
echo '<small>'.$xml->channel->item->pubDate.' '.$xml->channel->author.'</small>';
echo $xml->channel->item->description;

}

}

?>
</div>

<div><small><small><a href="http://www.instapaper.com/bodytext">More Patters from Instapaper</a></small></small></div>

</body>
</html>



