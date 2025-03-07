// Toggle Music
const music = document.getElementById('background-music');
const toggleButton = document.getElementById('music-toggle');

toggleButton.addEventListener('click', () => {
  if (music.paused) {
    music.play();
    toggleButton.textContent = '🎵 Pause Music';
  } else {
    music.pause();
    toggleButton.textContent = '🎵 Play Music';
  }
});

// Countdown Timer
const countdown = () => {
  const meetupDate = new Date('2023-12-31').getTime(); // Set your meetup date here
  const now = new Date().getTime();
  const timeLeft = meetupDate - now;

  const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
  const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
  const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

  document.getElementById('days').innerText = days;
  document.getElementById('hours').innerText = hours;
  document.getElementById('minutes').innerText = minutes;
  document.getElementById('seconds').innerText = seconds;
};

setInterval(countdown, 1000);