<?php
include '../config.php';
include '../logging/log.php';
$_SESSION['referer'] = $_SERVER['HTTP_REFERER'];
function renderForm($username, $email, $unitNumber, $password, $passwordConfirm, $error) {
?>
<html>
    <head>
        <script language="javascript" type="text/javascript" src="../../scripts/js/jQuery.js"></script>
        <link rel="stylesheet" type="text/css" href="../../css/buttons.css">
        <link rel="stylesheet" type="text/css" href="../../css/<?php echo STYLE; ?>">
		<title><?php echo WEBSITE_TITLE; ?> | Register</title>
        <style>
        #submitBtn {
            border-color:#13ff13;
        }
        a:link {
            text-decoration: none;
        }
        a:visited {
    text-decoration: none;
}
a:hover {
    text-decoration: none;
}

a:active {
    text-decoration: none;
}
        </style>
    </head>
	<body>
		<script>
			document.getElementById("settt").style.left = ((200 - document.getElementById("sett").offsetWidth) * -1);
			document.getElementById('tools_set').style.left = ((200 - document.getElementById('tools').offsetWidth) * -1);
		</script>
        <br>
        <br>
        <div>
            <div class="login-box" id="login-box">
                <form action="" method="post">
                    <h2 style=text-align:center;color:tomato;font-weight:100;>Server 1 CAD Registration</h2>
                    <br>
                    <p class="col-md-12">
                        <b>Username</b>
                        <input type=text name="username" class="form-control" placeholder="Username" style="width:100%; margin-top: 0px;" value="<?php echo $username ?>">
                    </p>
                    <p class="col-md-12">
                        <b>E-Mail</b>
                        <input type=text name="email" class="form-control" placeholder="E-Mail" style="width:100%; margin-top: 0px;" value="<?php echo $email ?>">
                    </p>
                    <p class="col-md-12">
                        <b>Unit Number</b>
                        <input type=text name="unitNumber" class="form-control" placeholder="Unit Number" style="width:100%; margin-top: 0px;" value="<?php echo $unitNumber ?>">
                    </p>
                    <p class="col-md-12">
                        <b>Password</b>
                        <input type=password name="password" class="form-control" placeholder="Password" style="width:100%; margin-top: 0px;" value="<?php echo $password ?>">
                    </p>
                    <p class="col-md-12">
                        <b>Confirm Password</b>
                        <input type=password name="passwordConfirm" class="form-control" placeholder="Confirm Password" style="width:100%; margin-top: 0px;" value="<?php echo $passwordConfirm ?>">
                    </p>
                    <p class="col-md-12">
                        <input type="submit" id="submitBtn" name="submit" class="btn btn-primary form-control" style="width:100%; margin-top: 0px;" value="Submit">
                    </p>
                    <p class="col-md-12 center" style="padding:0px;">
                        <font color="#ccc">Already have an account? </font><a href=".." style="color:#ccc">Login</a>
                    </p>
                    <?php
                    if ($error != "") {
                        echo "<p class='center'><font color=red>".$error."</font></p>";
                    }
                    ?>
                </form>
            </div>
        </div>
	</body>
</html>
<?php
}
$server = DB_HOST;
$user = DB_USER;
$pass = DB_PASSWORD;
$db = DB_NAME;
$connection = mysqli_connect($server, $user, $pass, $db);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($connection, htmlspecialchars($_POST['username']));
    $email = mysqli_real_escape_string($connection, htmlspecialchars($_POST['email']));
    $unitNumber = mysqli_real_escape_string($connection, htmlspecialchars($_POST['unitNumber']));
    $password = mysqli_real_escape_string($connection, htmlspecialchars($_POST['password']));
    $passwordConfirm = mysqli_real_escape_string($connection, htmlspecialchars($_POST['passwordConfirm']));
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    if ($username == "") {
        $error = "Please enter a valid username.";
        renderForm($username, $email, $unitNumber, $password, $passwordConfirm, $error);
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid E-Mail.";
        renderForm($username, $email, $unitNumber, $password, $passwordConfirm, $error);
    } else if ($password == "") {
        $error = "Please enter a valid password.";
        renderForm($username, $email, $unitNumber, $password, $passwordConfirm, $error);
    } else if ($password != $passwordConfirm) {
        $error = "Your password do not match.";
        renderForm($username, $email, $unitNumber, $password, $passwordConfirm, $error);
    } else {
        $sql = mysqli_query($connection, "SELECT callsign FROM units WHERE callsign='$unitNumber'")
        or die(mysqli_error($connection));
        if (mysqli_num_rows($sql) >= 1) {
            $error = "That unit number is already taken.";
            renderForm($username, $email, $unitNumber, $password, $passwordConfirm, $error);
        } else {
            $sql = mysqli_query($connection, "SELECT username FROM cad_users WHERE username='$username'")
            or die(mysqli_error($connection));
            if (mysqli_num_rows($sql) >= 1) {
                $error = "That username is already taken.";
                renderForm($username, $email, $unitNumber, $password, $passwordConfirm, $error);
            } else {
                $uuid = uniqid();
                $ip = getRealIpAddr();
                $sql = mysqli_query($connection, "INSERT INTO cad_users VALUES (DEFAULT, '$username', '$hashedPassword', '$uuid', 2, DEFAULT, '$email', '$ip')")
                or die(mysqli_error($connection));
                $sql = mysqli_query($connection, "INSERT INTO units VALUES ('$uuid', '$unitNumber', DEFAULT, DEFAULT, '')")
                or die(mysqli_error($connection));

$to = "joshduncan0529@gmail.com";
$subject = "SCRP CAD System";

$message = '<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldnt be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title></title> <!-- The title tag shows in email notifications, like Android 4.4. -->

    <!-- Web Font / @font-face : BEGIN -->
    <!-- NOTE: If web fonts are not required, lines 10 - 27 can be safely removed. -->

    <!-- Desktop Outlook chokes on web font references and defaults to Times New Roman, so we force a safe fallback font. -->
    <!--[if mso]>
        <style>
            * {
                font-family: sans-serif !important;
            }
        </style>
    <![endif]-->

    <!-- All other clients get the webfont reference; some will render the font and others will silently fail to the fallbacks. More on that here: http://stylecampaign.com/blog/2015/02/webfont-support-in-email/ -->
    <!--[if !mso]><!-->
    <!-- insert web font reference, eg: <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet" type="text/css"> -->
    <!--<![endif]-->

    <!-- Web Font / @font-face : END -->

    <!-- CSS Reset : BEGIN -->
    <style>

        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }

        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* What it does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }

        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        /* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        table table table {
            table-layout: auto;
        }

        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode:bicubic;
        }

        /* What it does: Prevents Windows 10 Mail from underlining links despite inline CSS. Styles for underlined links should be inline. */
        a {
            text-decoration: none;
        }

        /* What it does: A work-around for email clients meddling in triggered links. */
        *[x-apple-data-detectors],  /* iOS */
        .unstyle-auto-detected-links *,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* What it does: Prevents Gmail from displaying a download button on large, non-linked images. */
        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }
        /* If the above doesn"t work, add a .g-img class to any image in question. */
        img.g-img + div {
            display: none !important;
        }

        /* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
        /* Create one of these media queries for each additional viewport size you"d like to fix */

        /* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
        @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
            .email-container {
                min-width: 320px !important;
            }
        }
        /* iPhone 6, 6S, 7, 8, and X */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
            .email-container {
                min-width: 375px !important;
            }
        }
        /* iPhone 6+, 7+, and 8+ */
        @media only screen and (min-device-width: 414px) {
            .email-container {
                min-width: 414px !important;
            }
        }

    </style>
    <!-- CSS Reset : END -->
	<!-- Reset list spacing because Outlook ignores much of our inline CSS. -->
	<!--[if mso]>
	<style type="text/css">
		ul,
		ol {
			margin: 0 !important;
		}
		li {
			margin-left: 30px !important;
		}
		li.list-item-first {
			margin-top: 0 !important;
		}
		li.list-item-last {
			margin-bottom: 10px !important;
		}
	</style>
	<![endif]-->

    <!-- Progressive Enhancements : BEGIN -->
    <style>

	    /* What it does: Hover styles for buttons */
	    .button-td,
	    .button-a {
	        transition: all 100ms ease-in;
	    }
	    .button-td-primary:hover,
	    .button-a-primary:hover {
	        background: #555555 !important;
	        border-color: #555555 !important;
	    }

	    /* Media Queries */
	    @media screen and (max-width: 600px) {

	        /* What it does: Adjust typography on small screens to improve readability */
	        .email-container p {
	            font-size: 17px !important;
	        }

	    }

    </style>
    <!-- Progressive Enhancements : END -->

    <!-- What it does: Makes background images in 72ppi Outlook render at correct size. -->
    <!--[if gte mso 9]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->

</head>
<!--
	The email background color (#222222) is defined in three places:
	1. body tag: for most email clients
	2. center tag: for Gmail and Inbox mobile apps and web versions of Gmail, GSuite, Inbox, Yahoo, AOL, Libero, Comcast, freenet, Mail.ru, Orange.fr
	3. mso conditional: For Windows 10 Mail
-->
<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #222222;">
	<center style="width: 100%; background-color: #222222;">
    <!--[if mso | IE]>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #222222;">
    <tr>
    <td>
    <![endif]-->

        <!-- Visually Hidden Preheader Text : BEGIN -->
        <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
            Thank you for registering in the SCRP CAD system.
        </div>
        <!-- Visually Hidden Preheader Text : END -->

        <!-- Create white space after the desired preview text so email clients don’t pull other distracting text into the inbox preview. Extend as necessary. -->
        <!-- Preview Text Spacing Hack : BEGIN -->
        <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
	        &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
        </div>
        <!-- Preview Text Spacing Hack : END -->

        <!--
            Set the email width. Defined in two places:
            1. max-width for all clients except Desktop Windows Outlook, allowing the email to squish on narrow but never go wider than 600px.
            2. MSO tags for Desktop Windows Outlook enforce a 600px width.
        -->
        <div style="max-width: 600px; margin: 0 auto;" class="email-container">
            <!--[if mso]>
            <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="600">
            <tr>
            <td>
            <![endif]-->

	        <!-- Email Body : BEGIN -->
	        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 auto;">
		        <!-- Email Header : BEGIN -->
	            <tr>
	                <td style="padding: 20px 0; text-align: center">
                    <h1 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 25px; line-height: 30px; color: #ffffff; font-weight: normal;">Southern California Role Play</h1>
	                </td>
	            </tr>
		        <!-- Email Header : END -->

                <!-- Hero Image, Flush : BEGIN -->
                <tr>
                    <td style="background-color: #ffffff;">
                        <img src="http://southerncaliforniarp.com/style/scrp.jpg" width="300" height="" alt="alt_text" border="0" style="width: 100%; max-width: 600px; height: auto; background: #dddddd; font-family: sans-serif; font-size: 15px; line-height: 15px; color: #555555; margin: auto;" class="g-img">
                    </td>
                </tr>
                <!-- Hero Image, Flush : END -->

                <!-- 1 Column Text + Button : BEGIN -->
                <tr>
                    <td style="background-color: #ffffff;">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td style="padding: 20px; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
                                    <h1 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 25px; line-height: 30px; color: #333333; font-weight: normal;">SCRP CAD System</h1>
                                    <p style="margin: 0;">Thank you for registering on the SCRP CAD system. If this wasn' . "'" . 't you, contact our staff team immediately.</p>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </center>
</body>
</html>
';

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <no-reply@southerncaliforniarp.com>' . "\r\n";

mail($to,$subject,$message,$headers);

                header("Location: ..");
            }
        }
    }
}
else {
    renderForm("", "", "", "", "", "");
}
?>
