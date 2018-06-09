<?php
$time=time() + (86400 * 30);

// **PREVENTING SESSION HIJACKING**
// Prevents javascript XSS attacks aimed to steal the session ID
ini_set('session.cookie_httponly', 1);

// **PREVENTING SESSION FIXATION**
// Session ID cannot be passed through URLs
ini_set('session.use_only_cookies', 1);

// Uses a secure connection (HTTPS) if possible
ini_set('session.cookie_secure', 1);


function cookie_reset(){
	global $cookie_sure;
	if(isset($_SERVER['HTTP_COOKIE'])){
		$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
		foreach($cookies as $cookie){
			$parts = explode('=', $cookie);
			if($parts){
			$name = trim($parts[0]);
				if($name){
				if($name=='cook_rem'){continue;}
					setcookie($name,'',time()-$time);
					setcookie($name,'',time()-$time, '/');
				}
			}
		}
	}
	echo "<script>window.location.href='index.php';</script>";exit;
}

function getip(){$ip="0";
if(isset($_SERVER['HTTP_CLIENT_IP']) && isset($_SERVER['HTTP_X_FORWARDED_FOR']) && isset($_SERVER['REMOTE_ADDR'])){
    if (!empty($_SERVER['HTTP_CLIENT_IP'])){
		$ip=$_SERVER['HTTP_CLIENT_IP'];
	}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
		$ip=$_SERVER['REMOTE_ADDR'];
	}
}else{$ip="0";}
return $ip;
}


function key_gen(){
$e="";
if(isset($_SERVER['HTTP_USER_AGENT'])){
if(!empty($_SERVER['HTTP_USER_AGENT'])){
  $e=$_SERVER['HTTP_USER_AGENT'];
  }else{$e="0";}
}
else{$e="0";}
    return sha1(getip().$e);
}

//generate-new
$new=key_gen();
if(isset($_COOKIE['key'])){
if($new !== $_COOKIE['key'] && !empty($_COOKIE['key'])){
    cookie_reset();
	echo "<script>window.location.href='index.php';</script>";exit;
}
}

if(isset($_GET['logout'])){
setcookie("login_name","",-1, "/");
setcookie("login","",-1, "/");
header('Location: index.php');
exit;
}

if(isset($_GET['login']) =='1' && isset($_GET['v1']) && isset($_GET['v2'])){
$v1=rawurldecode ($_GET['v1']);
$v2=$_GET['v2'];
$usr_c=base64_encode($v1.'-xx-'.$new);
setcookie("login_name",$usr_c,$time, "/");
setcookie("login",'1',$time, "/");
setcookie("key",$new,$time,'/');
header('Location: index.php');
exit;
}





?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Bare - Start Bootstrap Template</title>
    <!-- Bootstrap core CSS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <!-- Custom styles for this template -->
    <style>
      body {padding-top: 54px;}
	  @media (min-width: 992px) {
		  body {padding-top: 56px;}
      }
    </style>
  </head>
  <body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="#">Start Bootstrap</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#">Home
                <span class="sr-only">(current)</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Contact</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <div class="container">
      <div class="row">
	  <?php
	  $login=0;
			if(isset($_COOKIE['login']) && isset($_COOKIE['login_name'])){
				if(!empty($_COOKIE['login']) && !empty($_COOKIE['login_name'])){
					$name=base64_decode($_COOKIE['login_name']);
					$ar=explode('-xx-',$name);
					if(isset($ar[0]) && isset($ar[1])){
						if($ar[1] !== $new){
							cookie_reset();
							echo "<script>window.location.href='index.php';</script>";
							exit;
						}
						$usr_name=$ar[0];
						$login=1;
					}
				}
			}

		if($login == 1){
			echo  "<h3>Panel-screen</h3>xx";
		?>
        <div class="col-lg-12 text-center w3-teal w3-text-white">
			<h3>Panel-screen</h3>
			<h2 class='w3-grey w3-inline' ><?php print($usr_name); ?></h2>
			<hr>
			<a class='w3-margin w3-padding w3-white' href='?logout=1'>Logout-exit</a>
			<br><br><br>
        </div>
		<?php }else{ ?>
		<div class='w3-grey w3-block w3-margin w3-padding'>
		Test-Login
		<label class='w3-block w3-margin w3-padding'>
		<b>name</b>
		<input type='text' name='v1' class='form-control'>
		</label>
		<label class='w3-block w3-margin w3-padding'>
		<b>Pass</b>
		<input type='password' name='v2' class='form-control'>
		</label>
		<div id='teter3' class='w3-btn w3-red'>LOGİN TRY</div>
		</div>
		<?php } ?>
      </div>
    </div>

<script>
$('#teter3').click(function(){
	window.location.href+='?login=1&v1='+$('input[name=v1]').val()+'&v2='+$('input[name=v2]').val();
});
</script>
</body>

</html>
