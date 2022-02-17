<?php
require_once APPROOT . '/views/includes/header.php';
?>
<h1>Create new Post page</h1>


<form action="/public/post/store" method="post">
    <input type="text" name="title" />
    <button type="submit">New Post</button>
</form>

<?php

echo APPROOT;
require_once APPROOT . '/views/includes/footer.php';

?>