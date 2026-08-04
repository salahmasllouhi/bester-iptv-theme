<?php
/**
 * Rank Math — hand the editor the copy the template renders
 *
 * The score in the editor sidebar is computed in the browser, not in PHP. Both
 * of Rank Math's editor bundles do the same thing:
 *
 *     paper.setText( applyFilters( 'rank_math_content', post.content ) )
 *
 * `post.content` is the body field, which on the front page, the plan pages and
 * the keyword pages is empty — every word comes from ACF and from the section
 * templates. So the sidebar reported zero words, zero keyword density, no
 * subheadings, no images and no links, and eleven tests failed at once.
 *
 * The PHP filter in inc/front-page-seo.php is still right and still needed —
 * Rank Math re-analyses server-side when it recalculates scores in bulk — but it
 * never reaches the editor. This is the other half: the same digest, handed to
 * the JavaScript through the filter Rank Math registers for exactly this case.
 *
 * Nothing is invented here. The digest is the copy the visitor reads, built
 * from the same lookups the sections render from.
 *
 * @package Nordic_IPTV
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('iptv_rank_math_digest_for_post')) {
    /**
     * The template-rendered copy of a post, or '' if it renders none.
     *
     * @param WP_Post|null $post
     * @return string
     */
    function iptv_rank_math_digest_for_post($post)
    {
        $post = get_post($post);

        if (!$post || $post->post_type !== 'page') {
            return '';
        }

        $slug = function_exists('iptv_keyword_slug_for_post')
            ? iptv_keyword_slug_for_post($post)
            : '';

        if ($slug) {
            return iptv_keyword_analysis_digest($slug);
        }

        if (function_exists('iptv_plan_is_plan_page') && iptv_plan_is_plan_page($post)) {
            return iptv_plan_analysis_digest($post->ID);
        }

        if ((int) $post->ID === (int) get_option('page_on_front')) {
            return iptv_front_page_digest();
        }

        return '';
    }
}

/**
 * Register the filter on the post editing screens.
 *
 * Enqueued against wp-hooks rather than against Rank Math's own handle: the
 * filter only has to exist before the analysis runs, and depending on a plugin
 * handle would break the editor for these pages if Rank Math were ever
 * deactivated.
 */
add_action('admin_enqueue_scripts', function ($hook) {
    if (!in_array($hook, array('post.php', 'post-new.php'), true)) {
        return;
    }

    $digest = iptv_rank_math_digest_for_post(get_post());

    if (!$digest) {
        return;
    }

    wp_register_script('iptv-rank-math-content', false, array('wp-hooks'), null, false);
    wp_enqueue_script('iptv-rank-math-content');

    // JSON_HEX_TAG so a '<' in the copy cannot end the inline <script> early.
    $json = wp_json_encode($digest, JSON_HEX_TAG | JSON_UNESCAPED_UNICODE);

    wp_add_inline_script('iptv-rank-math-content', <<<JS
( function ( hooks, copy ) {
	if ( ! hooks || ! copy ) {
		return;
	}

	// Priority 20: after Rank Math's own filter, which sits at 11 and pulls in
	// the reusable-block content. Appended, never substituted, so anything an
	// editor does type into the body field still counts — and still comes
	// first, which is what "keyword at the beginning of the content" measures.
	hooks.addFilter( 'rank_math_content', 'iptv/template-copy', function ( content ) {
		return content + '\\n' + copy;
	}, 20 );

	// Rank Math may have run its first analysis before this file executed, in
	// which case the filter above is registered too late to affect it. Nudge it
	// once the editor exists — by event if we are early, by polling if we are
	// not. The interval gives up after 15s rather than spinning forever.
	var refresh = function () {
		if ( window.rankMathEditor && window.rankMathEditor.refresh ) {
			window.rankMathEditor.refresh( 'content' );
			return true;
		}
		return false;
	};

	hooks.addAction( 'rank_math_loaded', 'iptv/template-copy', refresh );

	if ( ! refresh() ) {
		var poll = setInterval( function () {
			if ( refresh() ) {
				clearInterval( poll );
			}
		}, 500 );

		setTimeout( function () {
			clearInterval( poll );
		}, 15000 );
	}
}( window.wp && window.wp.hooks, {$json} ) );
JS
    );
});

/**
 * Use the German power words on this German site.
 *
 * Rank Math picks the list by site language and this install is set to en_US,
 * so it was matching German titles against English power words — a test no
 * German headline could pass. assets/vendor/powerwords/de.php is the plugin's
 * own list; this only changes which of its files is consulted.
 *
 * Setting the site language to German would fix it at the source, and would
 * also retire the title-sentiment test, which Rank Math only applies to English
 * ("en" === paper.getShortLocale()) and which no German title can pass while
 * the site claims to be English.
 */
add_filter('rank_math/metabox/power_words', function ($words, $locale) {
    if ($locale === 'de') {
        return $words;
    }

    $dir = function_exists('rank_math') ? rank_math()->plugin_dir() : WP_PLUGIN_DIR . '/seo-by-rank-math/';
    $file = $dir . 'assets/vendor/powerwords/de.php';

    if (!file_exists($file)) {
        return $words;
    }

    $german = include $file;

    return is_array($german) ? array_map('strtolower', $german) : $words;
}, 10, 2);
