<?php require APPROOT . '/views/inc/header.php'; ?>
    <?php 
        if(count($data['users']) != 0) {
            echo "<h1>Users: <pre>";
            print_r($data['users']);
            echo "</pre>";
        }else{
            echo "<h1>No users registered yet</h1>";
        } 
    ?>
<?php require APPROOT . '/views/inc/footer.php'; ?>