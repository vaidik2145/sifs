<?php
$q = $_GET['q'] ?? '';

// Remove only literal < and >
$filtered_q = str_replace(['<','>'], '', $q);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>XSS Lab</title>
<style>
body { font-family: Arial, sans-serif; background:#e0ffff; padding:50px; }
.container { max-width:600px; margin:auto; background:#fff; padding:25px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.2); }
h2 { text-align:center; color:#007acc; }
input[type="text"] { width:100%; padding:12px; border:1px solid #ccc; border-radius:5px; margin-top:10px; font-size:16px; }
button { padding:12px 25px; margin-top:15px; background:#00bfff; color:#fff; border:none; border-radius:5px; cursor:pointer; font-size:16px; }
button:hover { background:#009acd; }
.result { margin-top:20px; padding:15px; border:1px solid #ccc; border-radius:5px; background:#f0ffff; font-family:monospace; white-space:pre-wrap; word-wrap:break-word; }
label { font-weight:bold; display:block; margin-top:10px; }
</style>
</head>
<body>
<div class="container">
  <h2>XSS Lab</h2>
  <form method="get">
    <label for="search">Enter your Name:</label>
    <input type="text" id="search" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Name">
    <button type="submit">Search</button>
  </form>

  <div class="result" id="results"></div>
</div>

<script>
// Take PHP filtered input safely
var rawInput = "<?php echo addslashes($filtered_q); ?>";

// Decode URL-encoded payload
var decodedInput;
try {
    decodedInput = decodeURIComponent(rawInput);
} catch(e) {
    decodedInput = rawInput;
}

// Create temporary div to parse HTML/JS
var tempDiv = document.createElement('div');
tempDiv.innerHTML = decodedInput;

// Dynamically execute any <script> tags
var scripts = tempDiv.querySelectorAll('script');
scripts.forEach(s => {
    var newScript = document.createElement('script');
    newScript.text = s.innerHTML;
    document.body.appendChild(newScript);
});

// Insert remaining content into result div
document.getElementById('results').innerHTML = tempDiv.innerHTML;
</script>
</body>
</html>
