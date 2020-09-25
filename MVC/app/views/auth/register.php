<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
</head>
<body>
   <form method="post" action="/MVC/register">
    <input name="email" type="text" placeholder="Email"></input>
    <input name="user" type="text" placeholder="Username"></input>
    <input name="password" type="password" placeholder="Password"></input>
    <button type="submit">Register</button>
   </form>
   <?php if(isset($data['errors'])){ if(is_array($data['errors'])){foreach($data['errors'] as $error){ echo $error . '</br>'; }}else{ echo $data['errors'];} }?>
   <?php if(isset($data['registered'])){ echo $data['registered']; }?>
</body>
</html>