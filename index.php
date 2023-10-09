<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Weather Json</title>
</head>

<body>
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <label for="city_name">Enter the city name:</label>
    <input type="text" id="city_name" name="city_name" placeholder="Eg.: paris, london, tokyo" autocomplete="off">
    <button type="submit">Search</button>
  </form>
  <hr>
  <?php
  if($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "config.php";

    $receivedString = $_POST["city_name"];
    $city = str_replace(" ", "+", $receivedString);

    if ($receivedString) {
      $link = "https://api.openweathermap.org/data/2.5/weather?q=$city&APPID=$apiKey";

      $ch = curl_init();

      curl_setopt_array($ch, [
        CURLOPT_URL => $link, 
        CURLOPT_RETURNTRANSFER => true
      ]);
      
      $data = curl_exec($ch);

      curl_close($ch);
    
      $response = json_decode($data, true);
    
      if ($response !== null) {
        $cityName = $response["name"];
        $country = $response["sys"]["country"];
        $description = $response["weather"][0]["main"];
        $temperature = $response["main"]["temp"] - 273.5;
        $feelsLike = $response["main"]["feels_like"] - 273.5;
        $tempMax = $response["main"]["temp_max"] - 273.5;
        $tempMin = $response["main"]["temp_min"] - 273.5;
        $humidity = $response["main"]["humidity"];
    
        echo "<br>Weather in $cityName $country | $description<br>";
        echo "Temperature: $temperature °C<br>";
        echo "Feels Like: $feelsLike °C<br>";
        echo "Max°C: $tempMax °C<br>";
        echo "Min°C: $tempMin °C<br>";
        echo "Humidity: $humidity<br><br>";
        var_dump($data);
      } else {
        echo "ERROR: API request failed.";
      }
    } else {
      echo "Enter a city name!";
    }
  }
  ?>
</body>

</html>