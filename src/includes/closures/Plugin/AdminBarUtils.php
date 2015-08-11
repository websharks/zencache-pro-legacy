<?php
/*[pro strip-from="lite"]*/
namespace WebSharks\ZenCache\Pro;

// @TODO Cache file count meter.

/*
 * Filter WordPress admin bar.
 *
 * @since 150422 Rewrite.
 *
 * @attaches-to `admin_bar_menu` hook.
 *
 * @param $wp_admin_bar \WP_Admin_Bar
 */
$self->adminBarMenu = function (&$wp_admin_bar) use ($self) {
    if (!$self->options['enable']) {
        return; // Nothing to do.
    }
    if (!$self->options['admin_bar_enable']) {
        return; // Nothing to do.
    }
    if (!current_user_can($self->cap) || !is_admin_bar_showing()) {
        return; // Nothing to do.
    }
    if (is_multisite() && current_user_can($self->network_cap)) {
        $wp_admin_bar->add_node(
            array(
                'parent' => 'top-secondary',
                'id'     => GLOBAL_NS.'-wipe',
                'title'  => __('Wipe', SLUG_TD),
                'href'   => '#',
                'meta'   => array(
                        'title'    => __('Wipe Cache (Start Fresh). Clears the cache for all sites in this network at once!', SLUG_TD),
                        'class'    => GLOBAL_NS,
                        'tabindex' => -1,
                ),
            )
        );
    }
    if (!is_multisite() || !is_network_admin()) {
        $wp_admin_bar->add_node(
            array(
                'parent' => 'top-secondary',
                'id'     => GLOBAL_NS.'-clear',
                'title'  => __('Clear Cache', SLUG_TD), 'href' => '#',
                'meta'   => array(
                        'title' => is_multisite() && current_user_can($self->network_cap)
                            ? __('Clear Cache (Start Fresh). Affects the current site only.', SLUG_TD)
                            : __('Clear Cache (Start Fresh)', SLUG_TD),
                        'class'    => GLOBAL_NS,
                        'tabindex' => -1,
                ),
            )
        );
    }
    if ($self->options['dir_stats_admin_bar_enable']) {
        // Build the miniature directory stats graph.
        // Goes into an `<a>` tag, so these need to be inline tags.
        $stats = '<span class="-container">'; // Primary container.
        $stats .= '<span class="-bars"><span></span><span></span><span></span><span></span><span></span></span>';
        $stats .= '<span class="-meter"></span>';
        $stats .= '</span>'; // Close container.

        $wp_admin_bar->add_node(
            array(
                'parent' => 'top-secondary',
                'id'     => GLOBAL_NS.'-dir-stats',
                'title'  => $stats,
                'href'   => '#',
                'meta'   => array(
                        'title'    => __('Cache directory statistics.', SLUG_TD),
                        'class'    => GLOBAL_NS,
                        'tabindex' => -1,
                ),
            )
        );
    }
};

/*
 * Injects `<meta>` tag w/ JSON-encoded data for WordPress admin bar.
 *
 * @since 150422 Rewrite.
 *
 * @attaches-to `admin_head` hook.
 */
$self->adminBarMetaTags = function () use ($self) {
    if (!$self->options['enable']) {
        return; // Nothing to do.
    }
    if (!$self->options['admin_bar_enable']) {
        return; // Nothing to do.
    }
    if (!current_user_can($self->cap) || !is_admin_bar_showing()) {
        return; // Nothing to do.
    }
    $vars = array(
        '_wpnonce' => wp_create_nonce(), // For security.
        'ajaxURL'  => site_url('/wp-load.php', is_ssl() ? 'https' : 'http'),
    );
    echo '<meta property="'.esc_attr(GLOBAL_NS).':vars" content="data-json"'.
         ' data-json="'.esc_attr(json_encode($vars)).'" id="'.esc_attr(GLOBAL_NS).'-vars" />'."\n";
};

/*
 * Adds CSS for WordPress admin bar.
 *
 * @since 150422 Rewrite.
 *
 * @attaches-to `wp_enqueue_scripts` hook.
 * @attaches-to `admin_enqueue_scripts` hook.
 */
$self->adminBarStyles = function () use ($self) {
    if (!$self->options['enable']) {
        return; // Nothing to do.
    }
    if (!$self->options['admin_bar_enable']) {
        return; // Nothing to do.
    }
    if (!current_user_can($self->cap) || !is_admin_bar_showing()) {
        return; // Nothing to do.
    }
    $deps = array(); // Plugin dependencies.

    wp_enqueue_style(GLOBAL_NS.'-admin-bar', $self->url('/src/client-s/css/admin-bar.css'), $deps, VERSION, 'all');
};

/*
 * Adds JS for WordPress admin bar.
 *
 * @since 150422 Rewrite.
 *
 * @attaches-to `wp_enqueue_scripts` hook.
 * @attaches-to `admin_enqueue_scripts` hook.
 */
$self->adminBarScripts = function () use ($self) {
    if (!$self->options['enable']) {
        return; // Nothing to do.
    }
    if (!$self->options['admin_bar_enable']) {
        return; // Nothing to do.
    }
    if (!current_user_can($self->cap) || !is_admin_bar_showing()) {
        return; // Nothing to do.
    }
    $deps = array('jquery'); // Plugin dependencies.

    wp_enqueue_script(GLOBAL_NS.'-admin-bar', $self->url('/src/client-s/js/admin-bar.js'), $deps, VERSION, true);
};
/*[/pro]*/
