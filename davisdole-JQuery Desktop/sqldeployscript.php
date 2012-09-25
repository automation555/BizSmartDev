<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>


function actionhook_ClientSignup($vars) {

# This function runs when a new client is added
# $vars["ClientID"]
# $vars["FirstName"]
# $vars["LastName"]
# $vars["CompanyName"]
# $vars["Email"]
# $vars["Address1"]
# $vars["Address2"]
# $vars["City"]
# $vars["State"]
# $vars["Postcode"]
# $vars["Country"]
# $vars["PhoneNumber"]
# $vars["Password"]
$vars["custom1"];
$vars["custom2"];
$vars["custom4"];
if ($_POST["custom4"] == "2"){
// do nothing
} else{

### Setting up to send custom email and insert ###
// The Group ID to insert the user as belonging to. Leave as zero for no group.
$ttgid = 0;
// The Database name
$ttdb_db = "put your db name here";
// The MySQL username with access to this database
$ttdb_user = "user name here";
// The MySQL user's password
$ttdb_pass = "password";
// The host the database is on - most of the time - its localhost; other wise add IP:Port
$ttdb_host = "localhost";

##################### 

$ttdb = mysql_connect($ttdb_host, $ttdb_user, $ttdb_pass);
mysql_select_db($ttdb_db, $ttdb);
$query = mysql_query(("insert into MY CUSTOM TABLE set firstname = '".$_POST["firstname"]."', lastname = '".$_POST["lastname"]."', custom1 = '".$_POST["custom1"]."', custom2 = '".intval($_POST["custom2"])."', custom4 = '".intval($_POST["custom4"])."', zipcode = '".$_POST["postcode"]."', email = '".$_POST["email"].'"), $ttdb);

// prep to send email
$to = $_POST["email"];
$subject = "Registration Complete";

$message = '';
$message .= '<br>';
$message .= '<br>';
$message .= '<p>Hello '.$_POST["firstname"].' '.$_POST["lastname"].'.';
$message .= '<br>';
$message .= '<br>';
$message .= '<p>First name: '.$_POST["firstname"];
$message .= '<p>Last name: '.$_POST["lastname"];
$message .= '<p>Email: '.$_POST["email"];
$message .= '<br>';
$message .= '<br>';
$message .= '<br><p>Regards,<br>';
$message .= 'Support Team';
$message .= '<br>';
$message .= '<br>';
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=windows-1251\r\n";
$headers .= "From: Registration Team <registration@yourdomain.com>\n";
$headers .= "Bcc: you@yourdomain.com\n";
mail($to, $subject, $message, $headers);

mysql_query($query, $ttdb);
mysql_close($ttdb); 

}

}