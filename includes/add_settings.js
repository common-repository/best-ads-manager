jQuery(document).ready(function($){
$('.links_ads').html('<font color="red">The link must start with http://</font>');
$('input[type="text"]').click(function()
{
$(this).val('');
});
$('#feedback_ads_name').text('minimum characters (15).');
$('#count_name').keyup(function(){
var max_length    = 15;
var typing_length = $(this).val().length;
var remaining     = max_length - typing_length;
$('#feedback_ads_name').text(remaining + ' characters.' );
if(remaining == 0)
{
$('#feedback_ads_name').html('<b><font color="red">You can not write more than 15 characters.</font></b>');
}
});
$('.condition_ads').change(function(){
if($(this).val()=="re-activate"){
$('.ads_manager_choose').text('Re-Activate: Means the ads won\'t be deleted automatically.');
}else{
$('.ads_manager_choose').text('Delete: Means the ads will be deleted automatically after it reached its estimated time.');
}
});


$('.img_opacity').css('opacity','0.6');

$('.img_opacity').mouseenter(function(){
$(this).css('opacity','1.0');
}).mouseleave(function(){
$(this).css('opacity','0.6');
});







});
