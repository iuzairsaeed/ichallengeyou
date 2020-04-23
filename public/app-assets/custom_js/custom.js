/* setting slider images */

$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.setting_slider_image').click(function(){
       id = $(this).attr('id');
       
     
       
       $.ajax({
           type:'DELETE',
           url:'/deleteSliderImage',
           data:{'id':id},
           success:function(response) {
            x= $('[id='+id+']').parent().remove();
           },
           error:function(errors){
            console.log(errors)
           }
        });
      
    })
});