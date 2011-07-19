jQuery(document).ready(function($) {
	
	//Animate gallery show
	var slidesCon = $('.ddslides');
	var thumbshow = $('#thumbshow'); 
	var thumbWrap = $('#thumbwrap');
	var thumbWrapWidth = $('#thumbwrap').width();
	var thumb = $('.thumbs');
	var thumbs = $('.thumbs li');
	var thumsSize = $('.thumbs li').size();
	var imageCaption = $('#imgCaption');
	
	var slides = $('.ddslides li');
	var prevbtn = $('.prevbtn');
	var nextbtn = $('.nextbtn');
	var navarrow = $('.navarrow');
	
	slides.hide();
	slides.eq(0).show();
	
	var imgCapPad = imageCaption.css('paddingLeft');
	
	//alert(imgCapPad);
	var imgTitle = slides.eq(0).find('img').attr('title');
	var imgDescription = slides.eq(0).find('img').attr('alt');		
	imageCaption.find('h4').html(imgTitle);
	imageCaption.find('p').html(imgDescription);
	
	thumbs.click(function(){
		var thumbIndex = $(this).index();
		slides.fadeOut('slow');
		slides.eq(thumbIndex).fadeIn('slow');
		
		if(displayImageInfo){
			var imgTitle = slides.eq(thumbIndex).find('img').attr('title');
			var imgDescription = slides.eq(thumbIndex).find('img').attr('alt');		
			$('#imgCaption h4').html(imgTitle);
			$('#imgCaption p').html(imgDescription);
		}
	});
	
	
		
	//Dynamically set large image size
	var largeConPadLeftRight = parseInt(slides.css('paddingLeft'))*2;
	var largeConPadTopBottom = largeConPadLeftRight;
	
	var largeConBorderLeftRight = parseInt(slides.css('border-left-width'))*2;
	var largeConBorderTopBottom = largeConBorderLeftRight;	
	
	var largeConWidth = parseInt(largeImageWidth)+largeConPadLeftRight+largeConBorderLeftRight;
	var largeConHeight = parseInt(largeImageHeight)+largeConPadTopBottom+largeConBorderTopBottom;
	
	//alert(largeConHeight);
	
	slidesCon.width(largeConWidth);
	slidesCon.height(largeConHeight);
	imageCaption.css({'width':(largeImageWidth-parseInt(imgCapPad)*2),'margin':(largeConPadLeftRight/2+largeConBorderLeftRight/2)});
	
	
	//Dynamically set thumbnail size
	var thumbsPadLeftRight = parseInt(thumbs.css('paddingLeft'))*2;
	var thumbsPadTopBottom = thumbsPadLeftRight;
	
	var thumbsBorderLeftRight = parseInt(thumbs.css('border-left-width'))*2;
	var thumbsBorderTopBottom = thumbsBorderLeftRight;
	
	var thumbsMarginLeft = parseInt(thumbs.css('marginLeft'));
	var thumbsMarginRight = parseInt(thumbs.css('marginRight'));
	var thumbsMarginTop = parseInt(thumbs.css('marginTop'));
	var thumbsMarginBottom = parseInt(thumbs.css('marginBottom'));
	
	var thumbNavWidth = parseInt(navarrow.css('width'))*2;
	
	
	var thumbshowBorder = parseInt(thumbshow.css('border-left-width'))*2;
	var thumbshowPad = parseInt(thumbshow.css('paddingLeft'))*2;
	thumbshow.width(largeConWidth-thumbshowPad-thumbshowBorder);
	var thumbsWidth = parseInt(thumbImageWidth)+thumbsPadLeftRight+thumbsBorderLeftRight+thumbsMarginLeft+thumbsMarginRight;
	var thumbsHeight = parseInt(thumbImageHeight)+thumbsPadTopBottom+thumbsBorderTopBottom+thumbsMarginTop+thumbsMarginBottom;
	
	//Actual thumbnail width
	var thumbActualWidh = thumsSize*thumbsWidth;
	thumb.width(thumbActualWidh);
	
	//Dynamically setting thumbnail height
	thumbWrap.height(thumbsHeight);
	
	
	var sliderLeftPos = 0;
	
	//Thumbnail Previous navigate
	prevbtn.click(function(){
		if(sliderLeftPos>-(thumbActualWidh -(largeConWidth-thumbNavWidth))){
		sliderLeftPos = sliderLeftPos - thumbsWidth;		
		thumb.animate({left:sliderLeftPos},slideSpeed*1);
		}
	});
	
	
	//Thumbnail Next navigate
	nextbtn.click(function(){		
		if(sliderLeftPos!=0){
		sliderLeftPos = sliderLeftPos + thumbsWidth;		
		thumb.animate({left:sliderLeftPos},slideSpeed*1);
		}
	});
	
	//Gallery data manipulation
	
	
	
			
});
