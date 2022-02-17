<?php
require_once APPROOT . '/views/includes/header.php';

echo "<h1>Posts page</h1>";

foreach ($posts as $post) {
    echo '<div class="well">';
    echo '<h3>' . $post->title . '</h3>';
    echo '</div>';
}

echo APPROOT;
require_once APPROOT . '/views/includes/footer.php';
