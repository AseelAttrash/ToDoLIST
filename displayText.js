//display text code
const texts = [
    "Believe in yourself and take action today. The future belongs to those who believe in the beauty of their dreams and take steps towards them.",
    "Success is the result of hard work, persistence, and learning from failure. Don't be afraid to try and keep pushing forward.",
    "The best way to get started on your goals is to stop talking and start doing. The journey of a thousand miles begins with a single step.",
    "Your goals are achievable with dedication and effort. Stay committed and maintain a positive attitude throughout your journey.",
    "One step at a time leads to great accomplishments. Break your tasks into smaller, manageable pieces and tackle them one by one.",
    "The secret of getting ahead is getting started. Don't wait for the perfect moment, take the moment and make it perfect.",
    "Procrastination is the thief of time. Act now, and take control of your life. Remember, the best time to start was yesterday, the second-best time is now.",
    "Believe you can, and you're halfway there. Keep your focus on the end goal, and remember that every small step counts.",
    "The only limit to our realization of tomorrow will be our doubts of today. Clear your mind of doubt and focus on what you can achieve.",
    "Your future is created by what you do today, not tomorrow. Make the most of the present and build a better future for yourself."
  ];
  
  const words = getRandomText().split(" ");
  const displayTextElement = document.getElementById("display-text");
  let currentIndex = 0;
  
  function getRandomText() {
    const randomIndex = Math.floor(Math.random() * texts.length);
    return texts[randomIndex];
  }
  
  function displayNextWord() {
    if (currentIndex < words.length) {
      displayTextElement.innerHTML += words[currentIndex] + " ";
      currentIndex++;
      setTimeout(displayNextWord, 300); //  delay between words in milliseconds
    }
  }
  
  document.addEventListener("DOMContentLoaded", function () {
    displayNextWord();
  });
  