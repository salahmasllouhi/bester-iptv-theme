<?php
/**
 * Keyword landing pages — the body band
 *
 * The one section that is genuinely different on each of the eight pages. The
 * rest of the page is the front page's section stack with swapped headlines,
 * which on its own would make eight near-duplicates competing with each other;
 * this is what makes each page about its own keyword.
 *
 * Placed after the onboarding panel on purpose: the buying path (hero →
 * showcase → sport → features → prices → steps) stays intact, and the reading
 * material sits below it rather than between the argument and the ask.
 *
 * Expects from template-keyword-landing.php:
 *   $kw_slug (string)  $kw (array, the definition)
 */

if (empty($kw['blocks'])) {
    return;
}

// Every paragraph goes through the link resolver; wp_kses_post then allows the
// anchors it produced and nothing else.
$kw_para = function ($text) use ($kw_slug) {
    return wp_kses_post(iptv_keyword_links($text, $kw_slug));
};
?>
<section class="kw-content dv2-section" id="ratgeber">
    <div class="container">

        <?php if (!empty($kw['lead'])) : ?>
            <p class="kw-lead"><?php echo $kw_para($kw['lead']); ?></p>
        <?php endif; ?>

        <article class="kw-body">
            <?php foreach ($kw['blocks'] as $block) : ?>

                <h2><?php echo esc_html($block['title']); ?></h2>

                <?php foreach ((array) (isset($block['text']) ? $block['text'] : array()) as $paragraph) : ?>
                    <p><?php echo $kw_para($paragraph); ?></p>
                <?php endforeach; ?>

                <?php if (!empty($block['list'])) : ?>
                    <ul class="kw-list">
                        <?php foreach ($block['list'] as $item) : ?>
                            <li><?php echo $kw_para($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if (!empty($block['items'])) : ?>
                    <div class="kw-cards">
                        <?php foreach ($block['items'] as $item) : ?>
                            <div class="kw-card">
                                <h3><?php echo esc_html($item['title']); ?></h3>
                                <p><?php echo $kw_para($item['text']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php foreach ((array) (isset($block['text_after']) ? $block['text_after'] : array()) as $paragraph) : ?>
                    <p><?php echo $kw_para($paragraph); ?></p>
                <?php endforeach; ?>

            <?php endforeach; ?>
        </article>

    </div>
</section>
