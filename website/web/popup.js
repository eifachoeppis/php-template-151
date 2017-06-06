$(document).ready(function() {
	$('main img').each( function(index, element){
        var img    = new Image(),
             imgSrc = element.getAttribute("src");

        img.src = imgSrc;    
	});
	
	$('main').magnificPopup({
		  delegate: 'img', // child items selector, by clicking on it popup will open
		  type: 'image',
		  gallery: {
              enabled: true
          },
          zoom: {
      	    enabled: true, // By default it's false, so don't forget to enable it

      	    duration: 300, // duration of the effect, in milliseconds
      	    easing: 'ease-in-out', // CSS transition easing function
	      	opener: function(openerElement) {
	      	    // openerElement is the element on which popup was initialized, in this case its <a> tag
	      	    // you don't need to add "opener" option if this code matches your needs, it's defailt one.
	      	    return openerElement.is('img') ? openerElement : openerElement.find('img');
	      	}
        },
          callbacks: {
              elementParse: function(item) { 
            	  item.src = item.el.attr('src');
            	  }
         }  
		  // other options
		});
});

