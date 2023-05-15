 var i = 0;
 var images = ['yoga1.jpg', 'yoga2.jpg', 'yoga3.jpg'];
 var time = 100;

 function changeImage() {
    document.getElementById.carousel.src = images[i];

    if(i < images.length - 1) {
        i++;
    } else {
        i = 0;
    }

    setTimeout("changeImage()", time)
 }
 
 
//  const myCarouselElement = document.getElementById('#carouselBasicExample')
//  const carousel = new bootstrap.Carousel(myCarouselElement, {
 


//  })