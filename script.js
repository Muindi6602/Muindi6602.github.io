$(document).ready(function(){
    $(window).scroll(function(){
        // sticky navbar on scroll script
        if(this.scrollY > 20){
            $('.navbar').addClass("sticky");
        }else{
            $('.navbar').removeClass("sticky");
        }
        
        // scroll-up button show/hide script
        if(this.scrollY > 500){
            $('.scroll-up-btn').addClass("show");
        }else{
            $('.scroll-up-btn').removeClass("show");
        }
    });

    // slide-up script
    $('.scroll-up-btn').click(function(){
        $('html').animate({scrollTop: 0});
        // removing smooth scroll on slide-up button click
        $('html').css("scrollBehavior", "auto");
    });

    $('.navbar .menu li a').click(function(){
        // applying again smooth scroll on menu items click
        $('html').css("scrollBehavior", "smooth");
    });

    // toggle menu/navbar script
    $('.menu-btn').click(function(){
        $('.navbar .menu').toggleClass("active");
        $('.menu-btn i').toggleClass("active");
    });

    // typing text animation script
    var typed = new Typed(".typing", {
        strings: ["Financial Analyst", "Accountant", "Web Developer", "Freelancer"],
        typeSpeed: 100,
        backSpeed: 60,
        loop: true
    });

    var typed = new Typed(".typing-2", {
        strings: ["Financial Analyst", "Accountant", "Web Developer", "Freelancer"],
        typeSpeed: 100,
        backSpeed: 60,
        loop: true
    });

    // owl carousel script
    $('.carousel').owlCarousel({
        margin: 20,
        loop: true,
        autoplay: true,
        autoplayTimeOut: 2000,
        autoplayHoverPause: true,
        responsive: {
            0:{
                items: 1,
                nav: false
            },
            600:{
                items: 2,
                nav: false
            },
            1000:{
                items: 3,
                nav: false
            }
        }
    });
});

//review+sweetalert

const stars = document.querySelectorAll(".star");
const rating = document.getElementById("rating");
const submitBtn = document.getElementById("submit");
const reviewsContainer = document.getElementById("reviews");

stars.forEach((star) => {
    star.addEventListener("click", () => {
        const value = parseInt(star.getAttribute("data-value"));
        rating.innerText = value;

        // Remove all existing classes from stars
        stars.forEach((s) => s.classList.remove("one", "two", "three", "four", "five"));

        // Add the appropriate class to 
        // each star based on the selected star's value
        stars.forEach((s, index) => {
            if (index < value) {
                s.classList.add(getStarColorClass(value));
            }
        });

        // Remove "selected" class from all stars
        stars.forEach((s) => s.classList.remove("selected"));
        // Add "selected" class to the clicked star
        star.classList.add("selected");
    });
});

submitBtn.addEventListener("click", () => {
    const userRating = parseInt(rating.innerText);

    if (!userRating) {
        alert("Please select a rating before submitting.");
        return;
    }

    if (userRating > 0) {
        // Display SweetAlert message
        Swal.fire({
            position: "centre",
            icon: "success",
            title: "Review posted Successfully",
            showConfirmButton: false,
            timer: 2700
        }).then(() => {
            const reviewMessage = `Hello Muindi, I've made a review of ${getStarEmoji(userRating)} Stars in your portfolio. Keep it up.`;
            const whatsappLink = `https://wa.me/254115783375?text=${encodeURIComponent(reviewMessage)}`;
            
            // Open WhatsApp with pre-filled message
            window.location.href = whatsappLink;
            
            // Reset styles after submitting
            rating.innerText = "0";
            stars.forEach((s) => s.classList.remove("one", "two", "three", "four", "five", "selected"));
        });
    }
});

function getStarColorClass(value) {
    switch (value) {
        case 1:
            return "one";
        case 2:
            return "two";
        case 3:
            return "three";
        case 4:
            return "four";
        case 5:
            return "five";
        default:
            return "";
    }
}

function getStarEmoji(value) {
    switch (value) {
        case 1:
            return "\u2605"; // ★
        case 2:
            return "\u2605\u2605"; // ★★
        case 3:
            return "\u2605\u2605\u2605"; // ★★★
        case 4:
            return "\u2605\u2605\u2605\u2605"; // ★★★★
        case 5:
            return "\u2605\u2605\u2605\u2605\u2605"; // ★★★★★
        default:
            return "";
    }
}
