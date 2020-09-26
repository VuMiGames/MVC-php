<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
</head>
<body>
   <form method="post" action="/MVC/login">
    <input name="user" type="text" placeholder="Username"></input>
    <input name="password" type="password" placeholder="Password"></input>
    <button type="submit">Login</button>
   </form>
   <?php if(isset($data['errors'])){ if(is_array($data['errors'])){foreach($data['errors'] as $error){ echo $error . '</br>'; }}else{ echo $data['errors'];} }?>
   <?php if(isset($data['logged_in'])){ echo $data['logged_in']; }?>
   <h3>Make a new account</h3>
   <a href="/MVC/register">Register</a>
   <a href="/MVC/forgot">Forgot password?</a>
</body>
</html>