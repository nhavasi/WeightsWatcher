<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<link rel="stylesheet" href="global.css" />
<link rel="stylesheet" href="logSets.css" />

<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-type" content="text/html/css" charset="UTF-8" />
    <title>WW Log Sets</title>
  </head>

  <body>
    <header>LOG SETS</header>

    <div class="group-container">
      CHOOSE A GROUP:
      <a href="chestGroup.php" style="color:white;text-decoration:none;">
        <div class="group-item">CHEST</div>
      </a>
      <a href="armsGroup.php" style="color:white;text-decoration:none;">
        <div class="group-item">ARMS</div>
      </a>
      <a href="backGroup.php" style="color:white;text-decoration:none;">
        <div class="group-item">BACK</div>
      </a>
      <a href="coreGroup.php" style="color:white;text-decoration:none;">
        <div class="group-item">CORE</div>
      </a>
      <a href="legsGroup.php" style="color:white;text-decoration:none;">
        <div class="group-item">LEGS</div>
      </a>
      <a href="cardioGroup.php" style="color:white;text-decoration:none;">
        <div class="group-item">CARDIO</div>
      </a>
    </div>

    <div class="exr-container">
      <span class="exrHeader">EXERCISE:</span>
      <input type="text" class="search" placeholder=" Search..." />
      <form method="POST" action="logSets.php" style="grid-column: 1 / span 2" disabled>
        <input type="text" name="typeNew" class="typeNew" placeholder="+" disabled/>
        <input type="submit" name="enterNew" style="display: none" disabled/>
      </form>
    </div>
  </body>
</html>
