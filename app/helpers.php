<?php
function time_Ago($time) {

  // Calculate difference between current
  // time and given timestamp in seconds
  $diff     = time() - $time;

  // Time difference in seconds
  $sec     = $diff;

  // Convert time difference in minutes
  $min     = round($diff / 60 );

  // Convert time difference in hours
  $hrs     = round($diff / 3600);

  // Convert time difference in days
  $days     = round($diff / 86400 );

  // Convert time difference in weeks
  $weeks     = round($diff / 604800);

  // Convert time difference in months
  $mnths     = round($diff / 2600640 );

  // Convert time difference in years
  $yrs     = round($diff / 31207680 );

  // Check for seconds
  if($sec <= 60) {
      echo $sec;
  }

  // Check for minutes
  else if($min <= 60) {
      if($min==1) {
          echo "1";
      }
      else {
          echo $min;
      }
  }

  // Check for hours
  else if($hrs <= 24) {
      if($hrs == 1) {
          echo "1";
      }
      else {
          echo $hrs;
      }
  }

  // Check for days
  else if($days <= 7) {
      if($days == 1) {
          echo "1";
      }
      else {
          echo $days;
      }
  }

  // Check for weeks
  else if($weeks <= 4.3) {
      if($weeks == 1) {
          echo "1";
      }
      else {
          echo $weeks;
      }
  }

  // Check for months
  else if($mnths <= 12) {
      if($mnths == 1) {
          echo "1";
      }
      else {
          echo $mnths;
      }
  }

  // Check for years
  else {
      if($yrs == 1) {
          echo "1";
      }
      else {
          echo $yrs;
      }
  }
}
?>
