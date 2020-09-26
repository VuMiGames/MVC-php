<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password recovery</title>
</head>
<body>
   <form method="post" action="/MVC/forgot">
    <input name="email" type="text" placeholder="Email"></input>
    <button type="submit">Recover</button>
   </form>
   <?php if(isset($data['errors'])){ if(is_array($data['errors'])){foreach($data['errors'] as $error){ echo $error . '</br>'; }}else{ echo $data['errors'];} }?>
   <?php if(isset($data['success'])){ echo $data['success']; }?>
   <h3>Recovered your password already?</h3>
   <a href="/MVC/login">Login</a>
   <a href="/MVC/register">Register</a>
</body>
</html>