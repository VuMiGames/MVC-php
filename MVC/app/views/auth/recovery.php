<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recovery</title>
</head>
<body>
   <form method="post" action="/MVC/recovery">
    <input name="token" type="hidden" value="<?php echo $data['token']?>"></input>
    <input name="password" type="password" placeholder="Password"></input>
    <button type="submit">Update password</button>
   </form>
   <?php if(isset($data['errors'])){ if(is_array($data['errors'])){foreach($data['errors'] as $error){ echo $error . '</br>'; }}else{ echo $data['errors'];} }?>
   <?php if(isset($data['success'])){ echo $data['success']; }?>
</body>
</html>