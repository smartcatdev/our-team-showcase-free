<?php

namespace ots;

const VERSION = '4.4.1';


interface Options {

    /**
     * @since 4.0.0
     */
    const REWRITE_SLUG = 'ots-team-rewrite-slug';

    /**
     * @since 4.0.0
     */
    const TEMPLATE = 'ots-team-template';

    /**
     * @since 4.0.0
     */
    const GRID_COLUMNS = 'ots-team-grid-columns';

    /**
     * @since 4.0.0
     */
    const MARGIN = 'ots-team-margin';

    /**
     * @since 4.0.0
     */
    const SHOW_SOCIAL = 'ots-team-show-social';

    /**
     * @since 4.0.0
     */
    const SOCIAL_LINK_ACTION = 'ots-team-social-link-action';

    /**
     * @since 4.0.0
     */
    const DISPLAY_NAME = 'ots-team-display-name';

    /**
     * @since 4.0.0
     */
    const DISPLAY_TITLE = 'ots-team-display-title';

    /**
     * @since 4.0.0
     */
    const DISPLAY_LIMIT = 'ots-team-display-limit';

    /**
     * @since 4.0.0
     */
    const MAIN_COLOR = 'ots-team-main-color';

    /**
     * @since 4.0.0
     */
    const SINGLE_TEMPLATE = 'ots-single-template';

    /**
     * @since 4.0.0
     */
    const DEFAULT_AVATAR = 'ots-default-avatar';

    /**
     * @since 4.0.0
     */
    const SHOW_SINGLE_SOCIAL = 'ots-show-single-social';

    /**
     * @since 4.0.0
     */
    const PLUGIN_VERSION = 'ots-plugin-version';

    /**
     * @since 4.0.0
     */
    const NUKE = 'ots-nuke-install';
    
    
    /**
     * @since 4.3.0
     */
    const EXPORT_BUTTON = 'ots-export-button';
    
    /**
     * @since 4.3.0
     */
    const IMPORT_BUTTON = 'ots-import-button';
    
}

interface Defaults {

    /**
     * @since 4.0.0
     */
    const REWRITE_SLUG = 'team-member';

    /**
     * @since 4.0.0
     */
    const TEMPLATE = 'grid';

    /**
     * @since 4.0.0
     */
    const GRID_COLUMNS = 3;

    /**
     * @since 4.0.0
     */
    const MARGIN = 5;

    /**
     * @since 4.0.0
     */
    const SHOW_SOCIAL = 'on';

    /**
     * @since 4.0.0
     */
    const SOCIAL_LINK_ACTION = 'on';

    /**
     * @since 4.0.0
     */
    const DISPLAY_NAME = 'on';

    /**
     * @since 4.0.0
     */
    const DISPLAY_TITLE = 'on';

    /**
     * @since 4.0.0
     */
    const DISPLAY_LIMIT = 'all';

    /**
     * @since 4.0.0
     */
    const MAIN_COLOR = '#1f7dcf';

    /**
     * @since 4.0.0
     */
    const SINGLE_TEMPLATE = 'standard';

    /**
     * @since 4.0.0
     */
    const SHOW_SINGLE_SOCIAL = 'on';


}
