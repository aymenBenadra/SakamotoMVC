<?php
require_once APPROOT . '/views/includes/header.php';

?>

<form action="/public/post/destroy" method="post">
    <input type="number" name="id">
    <button type="submit">Delete Post</button>
</form>

<?php
echo APPROOT;
require_once APPROOT . '/views/includes/footer.php';

?>