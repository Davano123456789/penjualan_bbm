<?php
// Load Parts
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/sidebar.php';
?>

<!-- Main Content Area -->
<div id="main-content" class="ml-64 p-6 md:p-8 min-h-screen transition-all duration-300">
    <?php 
    // Load Topbar
    require_once __DIR__ . '/topbar.php'; 
    
    // Load View Content
    require_once '../app/views/' . $data['view'] . '.php'; 
    ?>
</div> 

<?php 
// Load Footer
require_once __DIR__ . '/footer.php'; 
?>
