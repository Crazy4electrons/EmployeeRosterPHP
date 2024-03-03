<div class="flex-collumn">
<?php
session_start();
session_regenerate_id();
print_r([session_id(),session_encode()]);
?>
</div>