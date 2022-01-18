<?php
/**
 * Silent is gold
 */

// Admin classes
require plugin_dir_path( __FILE__ ) .'/admin/class.wasp-admin-setting-fields.php';
require plugin_dir_path( __FILE__ ) .'/admin/class.wasp-admin-page.php';
require plugin_dir_path( __FILE__ ) .'/admin/class.wasp-admin-sub-page.php';

// Custom Post Type classes
require plugin_dir_path( __FILE__ ) .'/post-type/class.wasp-post-type.php';

// Meta Boxes
require plugin_dir_path( __FILE__ ) .'/meta-box/class.wasp-meta-box.php';

// General Plugin classes
require plugin_dir_path( __FILE__ ) .'/general/class.wasp-enqueue.php';
