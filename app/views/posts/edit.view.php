<?php
require_once APPROOT . '/views/includes/header.php';

?>

<form action="/public/post/update" method="post">
    <input type="hidden" name="id" value="<?=$post->id?>">
    <input type="text" name="title" value="<?=$post->title?>" />
    <button type="submit">Update Post</button>
</form>

<?php
echo APPROOT;
require_once APPROOT . '/views/includes/footer.php';

?>