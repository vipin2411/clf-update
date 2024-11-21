<html>
<body>
<?php
  // Function to get the metadata using IMDSv2
  function get_metadata($url) {
    $token_url = "http://169.254.169.254/latest/api/token";
    $ch = curl_init();
    
    // Get the token
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_PUT, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-aws-ec2-metadata-token-ttl-seconds: 21600"]);
    $token = curl_exec($ch);
    
    if (curl_errno($ch)) {
      return "Error: Unable to fetch metadata token.";
    }
    
    // Fetch the metadata using the token
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-aws-ec2-metadata-token: $token"]);
    $metadata = curl_exec($ch);
    curl_close($ch);

    return $metadata ? $metadata : "Error: Unable to fetch metadata.";
  }

  // Metadata URLs
  $iurl = "http://169.254.169.254/latest/meta-data/instance-id";
  $aurl = "http://169.254.169.254/latest/meta-data/placement/availability-zone";
  $ipurl = "http://169.254.169.254/latest/meta-data/local-ipv4";
  $pipurl = "http://169.254.169.254/latest/meta-data/public-ipv4";

  // Fetch metadata
  $iid = get_metadata($iurl);
  $azone = get_metadata($aurl);
  $lip = get_metadata($ipurl);
  $pip = get_metadata($pipurl);
?>
  <center>
    <h3>EC2 Instance ID: <?php echo $iid; ?></h3>
    <h3>Availability Zone: <?php echo $azone; ?></h3>
    <h3>Private IP: <?php echo $lip; ?></h3>
    <h3>Public IP: <?php echo $pip; ?></h3>
  </center>
</body>
</html>
