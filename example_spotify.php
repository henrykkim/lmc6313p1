<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Week 2 PHP</title>
    <meta name="description" content="6313 GATech class example">
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
  <body class="mt-2">
    <div class="container">
      <div class="row">
        <div class="col">
          <h3 class="text-muted">Exercise 1 Example</h3>
          <h4 class="text-muted">Twitter cURL API</h4>
        </div>
        <div class="col">
          <p>
            This example pulls data from the Spotify API.
          </p>
        </div>
      </div>
      <?php
        error_reporting(E_ALL);
        ini_set("display_errors", 1);

        // PLEASE get your own API/OAuth Key
        $client_id = '97cceb1c246a419e928c11fe96240e48'; 
        $client_secret = '5d02dc41c78a4c60bb32dde775a6479b';
        $token = null;

        $host = 'https://api.spotify.com';
        $method = 'GET';
        $path = '/v1/search'; // api call path
        $query = array( // query parameters
          'q' => 'roadhouse blues',
          'type' => 'artist'
        );

        
        $query = http_build_query($query, '', '&');
        $query = preg_replace('/\s+/', '+', $query); // Required for search in spotfiy calls (replaces spaces with +)
        $combinedURL = $host.$path."?".$query;
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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $combinedURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Authorization: Bearer ".$token
        ));
        $output = curl_exec($ch);
        curl_close($ch);
        /* cURL code ends. Make sure you decode the results! */

        $spotifyData = json_decode($output,true);
        /*
          JSON data is formatted differently for every API. Consult the documentation 
          https://developer.twitter.com/en/docs
        */
        //var_dump($output);
        echo $output;
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