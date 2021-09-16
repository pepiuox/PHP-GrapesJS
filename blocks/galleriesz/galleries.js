

$(function () {
    $("img.scale").imageScale({
        rescaleOnResize: true
    });

    // INIT Variables
    var IntID;
    var total = $(".galleryPreviewImage").find("li").length;
    var imagesTotal = total;
    var currentImage = 1;

    $('a.thumbnailsimage' + currentImage).addClass("active");

    // SET WIDTH for THUMBNAILS CONTAINER

    function startCounter(n) {
        $(".count").html(n + "/" + total);
    }
    startCounter(currentImage);


    // PREVIOUS ARROW CODE
    function prev() {
        $('li.previewImage' + currentImage).hide();
        $('a.thumbnailsimage' + currentImage).removeClass("active");

        currentImage--;

        if (currentImage === 0) {
            currentImage = imagesTotal;
        }

        $('a.thumbnailsimage' + currentImage).addClass("active");
        $('li.previewImage' + currentImage).show();
        startCounter(currentImage);
        return false;
    }
    $('.previousSlideArrow').click(function () {
        prev();
    });
    // ============================

    // NEXT ARROW CODE
    function next() {
        $('li.previewImage' + currentImage).hide();
        $('a.thumbnailsimage' + currentImage).removeClass("active");

        currentImage++;

        if (currentImage === imagesTotal + 1) {
            currentImage = 1;
        }

        $('a.thumbnailsimage' + currentImage).addClass("active");
        $('li.previewImage' + currentImage).show();
        startCounter(currentImage);
        return false;
    }
    $('.nextSlideArrow').click(function () {
        next();
    });

    // BULLETS CODE                

    function changeImage(n) {
        $('li.previewImage' + currentImage).hide();
        currentImage = n;
        $('li.previewImage' + n).show();
        $('.galleryThumbnails a').removeClass("active");
        $('a.thumbnailsimage' + currentImage).addClass("active");
        $(".galleryPreviewContainer").show();
        $(".square").hide();
        $(".boxes").show();
        startCounter(n);
        return false;
    }
    // AUTOMATIC CHANGE SLIDES
    function autoChangeSlides() {
        $('li.previewImage' + currentImage).fadeOut(500).hide();
        $('a.thumbnailsimage' + currentImage).removeClass("active");
        currentImage++;
        if (currentImage === imagesTotal + 1) {
            currentImage = 1;
        }
        $('a.thumbnailsimage' + currentImage).addClass("active");
        $('li.previewImage' + currentImage).fadeIn(500).show();

        startCounter(currentImage);
    }
    IntID = setInterval(autoChangeSlides, 4000);

    $('.arrows').hide();
    // ============================    
    $('.galleryPreviewArrows').mouseover(function () {
        $('.arrows').show();
    });
    $('.galleryPreviewArrows').mouseout(function () {
        $('.arrows').hide();
    });

    $('.thumbSlides').click(function () {
        var newImage = $(this).index() + 1;
        changeImage(newImage);
        startCounter(newImage);
    });

    // Listen for click of prev icon

    $(".playSlideArrow").hide();
    $(".stopSlideArrow").click(function () {
        clearInterval(IntID);
        $(".playSlideArrow").show();
        $(".stopSlideArrow").hide();
        return false;
    });
    $(".playSlideArrow").click(function () {
        IntID = setInterval(autoChangeSlides, 4000);
        $(".stopSlideArrow").show();
        $(".playSlideArrow").hide();
        return false;
    });
});