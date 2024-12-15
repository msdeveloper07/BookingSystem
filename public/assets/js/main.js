$(function(){
    
        $("#close-btn").click(function(){
       if($('#close-btn i').hasClass("fa-search")){
           $('#close-btn i').toggleClass("fa-times");
           $("#header-search").slideToggle("slow");
       }else{
           $('#close-btn i').addClass("fa-search");
       }
     });
    
   
//    $("#sidebar").animate({width: 'toggle'});
    
    $("#menu-sm").click(function(event){
        event.preventDefault();
        $(".nav.navbar-side > li > a span").fadeToggle("slow");
        if($('#menu-sm i').hasClass("fa-navicon")){
        $('#menu-sm i').toggleClass("fa-times");
        $('#menu-sm i').removeClass("fa-navicon");
    }else{
        $('#menu-sm i').addClass("fa-navicon");
        $('#menu-sm i').removeClass("fa-times");
    }
    });

    $("#menu-xs").click(function(event){
        event.preventDefault();
    if($('#menu-xs i').hasClass("fa-navicon")){
        $('#menu-xs i').addClass("fa-times");
        $('#menu-xs i').removeClass("fa-navicon");
    }else{
        $('#menu-xs i').addClass("fa-navicon");
        $('#menu-xs i').removeClass("fa-times");
    }
        $("#sidebar").animate({width: 'toggle'}, 500);
    });
    
   
 
    
});
 $(document).ready(function(){
   
    $("#myBtn3").click(function(){
        $("#myModal3").modal({backdrop: "static"});
    });
});



   $("#formoid").submit(function() {
    $.post($(this).attr("action"), $(this).serialize(), function(data) {
        $("#converted_amount").val(data);
        
    });
    return false; // prevent normal submit
});