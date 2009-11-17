<?php
/*
Plugin Name: MakeMeHappy
Plugin URI: http://blog.makemehappy.ro
Description: Integrate your MakeMeHappy wishlists into WP
Author: Andrei Daneasa
Version: 0.1
Author URI: http://mmh.ro/
*/

function show_content_MakeMeHappy($user, $list, $display, $pict, $desc)
{
  if ($user==-1) 
	echo "Pluginul nu a fost configurat";
  else {
	//check user first
	
	//if list, check list
	
	include('http://www.makemehappy.ro/Widgets/wordpress/'.$user.'/'.$list.'/'.$display.'/'.$pict.'/'.$desc);
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