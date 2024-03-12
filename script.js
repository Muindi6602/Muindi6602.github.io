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

        // Add the appropriate class to each star based on the selected star's value
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
        const reviewMessage = `Hello Muindi, I've made a review of ${getStarEmoji(userRating)} in your portfolio. Keep it up.`;
        const whatsappLink = `https://wa.me/254115783375?text=${encodeURIComponent(reviewMessage)}`;
        
        // Open WhatsApp with pre-filled message
        window.location.href = whatsappLink;
        
        // Reset styles after submitting
        rating.innerText = "0";
        stars.forEach((s) => s.classList.remove("one", "two", "three", "four", "five", "selected"));
    }
});

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
