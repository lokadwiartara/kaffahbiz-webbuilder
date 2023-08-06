<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Wp Migration</title>
</head>
<body>
<?php 

for ($i=0; $i<5; $i++) {
   echo $i.'<br>';
   sleep(1);
   @ob_flush();
   flush();
}
?>
</body>
</html>