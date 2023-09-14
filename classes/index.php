<?php
/**
 * Silent is gold
 */

// Admin classes
require plugin_dir_path( __FILE__ ) .'/admin-page/index.php';

// Setting Fields classes
require plugin_dir_path( __FILE__ ) .'/setting-fields/index.php';

// Custom Post Type classes
require plugin_dir_path( __FILE__ ) .'/post-type/index.php';

// Custom Taxonomies
require plugin_dir_path( __FILE__ ) .'/taxonomy/index.php';

// Meta Boxes
require plugin_dir_path( __FILE__ ) .'/meta-box/index.php';

// Term Meta Boxes
require plugin_dir_path( __FILE__ ) .'/term-meta/index.php';

// User Meta Boxes
require plugin_dir_path( __FILE__ ) .'/user-meta/index.php';

// General Plugin classes
require plugin_dir_path( __FILE__ ) .'/general/index.php';

// Helper classes
require plugin_dir_path( __FILE__ ) .'/helpers/index.php';
