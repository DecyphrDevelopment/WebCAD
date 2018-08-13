<?php
include '../menu.php';
$_SESSION['referer'] = $_SERVER['HTTP_REFERER'];
ob_end_flush();

echo '
<title>PBS Livestream | Login</title>

<!--[if IE]><link rel="shortcut icon" href="images/favicon_IE.ico"><![endif]-->
<link rel="shortcut icon" href="images/favicon_IE.ico">

<!-- Touch Icons - iOS and Android 2.1+ 180x180 pixels in size. -->
<link rel="apple-touch-icon-precomposed" href="images/favicon.png">

<!-- Firefox, Chrome, Safari, IE 11+ and Opera. 196x196 pixels in size. -->
<link rel="icon" href="images/favicon.png">
<head>
<link rel="stylesheet" href="https://bootswatch.com/3/cyborg/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../css/style.css">
<link rel="stylesheet" type="text/css" href="../css/buttons.css">
<style>
    #loginbtn{
      border-color: #13ff13;
    }
    #loginbtn:hover{
      background-color: #003c47;
    }
    #regbtn{
      border-color: #13a9ff;
      background-color: #002b36;
    }
    #regbtn:hover{
      background-color: #003c47;
    }
    .login-box
    {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: #004d58;
      border-radius: 5px;
      color: #ccc;
      display: block;
      padding: 15px 10px;
      width: 500px;
      margin: auto;
      height: auto;
      overflow: hidden;
      text-align: left;
    }
</style>
</head>';
echo '
	<body>
    <br>
    <br>
    <div>
<div class="login-box">
<h2 style=text-align:center;color:tomato;font-weight:100;>Admin/Dispatch Login</h2>
<form name="form1" method="post" action="checklogin.php"><br>
<p class="col-md-12">
<b>Username</b>
<input type=text name="myusername" class="form-control" placeholder="Username" style="width:100%; margin-top: 0px;">
</p>
<p class="col-md-12">
<b>Password</b>
<input type=password name="mypassword" class="form-control" placeholder="Password" style="width:100%; margin-top: 0px;">
</p>
<br style=clear:both;><br>
<p class="col-md-12">
<button id="loginbtn" class="btn btn-primary form-control" style="width:100%; margin-top: 0px;">Login</button>
</p>
</form>
</div>
	</body>
</html>';

?>