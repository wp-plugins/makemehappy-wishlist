<?php
/*
Plugin Name: MakeMeHappy
Plugin URI: http://blog.makemehappy.ro
Description: Integrate your MakeMeHappy wishlists into WP
Author: MakeMeHappy
Version: 0.3
Author URI: http://mmh.ro/
*/

function show_content_MakeMeHappy($user, $list, $display, $pict, $desc)
{
  if ($user==-1) 
	echo "Pluginul nu a fost configurat";

  else {

	$base_url='http://www.makemehappy.ro/';
	$doc=new DOMDocument();
	$doc->load($base_url."Widgets/wordpress/".$user."/".$list);
	
	$listname = $doc->getElementsByTagName("title");
	$listname = $listname->item(0)->nodeValue;
	$link = $doc->getElementsByTagName("link");
	$link = $link->item(0)->nodeValue;
	echo '<p><a href="'.$link.'" target="_blank">'.$listname.'</a><br>';
	
	$description = $doc->getElementsByTagName("description");
	$description = $description->item(0)->nodeValue;
	
	if ($desc==1)
		echo $description;
	
	echo '</p><BR>';
	
	$gifts = $doc->getElementsByTagName("item");
	$cnt=0;
	foreach($gifts as $gift){
		if ($cnt<$display) {
			$gift_mmh_link = $gift->getElementsByTagName("item_link");
			$gift_mmh_link = $gift_mmh_link->item(0)->nodeValue;
			
			if ($pict==1) {
				$gift_pict = $gift->getElementsByTagName("image");
				$gift_pict = $gift_pict->item(0)->nodeValue;
				if($gift_pict!="") 
					echo '<a href="'.$gift_mmh_link.'">';
				echo '<img src="'.$base_url.'uploads/gifts/'.$gift_pict.'" width="50" height="50" border="1" align="left" style="margin-right:5px; border-color:#666666;" />';
				if($gift_pict!="") 
					echo '</a>';
			}
			$gift_title = $gift->getElementsByTagName("title");
			$gift_title = $gift_title->item(0)->nodeValue;
	        echo '<h4><a href="'.$gift_mmh_link.'" class="blue" target="_blank">'.$gift_title.'</a></h4>';
			
			$gift_price = $gift->getElementsByTagName("price");
			$gift_price = $gift_price->item(0)->nodeValue;
			echo '<p>Costa: <strong><span>'.$gift_price.'</span></strong></p>';
			
			$gift_link = $gift->getElementsByTagName("link");
			$gift_link = $gift_link->item(0)->nodeValue;
	        if (!empty($gift_link)) {
				echo '<p>Il gasesti la: <strong><a href="'.ereg_replace('@#', '&', $gift_link).'" target="_blank">';
				$idx1=strpos($gift_link, '//');
				$idx2=strpos($gift_link, '/', $idx1+2);
				if ($idx1===false) $idx1=-2;
				if ($idx2===false) $idx2=strlen($gift_link);
				echo substr($gift_link, $idx1+2, $idx2-$idx1-2);
				echo '</a></strong></p>';
			}
			
			if ($desc==1) {
				$gift_desc = $gift->getElementsByTagName("description");
				$gift_desc = $gift_desc->item(0)->nodeValue;
				echo '<p><strong>Descriere: </strong>'.substr($gift_desc, 0,50);
				if(strlen($gift_desc)>50) 
					echo '<a href="'.$base_url.$username.'/'.$list.'" class="blue" target="_blank">[mai mult]</a>';
			}
			echo '<div style="display:block; height:1px; background-color:#dddddd; overflow:hidden;"></div>';
		}
		$cnt++;
	}
	
  }
}

function widget_MakeMeHappy($args) {
  extract($args);

  $options = get_option("widget_MakeMeHappy");
  if (!is_array( $options ))
        {
                $options = array(
      'title' => 'Wishlist MakeMeHappy',
	  'username' => -1,
	  'list' => -1, 
	  'display' => -1, 
	  'show_pict' => 1, 
	  'show_desc' => 1
      );
  }      

  echo $before_widget;
    echo $before_title;
      echo $options['title'];
    echo $after_title;

    //Widget Content
    show_content_MakeMeHappy($options['username'], $options['list'], $options['display'], $options['show_pict'], $options['show_desc']);
  echo $after_widget;
}

function MakeMeHappy_control()
{
  $options = get_option("widget_MakeMeHappy");
  
  if (!is_array( $options ))
        {
                $options = array(
      'title' => 'Wishlist MakeMeHappy',
	  'show_pict' => 1,
	  'show_desc' => 1
      );
  }    

  if ($_POST['MakeMeHappy-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['MakeMeHappy-WidgetTitle']);
    $options['username'] = htmlspecialchars($_POST['MakeMeHappy-Username']);
    $options['list'] = htmlspecialchars($_POST['MakeMeHappy-list-url']);
    $options['display'] = htmlspecialchars($_POST['MakeMeHappy-display']);
    if (!isset($_POST['MakeMeHappy-show-pict']))
		$options['show_pict']=1;
	else 
		$options['show_pict']=0;
    if (!isset($_POST['MakeMeHappy-show-desc']))
		$options['show_desc']=1;
	else 
		$options['show_desc']=0;
    update_option("widget_MakeMeHappy", $options);
  }
?>
  <p>
    <label for="MakeMeHappy-WidgetTitle">Titlu: </label>
    <input type="text" id="MakeMeHappy-WidgetTitle" name="MakeMeHappy-WidgetTitle" value="<?php echo $options['title'];?>" /><br>
    <label for="MakeMeHappy-Username">Utilizator: </label>
    <input type="text" id="MakeMeHappy-Username" name="MakeMeHappy-Username" value="<?php echo $options['username'];?>" /><br>
    <label for="MakeMeHappy-list-url">URL lista: </label>
    <input type="text" id="MakeMeHappy-list-url" name="MakeMeHappy-list-url" value="<?php echo $options['list'];?>" /><br>
    <label for="MakeMeHappy-display">Maxim obiecte afisate: </label>
    <input type="text" id="MakeMeHappy-display" name="MakeMeHappy-display" size="5" value="<?php echo $options['display'];?>" /><br>
    <label for="MakeMeHappy-show-pict">Ascunde poza produs: </label>
    <input type="checkbox" name="MakeMeHappy-show-pict" id="MakeMeHappy-show-pict" value="0" <?php if($options['show_pict']==0) echo "checked='checked'";?> /><br>
    <label for="MakeMeHappy-show-desc">Ascunde descrierea: </label>
    <input type="checkbox" name="MakeMeHappy-show-desc" id="MakeMeHappy-show-desc"  value="0" <?php if($options['show_desc']==0) echo "checked='checked'";?> />
    <input type="hidden" id="MakeMeHappy-Submit" name="MakeMeHappy-Submit" value="1" />
  </p>
<?php
}

function MakeMeHappy_init()
{
  register_sidebar_widget(__('MakeMeHappy'), 'widget_MakeMeHappy');
  register_widget_control(   'MakeMeHappy', 'MakeMeHappy_control', 200, 200 );    
}
add_action("plugins_loaded", "MakeMeHappy_init");
?>