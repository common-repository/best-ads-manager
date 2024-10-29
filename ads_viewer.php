<?php ob_start();
/*
Plugin Name: Ads Manager
Description: The best way to control your ads on your website with many feature, please read the description and the support file in the control panel for more information.
Plugin uri:  https://twitter.com/walid_naceri
Version: 1.0
Author: Walid Naceri
Author uri: http://coder-dz.com
*/

register_activation_hook(__FILE__,'wa_create_database');
function wa_create_database()
{
global $wpdb;

$wpdb->query("CREATE TABLE IF NOT EXISTS ads_manager(id int(10) not null primary key auto_increment,
				         ads_name varchar(200) not null,
				         ads_img varchar(500) not null,
				         ads_link varchar(500) not null,
				         state varchar(4) not null,
				         time int(10) not null,
				         condition_ads varchar(200) not null)");


}// close function wa_create_database


add_action('widgets_init','wa_widget_initialize');
function wa_widget_initialize(){
register_widget('wa_imen');
}

class wa_imen extends WP_Widget
{
function wa_imen(){
$widget_ops  = array("classname"=>"imen","Description"=>"Ads Manager By coder-dz.com");
$control_ops = array("width"=>200000,"height"=>20000,"id_base"=>"walid");
$this->WP_Widget("walid","Ads Manager By coder-dz.com","Ads Manager By coder-dz.com",$widget_ops,$control_ops);
}// Close function imen

function widget($args,$instance)
{
extract($args);
$title = $instance['title'];

echo $before_widget;
echo $before_title.$title.$after_title;
$default_photo = "wp-content/plugins/ads_manager/photo/default.jpg";
global $wpdb;
$get_how_many_ads_on = $wpdb->query("SELECT * FROM ads_manager where state='on'");
$get_ads_on          = $wpdb->get_results("SELECT * FROM ads_manager where state='on'");


if($get_how_many_ads_on == 3)
{
foreach($get_ads_on as $ads){
echo "<a href='$ads->ads_link'><img src='$ads->ads_img' style='width: 280px;height:70px;'></img><br/>";
}
}

else if($get_how_many_ads_on == 2)
{
foreach($get_ads_on as $ads){
echo "<a href='$ads->ads_link'><img src='$ads->ads_img' style='width: 280px;height:70px;'></img><br/>";
}
echo "<a href='#'><img src='$default_photo' style='width: 280px;height:70px;'></img><br/>";
}

else if($get_how_many_ads_on == 1)
{
foreach($get_ads_on as $ads){
echo "<a href='$ads->ads_link'><img src='$ads->ads_img' style='width: 280px;height:70px;'></img><br/>";
}
echo "<a href='#'><img src='$default_photo' style='width: 280px;height:70px;'></img><br/>
<a href='#'><img src='$default_photo' style='width: 280px;height:70px;'></img><br/>
";
}
else{
echo "<a href='#'><img src='$default_photo' style='width: 280px;height:70px;'></img><br/>
<a href='#'><img src='$default_photo' style='width: 280px;height:70px;'></img><br/>
<a href='#'><img src='$default_photo' style='width: 280px;height:70px;'></img><br/>
";
}

echo $after_widget;

}// close class widget

function form($instance)
{
$defaults_param = array("title"=>"Ads On Coder-Dz.com");
$instance = wp_parse_args((array) $instance,$defaults_param);
?>

Title : <input id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title'];?>">

<?php
}// close function form
function update($new_instance,$old_instance)
{
$instance = $old_instance;
$instance['title'] = $new_instance['title'];
return $instance;
}// close function update




}// close class wa_imen
















































add_action('admin_menu','admin_initialize');

function admin_initialize()
{



add_menu_page('Add Your Advertisement','Add Ads','manage_options',__FILE__,'ads_admin');
}// close function admin_initialize

function ads_admin()
{
wp_enqueue_script('walid_settigns',plugins_url('includes/add_settings.js',__FILE__),array('jquery'));
?>
<center>
<form action="" method="POST">
<br/><br/><br/><br/><br/>
<table class="widefat" style="width:800px;"><tr><td><center><b>Add a New Advertisement</b></center></td></tr></table>
<table class="widefat" style="width:800px;"><tr><td>
<input type="text" id="count_name" maxlength="15" name="ads_name" value="Put a name for the Ads" size="50" style="background-color: Gainsboro ;color:green;font-weight:bold;border: 1px solid"> <b id="feedback_ads_name"></b>
<br/><br/>
<input type="text" name="ads_img" size="50" value="put a direct link for the banner of ads" style="background-color: Gainsboro ;color:green;font-weight:bold; border: 1px solid"> <b class="links_ads"></b>
<br/><br/>
<input type="text" name="ads_link" size="50" value="Put a url to redirect visitors when they click on the ads" style="background-color: Gainsboro ;color:green;font-weight:bold;border: 1px solid"> <b class="links_ads"></b>
<br/><br/>

<select class="state" style="width:120px; background-color: Gainsboro ;color:green;font-weight:bold;border: 1px solid;" name="state">
<option value="on">ON</option>
<option value="off">OFF</option>
</select> <b>ON: Means the ads will be displayed right now. | OFF: means the ads will be hidden.</b>

<br/><br/>
<select class="hour" style="width:120px; background-color: Gainsboro ;color:green;font-weight:bold;border: 1px solid;" name="hour">
<?php
for($i=1;$i<=72;$i++){
echo "<option value='$i'>$i Hour</option>";
}
?>
</select> <b>CountDown time before the ads will be closed.</b>

<br/><br/>
<select class="condition_ads" style="width:120px; background-color: Gainsboro ;color:green;font-weight:bold;border: 1px solid;" name="condition_ads">
<option value="delete">Delete</option>
<option value="re-activate">Re-Activate</option>
</select> <b class='ads_manager_choose'>Delete: Means the ads will be deleted automatically after it reached its estimated time</b>


<br/><br/>
<input type="submit" name="add" value="Add Advertisement" class="button-primary">
</td></tr></table>
</form>

</center>
<?php
global $wpdb;
if(isset($_POST['ads_name'], $_POST['ads_img'],$_POST['ads_link'],$_POST['state'],$_POST['hour'], $_POST['condition_ads']))
{
$ads_name       =    $_POST['ads_name'];
$ads_img        =    $_POST['ads_img'];
$ads_link       =    $_POST['ads_link'];
$state          =    $_POST['state'];
$hour           =    time()+($_POST['hour'] * 3600);
$condition_ads  =    $_POST['condition_ads'];
$query_state    =    $wpdb->query("SELECT * FROM ads_manager where state='on'");
if($ads_name =="" or $ads_name =="Put a name for the Ads")
{
echo "<center><div class='error' style='width: 785px;height: 50px'><b><br/>Please write a name for the ads</b></div></center>";
die();
}

else if($ads_img =="" or $ads_img =="put a direct link for the banner of ads")
{
echo "<center><div class='error' style='width: 785px;height: 50px'><b><br/>Please write a direct link for the banner or photo</b></div></center>";
die();
}

else if($ads_link  =="" or $ads_link =="Put a url to redirect visitors when they click on the ads")
{
echo "<center><div class='error' style='width: 785px;height: 50px'><b><br/>Please write a url to redirect the visitors when they click on the photo of the ads</b></div></center>";
die();
}

else if($state=='on' && $query_state ==3)
{
echo "<center><div class='error' style='width: 785px;height: 50px'><b>Warning: You can't put more than 3 ads visible.<br/>Note that you have already 3 ads running. </b></div></center>";
die();
}



else{
$wpdb->query("INSERT INTO ads_manager(ads_name,ads_img,ads_link,state,time,condition_ads) 
                                      values('$ads_name','$ads_img','$ads_link','$state','$hour','$condition_ads')");
echo "<center><div class='updated' style='width: 785px;height: 50px;color:green;border-color:green'><b><br/>Advertisement was added successfully.</b></div></center>";
}
}
}// close ads_admin


add_action('get_header','waimen_header_manage');
function waimen_header_manage(){
global $wpdb;
$time           =    time();
$get_ads_to_be_deleted  = $wpdb->query("DELETE FROM ads_manager where condition_ads='delete' and state='on' and $time > time");
$get_ads_to_be_reactive = $wpdb->query("UPDATE ads_manager SET state='off' WHERE condition_ads='re-activate' and $time > time");
}// close function header_manage




/////////////////////////////////////////////////////////
ob_start();
add_action('admin_menu','all_sub_menu');

function all_sub_menu()
{
add_submenu_page(__FILE__,'Manage All Ads','Manage All Ads','manage_options','All_manage_ads','All_manage_ads');
}

function All_manage_ads(){
global $wpdb;
wp_enqueue_script('walid_settigns',plugins_url('includes/add_settings.js',__FILE__),array('jquery'));
if(!isset($_GET['all'])){
header("Location: admin.php?page=All_manage_ads&all=1");
}else{
$all = $_GET['all'];
}
$get_num_rows = $wpdb->query("SELECT * FROM ads_manager");
$per_page     = 5;
$pages        = ceil($get_num_rows/$per_page);
$start        = (($all-1)*$per_page);

// i have to do pagination
$get_all_ads  = $wpdb->get_results("SELECT * FROM ads_manager limit $start,$per_page");


echo 
"
<table class='widefat'>
<thead>
<tr>
<th><center><b>ID</b></center></th>
<th><center><b>Ads_Name</b></center></th>
<th><center><b>Ads_Img</b></center></th>
<th><center><b>Ads_Link</b></center></th>
<th><center><b>State</b></center></th>
<th><center><b>Condition</b></center></th>
<th><center><b>Delete</b></center></th>
<th><center><b>Edit</b></center></th>
</tr>
</thead>

";


$i=1;
foreach($get_all_ads as $all_ads)
{
echo "
<tr align='center'>
<td><br/>$i</td>
<td><br/>$all_ads->ads_name</td>
<td><span class='img_opacity''><img src='$all_ads->ads_img' style='width:150px; height:50px;'></img></span></td>
<td><br/>$all_ads->ads_link</td>
";

if($all_ads->state=="off")
{
echo "<td><br/><font color='red'>OFF</font></td>";
}
else{
echo "<td><br/><font color='green'>ON</font></td>";
}
echo"
<td><br/>$all_ads->condition_ads</td>
<td><br/><a href='admin.php?page=All_manage_ads&all=1&delete=$all_ads->id'>Delete</a></td>
<td><br/><a href='admin.php?page=All_manage_ads&all=1&edit=$all_ads->id'>Edit</a></td>
</tr>
";
$i++;
}

if(isset($_GET['delete'])){
$delete = $_GET['delete'];
$wpdb->query("DELETE FROM ads_manager where id='$delete'");
echo "<script>alert('Ads Has Been Deleted Successfully')</script>";
echo '<meta http-equiv="refresh" content="0; URL=admin.php?page=All_manage_ads&all=1">';
}
if(isset($_GET['edit'])){
$edit = $_GET['edit'];
echo "<script>alert('Due to security reason you have to delete the ads and add it again sorry')</script>";
echo '<meta http-equiv="refresh" content="0; URL=admin.php?page=All_manage_ads&all=1">';
}



echo"
<table>
";

$next = $all+1;
$prev = $all-1;
echo "<br/><br/><center>";
if($prev>0){
echo "<a href='admin.php?page=All_manage_ads&all=$prev'><input type='button' value='Prev Page' class='button-primary'></a>";
}

if($next<=$pages){
echo "&nbsp;&nbsp;<a href='admin.php?page=All_manage_ads&all=$next'><input type='button' value='Next Page' class='button-primary'></a>";
}
echo "</center>";
}





/////////////////////////////////////////////////////////














add_action('admin_menu','waim_sub_menu');

function waim_sub_menu()
{
add_submenu_page(__FILE__,'Running Ads','Running Ads','manage_options','Manage_Your_Ads','running_manage_ads');
}

function running_manage_ads(){
global $wpdb;
wp_enqueue_script('walid_settigns',plugins_url('includes/add_settings.js',__FILE__),array('jquery'));

$get_all_ads  = $wpdb->get_results("SELECT * FROM ads_manager where state='on'");

echo 
"
<table class='widefat'>
<thead>
<tr>
<th><center><b>ID</b></center></th>
<th><center><b>Ads_Name</b></center></th>
<th><center><b>Ads_Img</b></center></th>
<th><center><b>Ads_Link</b></center></th>
<th><center><b>State</b></center></th>
<th><center><b>Condition</b></center></th>
</tr>
</thead>
";
$i=1;
foreach($get_all_ads as $all_ads)
{
echo "
<tr align='center'>
<td><br/>$i</td>
<td><br/>$all_ads->ads_name</td>
<td><span class='img_opacity''><img src='$all_ads->ads_img' style='width:150px; height:50px;'></img></span></td>
<td><br/>$all_ads->ads_link</td>
";
if($all_ads->state=="off")
{
echo "<td><br/><font color='red'>OFF</font></td>";
}
else{
echo "<td><br/><font color='green'>ON</font></td>";
}
echo"
<td><br/>$all_ads->condition_ads</td>
</tr>
";
$i++;
}

echo"
<table>
";



}









/////////////////////////////////////////////////////////////



























add_action('admin_menu','off_sub_menu');

function off_sub_menu()
{
add_submenu_page(__FILE__,'On-Hold Ads','On-Hold Ads','manage_options','On_Hold_manage_ads','On_Hold_manage_ads');
}

function On_Hold_manage_ads(){

global $wpdb;
wp_enqueue_script('walid_settigns',plugins_url('includes/add_settings.js',__FILE__),array('jquery'));
if(!isset($_GET['off'])){
header("Location: admin.php?page=On_Hold_manage_ads&off=1");
}else{
$all = $_GET['off'];
}
$get_num_rows = $wpdb->query("SELECT * FROM ads_manager where state='off' and condition_ads='re-activate'");
$per_page     = 5;
$pages        = ceil($get_num_rows/$per_page);
$start        = (($all-1)*$per_page);
$get_all_ads  = $wpdb->get_results("SELECT * FROM ads_manager where condition_ads='re-activate' or state='off' limit $start,$per_page");

echo 
"
<table class='widefat'>
<thead>
<tr>
<th><center><b>ID</b></center></th>
<th><center><b>Ads_Name</b></center></th>
<th><center><b>Ads_Img</b></center></th>
<th><center><b>Ads_Link</b></center></th>
<th><center><b>State</b></center></th>
<th><center><b>Condition</b></center></th>
<th><center><b>Activate</b></center></th>
</tr>
</thead>
";
$i=1;
foreach($get_all_ads as $all_ads)
{
echo "
<tr align='center'>
<td><br/>$i</td>
<td><br/>$all_ads->ads_name</td>
<td><span class='img_opacity''><img src='$all_ads->ads_img' style='width:150px; height:50px;'></img></span></td>
<td><br/>$all_ads->ads_link</td>
";
if($all_ads->state=="off")
{
echo "<td><br/><font color='red'>OFF</font></td>";
}
else{
echo "<td><br/><font color='green'>ON</font></td>";
}
echo"
<td><br/>$all_ads->condition_ads</td>
<td><br/><a href='admin.php?page=On_Hold_manage_ads&off=1&activates=$all_ads->id'>Activate</a></td>
</tr>
";
$i++;
}

echo"
<table>
";

$next = $all+1;
$prev = $all-1;
echo "<br/><br/><center>";
if($prev>0){
echo "<a href='admin.php?page=On_Hold_manage_ads&off=$prev'><input type='button' value='Prev Page' class='button-primary'></a>";
}

if($next<=$pages){
echo "&nbsp;&nbsp;<a href='admin.php?page=On_Hold_manage_ads&off=$next'><input type='button' value='Next Page' class='button-primary'></a>";
}
echo "</center>";
}
if(isset($_GET['activates']))
{
$activate  = $_GET['activates'];
$time      = time()+(3600*48);
$get_running_ads_3 = $wpdb->query("SELECT * FROM ads_manager where state='on'");
if($get_running_ads_3==3){
echo "<script>alert('Error!!, You have 3 ads activated.')</script>";
echo '<meta http-equiv="refresh" content="0; URL=admin.php?page=On_Hold_manage_ads&off=1">';
die();
}
else if($get_running_ads_3 !=3){
$wpdb->query("UPDATE ads_manager SET state='on',condition_ads='delete',time='$time' where id='$activate'");
echo "<script>alert('Ads Has Been Activate Successfully, after the re-activation of this ads it will be deleted automatically, and the time is set to 48H')</script>";
echo '<meta http-equiv="refresh" content="0; URL=admin.php?page=On_Hold_manage_ads&off=1">';
}




}


/////////////////////////////////////////////////////////////


add_action('admin_menu','waim_support_plug');

function waim_support_plug()
{
add_submenu_page(__FILE__,'Support','Support','manage_options','Support','waim_support_plugin');
}

function waim_support_plugin(){
echo
"
<h3>Thank you for downloading my plugin :)</h3><br/><br/><br/>
<b><font color='red'>*> How to use it?</font></b><br/>
<p><font size='3'>After the activation of the plugin, go to widgets and activate the ads manager widget.</font></p>
<p><font size='3'>After that you can easily control your advertisement from your control panel.</font></p>

<b><font color='red'>*> What are the options and features of this plugin?</font></b><br/>
<p><font size='3'>- You can add up to 3 ads in your index page.</font></p>
<p><font size='3'>- The reason that i have made only 3 ads, to make your blog in a good looking.</font></p>
<p><font size='3'>- You don't need to delete your ads every time, you can program your ads to be deleted automatically from 1 to 72H.</font></p>
<p><font size='3'>- if you have already 3 advertisements running you can put the new advertisements On-Hold.</font></p>
<p><font size='3'>- You can delete all the advertisements you don't need.</font></p>
<p><font size='3'>- And so many feaatures you can look at it by your self :).</font></p>

<b><font color='red'>*> Why i did this plugin?</font></b><br/>
<p><font size='3'>- I did this plugin to make it for sell, i coded it from 0 it took me almost 10 days, but i thought why i will sell it let the people enjoy this plugin and maybe they will donate for me to help me :).</font></p>
<p><font size='3' color='green'>- To donate for me by paypal click here: <a href='https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=walid%2enaceri%40yahoo%2ecom&lc=US&item_name=coder%2ddz%2ecom%20for%20programming&no_note=0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHostedGuest' target='donate'><img src='https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif'></img></a></font></p>
<p><font size='3'>- Coded by : Walid Naceri</font></p>
<p><font size='3'>- Twitter : @walid_naceri</font></p>
<p><font size='3'>- Website : <a href='http://coder-dz.com' target='author_website'>http://coder-dz.com</a></font></p>
";
}





























ob_end_flush();?>
