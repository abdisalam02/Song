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

        const clientId = 'fe06704a7b964fe9aea22b83f3655c61';
        const clientSecret = '4de8a22d366a4cc9880cec6de6a955a4';
        
        // Function to fetch access token
        async function fetchAccessToken() {
          const data = new URLSearchParams();
          data.append('grant_type', 'client_credentials');
          data.append('client_id', clientId);
          data.append('client_secret', clientSecret);
        
          try {
              // Make a request to obtain the access token
              const response = await fetch('https://accounts.spotify.com/api/token', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/x-www-form-urlencoded',
                  },
                  body: data, // Make sure `data` is defined
              });
        
              if (!response.ok) {
                  throw new Error('Failed to fetch access token');
              }
        
              const responseData = await response.json();
              const accessToken = responseData.access_token;
        
              // Use the access token for subsequent API requests
              console.log('Access Token:', accessToken);
        
              // Call the function to fetch data after obtaining the access token
              fetchSongData(accessToken); // Pass the access token to fetchSongData
          } catch (error) {
              console.error('Error fetching access token:', error.message);
          }
        }
        
        // Function to fetch data using the access token
        async function fetchSongData(accessToken) {
            try {
                const songId = '11dFghVXANMlKmJXsNCbNl'; // Replace with the actual song ID
                const response = await fetch(`https://api.spotify.com/v1/tracks/${songId}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${accessToken}`,
                    },
                });
        
                if (!response.ok) {
                    throw new Error('Failed to fetch playlist data');
                }
        
                const tracksData = await response.json();
                console.log('Tracks Data:', tracksData);
            } catch (error) {
                console.error('Error fetching song data:', error.message);
            }
        }
        
        // Call the function to fetch access token
        fetchAccessToken();
        