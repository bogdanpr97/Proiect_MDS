var i=0;
var slideIndex=0;
var images = [];
var time=4000;
images[0]="url(img/showcase1.jpg)";
images[1]="url(img/articol.jpeg)";
images[2]="url(img/showcase.jpg)";
var show=document.getElementById('show');
var dots = document.getElementsByClassName("dot");


//Schimba img din buton

function plusSlides(n) {
  showSlides(slideIndex += n);
}

//Schimba img din cerculete

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i1;
 
  if (n > images.length-1) {slideIndex = 0;} 
  if (n < 0) {slideIndex = images.length-1}

     if(slideIndex<0)
  {
    slideIndex=images.length;
  }
     for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
 
  //show.style.background=images[slideIndex];
  $(".showcase").css({"background": images[slideIndex],"opacity":0});
  $(".showcase").fadeTo('fast', 0.5, function(){
    $(this).css('opacity', 1)
});


  
  
  show.style.backgroundSize="cover";
  show.style.backgroundPosition="center";
  dots[slideIndex].className += " active";
  /*
   if(slideIndex<images.length-1){
   slideIndex++;
  }else{
    slideIndex=0;
}*/
  console.log('show' + slideIndex);
  //setTimeout("showSlides(slideIndex)",time);

}
//Schimba img automat

function changeImg(){
     console.log(slideIndex);
      if(slideIndex<images.length-1){
   slideIndex++;
   
     }else{
    slideIndex=0;
}

      $(".showcase").css({"background": images[slideIndex],"opacity":0});
  $(".showcase").fadeTo('slow', 0.5, function(){
    $(this).css('opacity', 1)
});
for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
    
     show.style.backgroundSize="cover";
     show.style.backgroundPosition="center";
     dots[slideIndex].className += " active";
 
  
}
$(document).ready(function(){   
      
        $(".showcase").fadeIn();
});
window.onload=showSlides(0);
window.onload = function(){
   setInterval("changeImg()", time);
};
//window.onload=changeImg;