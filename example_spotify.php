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
  </head>
  <body>
    <nav class="navbar navbar-light bg-light">
      <a class="navbar-brand" href="http://dmcore.lmc.gatech.edu/~kkim733/p1/example_spotify.php">Fun facts about Grammy-winning songs</a>
      <form class="form-inline">
        <select class="form-control" name="song">
          <option value="3Vo4wInECJQuz9BIBMOu8i">Bruno Mars - Fineness (Remix) [feat. Cardi B]</option>
          <option value="6uBhi9gBXWjanegOb2Phh0">Zedd - Stay (with Alessia Cara)</option>
          <option value="7qiZfU4dY1lWllzX7mPBI3">Ed Sheeran - Shape of You</option>
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

          // PLEASE get your own API/OAuth Key
          $client_id = '97cceb1c246a419e928c11fe96240e48'; 
          $client_secret = '5d02dc41c78a4c60bb32dde775a6479b';
          $token = null;

          $host = 'https://api.spotify.com';
          $method = 'GET';
          $path = '/v1/audio-features/'; // api call path
          $path2 = '/v1/tracks/';
          $id = $_GET['song'];

          $combinedURL = $host.$path.$id;
          $combinedURL2 = $host.$path2.$id;
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


          if (isset($_GET['song']))
            {
              $data = $_GET['song'];

              $data = trim($data);
              $data = stripslashes($data);
              $data = htmlspecialchars($data); 
              $percent = 100;
              echo "
              <div class='text-center'>
                <img src=".$spotifyData2['album']['images'][0]['url']." class='img-fluid' alt='Responsive image'>
                <h1>".$spotifyData2['album']['artists'][0]['name'].' - '.$spotifyData2['name']."</h1>
              </div>

              <table class='table'>
                <tbody>
                  <tr>
                    <th>Danceability</th>
                    <td>".$spotifyData['danceability']*$percent."</td>
                  </tr>
                </tbody>
                <tbody>
                  <tr>
                  <th>Energy</th>
                  <td>".$spotifyData['energy']*$percent."</td>
                  </tr>
                </tbody>
                <tbody>
                  <tr>
                  <th>Key</th>
                  <td>".$spotifyData['key']."</td>
                  </tr>
                </tbody>
                <tbody>
                  <tr>
                  <th>Loudness</th>
                  <td>".$spotifyData['loudness']*$percent."</td>
                  </tr>
                </tbody>
                <tbody>
                  <tr>
                  <th>Speechiness</th>
                  <td>".$spotifyData['speechiness']*$percent."</td>
                  </tr>
                </tbody>
                <tbody>
                  <tr>
                  <th>Acousticness</th>
                  <td>".$spotifyData['acousticness']*$percent."</td>
                  </tr>
                </tbody>
                <tbody>
                  <tr>
                  <th>Instrumentalness</th>
                  <td>".$spotifyData['instrumentalness']*$percent."</td>
                  </tr>
                </tbody>
                <tbody>
                  <tr>
                  <th>Liveness</th>
                  <td>".$spotifyData['liveness']."</td>
                  </tr>
                </tbody>
              </table>
                  ";     
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