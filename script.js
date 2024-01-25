function getRandomHexCode() {
    // Define hex code ranges for purple, pink, blue, and white gradients
    const gradients = {
        purple: ['#800080', '#9400D3', '#9932CC'],
        pink: ['#FF69B4', '#FF1493', '#C71585'],
        // blue: ['#0000FF', '#4169E1', '#6495ED'],
        // white: ['#FFFFFF', '#F8F8FF', '#F5F5F5']
    };

    // Choose a random gradient
    const gradientKeys = Object.keys(gradients);
    const randomGradientKey = gradientKeys[Math.floor(Math.random() * gradientKeys.length)];
    const randomGradient = gradients[randomGradientKey];

    // Choose a random color from the selected gradient
    return randomGradient[Math.floor(Math.random() * randomGradient.length)];
}


        document.addEventListener('DOMContentLoaded', function() {
            var cards = document.querySelectorAll('.random-color');

            cards.forEach(function(card) {
                // Apply the random color to each card
                card.style.backgroundColor = getRandomHexCode();
            });
        });