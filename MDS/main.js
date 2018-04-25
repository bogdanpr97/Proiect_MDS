var i=0;
var images = [];
var time=3000;

images[0]="url(img/showcase.jpg)";
images[1]="url(img/articol.jpeg)";
var show=document.getElementById('show');

//Schimba img

function changeImg(){
	
     show.style.background=images[i];
     show.style.backgroundSize="cover";
     show.style.backgroundPosition="center";
  if(i<images.length-1){
   i++
  }else{
    i=0;
}
  setTimeout("changeImg()",time);
}

window.onload=changeImg;
