<br>
<!-- div menu start -->
<div class="menu">
    <?php if($home != true) {echo '<a href="index.php">Hjem</a> | ';}; if(!isset($_SESSION['nickname']) or $left == true){echo '<a href="register.php">Registrer</a> | <a href="login.php">Logg inn</a>';}else{ echo '<a href="logout.php">Logg out</a>';}; if(!isset($_SESSION['nickname']) or $left == true){echo' | <a href="forgot.php">Glemt passord</a>';};
?>
</div>
<!-- div menu end -->
<!-- div wrapper end -->
</div>
<!-- div main_wrapper end -->
</div>
</body>
</html>
