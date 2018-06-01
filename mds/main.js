var i=0;
var slideIndex=0;
var images = [];
var time=3000;
images[0]="url(img-site/showcase1.jpg)";
images[1]="url(img-site/articol.jpg)";
images[2]="url(img-site/showcase.jpg)";
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
  if (n < 0) {slideIndex = images.length}
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
}
//Schimba img automat

function changeImg(){

      $(".showcase").css({"background": images[slideIndex],"opacity":0});
  $(".showcase").fadeTo('fast', 0.5, function(){
    $(this).css('opacity', 1)
});
for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }

     show.style.backgroundSize="cover";
     show.style.backgroundPosition="center";
     dots[slideIndex].className += " active";
  if(slideIndex<images.length-1){
   slideIndex++
  }else{
    slideIndex=0;
}

  setTimeout("changeImg()",time);
}
$(document).ready(function(){
        var buton = document.createElement("button");
        buton.onclick = function (event)
            {
                event.preventDefault();
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            }
        buton.id = "myBtn";
        buton.title = "Go to top";
        buton.innerHTML = "Top";
        document.body.appendChild(buton);
        $(function(){
          $(window).scroll(function(){
            if($(this).scrollTop() >= 200) {
                $("#myBtn").fadeIn('300');
            } else {
                $("#myBtn").fadeOut('200');
            }
          });
        });
        $(".showcase").fadeIn();
});
window.onload=showSlides;
window.onload=changeImg;
