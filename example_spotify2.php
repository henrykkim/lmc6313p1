<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Exercise 1</title>
    <meta name="description" content="LMC 6313 Exercise 1 by Henry Kim">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="../favicon.png" type="image/png">
    <!-- 
      Here I'm using a CDN (Content Delivery Netrwork) rather than Bootstrap and Normalize themselves. 
      This is somewhat easier to manage in that you don't need to host these files, you instead link to them on the web.
      Most Frameworks are hosted somewhere, google "CDN NameOfFrameworkOrLibarary" to find one or copy theses
    -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <nav class="navbar navbar-white bg-white mt-1">
      <a class="navbar-brand" href="http://dmcore.lmc.gatech.edu/~kkim733/p1/example_spotify.php"></a>
      <form class="form-inline">
        <select class="form-control" name="song"> 
        <!-- Nav bar with values for php -->
          <option value="3Vo4wInECJQuz9BIBMOu8i">Bruno Mars - Fineness (Remix) [feat. Cardi B]</option>
          <option value="6uBhi9gBXWjanegOb2Phh0">Zedd - Stay (with Alessia Cara)</option>
          <option value="7qiZfU4dY1lWllzX7mPBI3">Ed Sheeran - Shape of You</option>
          <option value="5tz69p7tJuGPeMGwNTxYuV">Logic, Alessia Cara, Khalid - 1-800-273-8255</option>
          <option value="3I4QOvltiKcMu3xmnQjEct">Lorde - Green Light</option>
          <option value="79NlESqzFSW0hdBWgls4FX">Kesha - Praying</option>
        </select>
        <button class="btn btn-outline-success my-2 my-sm-0 mx-1" type="submit" name = "subemit">
          Submit
        </button>
      </form>
    </nav>
    <div class="container">
        <?php
          error_reporting(E_ALL);
          ini_set("display_errors", 1);

          // API/OAuth Key
          $client_id = '97cceb1c246a419e928c11fe96240e48'; 
          $client_secret = '5d02dc41c78a4c60bb32dde775a6479b';
          $token = null;

          $host = 'https://api.spotify.com';
          $method = 'GET';
          $path = '/v1/audio-features/'; // api call for features
          $path2 = '/v1/tracks/'; // api call for track

          if (isset($_GET['song'])) {

            $data = $_GET['song']; // Value from a nav bar

            $combinedURL = $host.$path.$data; // For feature
            $combinedURL2 = $host.$path2.$data; // For track

            /* cURL code. I would recommend copying this section */
            $Auth_Key = $client_id.":".$client_secret;
            $encoded_Auth_Key=base64_encode($Auth_Key);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://accounts.spotify.com/api/token");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
            curl_setopt($ch, CURLOPT_POST, true); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic '.$encoded_Auth_Key
            ));
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
            $output = curl_exec($ch);
            curl_close($ch);
            $token = json_decode($output,true);
            $token = $token['access_token'];
            

            //For Analysis
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $combinedURL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer ".$token
            ));
            $output = curl_exec($ch);
            curl_close($ch);
            
            //For General
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $combinedURL2);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer ".$token
            ));
            $output2 = curl_exec($ch);
            curl_close($ch);

            /* cURL code ends. Make sure you decode the results! */

            $spotifyData = json_decode($output,true);
            $spotifyData2 = json_decode($output2,true);
            /*
                JSON data is formatted differently for every API. Consult the documentation 
                https://developer.twitter.com/en/docs
            */

            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data); 

            // Table
            echo "
              <div class='text-center'>
                <img src=".$spotifyData2['album']['images'][0]['url']." class='img-fluid albumart' alt='Responsive image'>
                <h1 class='title'>".$spotifyData2['album']['artists'][0]['name'].' - '.$spotifyData2['name']."</h1>
              </div>

              <table class='table'>
                <tbody>
                  <tr>
                    <th>Danceability<br><span class ='subtitle'>Danceability describes how suitable a track is for dancing based on a combination of musical elements including tempo, rhythm stability, beat strength, and overall regularity. A value of 0.0 is least danceable and 1.0 is most danceable.</span></th>
                    <td>".$spotifyData['danceability']."</td>
                  </tr>
                </tbody>
                <tbody>
                  <tr>
                  <th>Energy<br><span class ='subtitle'>Energy is a measure from 0.0 to 1.0 and represents a perceptual measure of intensity and activity. Typically, energetic tracks feel fast, loud, and noisy. For example, death metal has high energy, while a Bach prelude scores low on the scale. Perceptual features contributing to this attribute include dynamic range, perceived loudness, timbre, onset rate, and general entropy.</span></th>
                  <td>".$spotifyData['energy']."</td>
                  </tr>
                </tbody>
                <tbody>
                  <tr>
                  <th>Loudness<br><span class ='subtitle'>typical range between -60 and 0 db</span></th>
                  <td>".$spotifyData['loudness']."db</td>
                  </tr>
                </tbody>
                <tbody>
                  <tr>
                  <th>Speechiness<br><span class ='subtitle'>Speechiness detects the presence of spoken words in a track. The more exclusively speech-like the recording (e.g. talk show, audio book, poetry), the closer to 1.0 the attribute value. Values above 0.66 describe tracks that are probably made entirely of spoken words. Values between 0.33 and 0.66 describe tracks that may contain both music and speech, either in sections or layered, including such cases as rap music. Values below 0.33 most likely represent music and other non-speech-like tracks.</span></th>
                  <td>".$spotifyData['speechiness']."</td>
                  </tr>
                </tbody>
                <tbody>
                  <tr>
                  <th>Acousticness<br><span class ='subtitle'>A confidence measure from 0.0 to 1.0 of whether the track is acoustic. 1.0 represents high confidence the track is acoustic.</span></th>
                  <td>".$spotifyData['acousticness']."</td>
                  </tr>
                </tbody>
                <tbody>
                  <tr>
                  <th>Instrumentalness<br><span class ='subtitle'>Predicts whether a track contains no vocals. Ooh and aah sounds are treated as instrumental in this context. Rap or spoken word tracks are clearly vocal. The closer the instrumentalness value is to 1.0, the greater likelihood the track contains no vocal content. Values above 0.5 are intended to represent instrumental tracks, but confidence is higher as the value approaches 1.0.</span></th>
                  <td>".$spotifyData['instrumentalness']."</td>
                  </tr>
                </tbody>
                <tbody>
                  <tr>
                  <th>Liveness<br><span class ='subtitle'>Speechiness detects the presence of spoken words in a track. The more exclusively speech-like the recording (e.g. talk show, audio book, poetry), the closer to 1.0 the attribute value. Values above 0.66 describe tracks that are probably made entirely of spoken words. Values between 0.33 and 0.66 describe tracks that may contain both music and speech, either in sections or layered, including such cases as rap music. Values below 0.33 most likely represent music and other non-speech-like tracks.</span></span></th>
                  <td>".$spotifyData['liveness']."</td>
                  </tr>
                </tbody>
              </table>
                  ";     
          } else {
              echo '
              <div class="row justify-content-md-center mt-5">
                <div class="col col-lg-3">
                  <h1 class="funfact">Fun facts about some of Grammys 2018 songs</h1>
                </div>
                <div class="col col-lg-5">
                  <p>Choose the song from the form above and click submit to learn more abour features of it. Generated by Spotify.</p>
                </div>
              </div>
              ';
          }
        ?>
    </div>
  </body>
  <!--
    Same Deal with CDNs down here. Also these script tags work outside of the body tag. 
    I often leave them out for organizational reasons (Easier to seperate the two when coding)
  -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
</html>