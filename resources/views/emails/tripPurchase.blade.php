<!doctype html>
<html lang="en">

<head>
  <title>Trip Purchase Mail</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>

<body>
  <!-- resources/views/emails/trip_purchase.blade.php -->

<h1>Trip Purchase Confirmation</h1>

<p>Thank you for purchasing the trip. Here are the details:</p>

<h2>Trip Details:</h2>
<p>Trip Name: {{ $tripDetails['trip_name'] }}</p>
<p>Trip Date: {{ $tripDetails['trip_date'] }}</p>

<!-- Add other trip details -->

<h2>Customer Details:</h2>
<p>Customer Name: {{ $customerDetails['name'] }}</p>

<!-- Add other customer details -->

<h2>Member Details:</h2>
@foreach($members as $member)
    <p>Member Name: {{ $member['first_name'] }} {{ $member['last_name'] }}  Member Gender: {{ $member['gender'] == 1 ? 'Male' : 'Female' }}  Member Activity: {{ $member['activity'] == 1 ? 'Skier' : 'SnowBorder' }}</p>
    <!-- Add other member details -->
@endforeach

<p>Thank you for using our service!</p>

  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
  </script>
</body>

</html>
