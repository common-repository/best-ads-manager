jQuery(document).ready(function($){

$('.count_click').click(function(){

var link = $(this).attr('href');

$.ajax({
url:'',
data:'link='+link,
type:'POST',
success: function(data){alert(data);}
});



});


});
