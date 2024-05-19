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

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("sendSMS").addEventListener("click", function() {
        // Phone number to send SMS
        var phoneNumber = "+254115783375";
        // Message to pre-fill in the SMS body
        var message = "Hello Joseph, can I hire you to do this job of ... ";
        // Encode message for URL
        var encodedMessage = encodeURIComponent(message);
        // Create SMS URL with phone number and pre-filled message
        var smsUrl = "sms:" + phoneNumber + "?body=" + encodedMessage; // Note the '?' instead of '&'
        // Redirect to SMS app
        window.location.href = smsUrl;
    });
});


document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("callMe").addEventListener("click", function() {
        // Phone number to call
        var phoneNumber = "+254115783375";
        // Create phone call URL
        var callUrl = "tel:" + phoneNumber;
        // Redirect to phone call app
        window.location.href = callUrl;
    });
});


document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("sendEmail").addEventListener("click", function() {
        // Email address to send to
        var emailAddress = "mutuajosephmuindi@gmail.com";
        // Default message
        var defaultMessage = "Hello Joseph, can I hire you to do this job of ...";
        // Default subject
        var defaultSubject = "Portfolio Attachment";
        // Encode message and subject for URL
        var encodedMessage = encodeURIComponent(defaultMessage);
        var encodedSubject = encodeURIComponent(defaultSubject);
        // Create email URL with recipient, subject, and default message
        var emailUrl = "mailto:" + emailAddress + "?subject=" + encodedSubject + "&body=" + encodedMessage;
        // Redirect to email app
        window.location.href = emailUrl;
    });
});


document.addEventListener("DOMContentLoaded", function() {
    // Event listener for delete button
    document.getElementById('deleteButton').addEventListener('click', function() {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to reverse this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Clear form fields
                document.getElementById("name").value = "";
                document.getElementById("email").value = "";
                document.getElementById("subject").value = "";
                document.getElementById("message").value = "";
                document.getElementById("captcha").value = "";
                document.getElementById("termsCheckbox").checked = false;

                Swal.fire({
                    title: "Deleted!",
                    text: "Your message has been deleted.",
                    icon: "success"
                });
            }
        });
    });

    // Function to validate the form
    function validateForm() {
        var captchaInput = document.getElementById('captcha').value.trim();
        var termsCheckbox = document.getElementById('termsCheckbox');

        // Check if the entered value is correct and terms checkbox is checked
        if (parseInt(captchaInput) === 81 && termsCheckbox.checked) {
            openWhatsApp(); // If correct and terms agreed, proceed to send the message
        } else {
            if (!termsCheckbox.checked) {
                alert("Please read and agree to the terms and conditions before sending the message.");
            } else {
                alert("Incorrect answer to the CAPTCHA. Please try again."); // If incorrect, show an alert
            }
        }
    }

    // Function to open WhatsApp with pre-filled message
    function openWhatsApp() {
        var name = document.getElementById('name').value.trim();
        var email = document.getElementById('email').value.trim();
        var subject = document.getElementById('subject').value.trim();
        var message = document.getElementById('message').value.trim();

        // Construct the WhatsApp message
        var whatsappMessage = `Subject: *${subject}*\n\nMessage: ${message}\n\nName: ${name}\nEmail: ${email}`;

        // Encode the message for the URL
        var encodedMessage = encodeURIComponent(whatsappMessage);

        // Construct the WhatsApp URL
        var whatsappUrl = `https://wa.me/+254115783375?text=${encodedMessage}`;

        // Open WhatsApp with the pre-filled message
        window.open(whatsappUrl);
    }

    // Function to generate CAPTCHA
    function generateCaptcha() {
        // Update the CAPTCHA question
        document.getElementById('captcha').value = '';
        document.getElementById('captchaLabel').innerHTML = '<span style="color: red;">Confirm you are not a robot</span><br>What is 27 + 54?';
    }

    // Call generateCaptcha() when the page loads
    window.onload = function() {
        generateCaptcha();
    };
});


let show = document.getElementById("show");
let search = document.getElementById("search");
let cityVal = document.getElementById("city");


let key = "c8b4985befd346a7a7113c6439d515ed";

let getWeather = () => {
  let cityValue = cityVal.value;
  if (cityValue.length == 0) {
    show.innerHTML = `<h3 class="error">Muindi: Please enter a city name</h3>`;
  }
  else {
    let url = `https://api.openweathermap.org/data/2.5/weather?q=${cityValue}&appid=${key}&units=metric`;
    cityVal.value = "";
    fetch(url)
      .then((resp) => resp.json())
      .then((data) => {
        show.innerHTML = `
        <h2>${data.name}, ${data.sys.country}</h2>
        <h4 class="weather">${data.weather[0].main}</h4>
        <h4 class="desc">${data.weather[0].description}</h4>
        <img src="https://openweathermap.org/img/w/${data.weather[0].icon}.png">
        <h1>${data.main.temp} &#176;</h1>
        <div class="temp_container">
         <div>
            <h4 class="title">min</h4>
            <h4 class="temp">${data.main.temp_min}&#176;</h4>
         </div>
         <div>
            <h4 class="title">max</h4>
            <h4 class="temp">${data.main.temp_max}&#176;</h4>
         </div>   
        </div>
        `;
      })
      .catch(() => {
        show.innerHTML = `<h3 class="error">Muindi: City not found</h3>`;
      });
  }
};
search.addEventListener("click", getWeather);
window.addEventListener("load", getWeather);



"use strict";

const input = document.querySelector(".input");
const result = document.querySelector(".result");
const deleteBtn = document.querySelector(".delete");
const keys = document.querySelectorAll(".bottom span");

let operation = "";
let answer;
let decimalAdded = false;

const operators = ["+", "-", "x", "÷"];

function handleKeyPress (e) {
  const key = e.target.dataset.key;
  const lastChar = operation[operation.length - 1];

  if (key === "=") {
    return;
  }

  if (key === "." && decimalAdded) {
    return;
  }

  if (operators.indexOf(key) !== -1) {
    decimalAdded = false;
  }

  if (operation.length === 0 && key === "-") {
    operation += key;
    input.innerHTML = operation;
    return;
  }

  if (operation.length === 0 && operators.indexOf(key) !== -1) {
    input.innerHTML = operation;
    return;
  }

  if (operators.indexOf(lastChar) !== -1 && operators.indexOf(key) !== -1) {
    operation = operation.replace(/.$/, key);
    input.innerHTML = operation;
    return;
  }

  if (key) {
    if (key === ".") decimalAdded = true;
    operation += key;
    input.innerHTML = operation;
    return;
  }

}

function evaluate(e) {
  const key = e.target.dataset.key;
  const lastChar = operation[operation.length - 1];

  if (key === "=" && operators.indexOf(lastChar) !== -1) {
    operation = operation.slice(0, -1);
  }

  if (operation.length === 0) {
    answer = "";
    result.innerHTML = answer;
    return;
  }

  try {

    if (operation[0] === "0" && operation[1] !== "." && operation.length > 1) {
      operation = operation.slice(1);
    }

    const final = operation.replace(/x/g, "*").replace(/÷/g, "/");
    answer = +(eval(final)).toFixed(5);

    if (key === "=") {
      decimalAdded = false;
      operation = `${answer}`;
      answer = "";
      input.innerHTML = operation;
      result.innerHTML = answer;
      return;
    }

    result.innerHTML = answer;

  } catch (e) {
    if (key === "=") {
      decimalAdded = false;
      input.innerHTML = `<span class="error">${operation}</span>`;
      result.innerHTML = `<span class="error">Bad Expression</span>`;
    }
    console.log(e);
  }

}

function clearInput (e) {

  if (e.ctrlKey) {
    operation = "";
    answer = "";
    input.innerHTML = operation;
    result.innerHTML = answer;
    return;
  }

  operation = operation.slice(0, -1);
  input.innerHTML = operation;

}

deleteBtn.addEventListener("click", clearInput);
keys.forEach(key => {
  key.addEventListener("click", handleKeyPress);
  key.addEventListener("click", evaluate);
});