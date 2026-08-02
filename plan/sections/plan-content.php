<?php
/**
 * Long-form body copy
 *
 * The one section that renders the_content(). Everything else on a plan page
 * comes from ACF or code, which is why Rank Math scored these pages at zero
 * before this existed: its content tests read post_content, and post_content
 * was empty on all 24 pages.
 *
 * Placed after the compare table so the buying decision comes first and the
 * reading comes second. Renders nothing at all when the page has no body copy,
 * so a plan page without it looks exactly as it did before.
 */

if (!get_the_content()) {
    return;
}
?>
<section class="plan-content dv2-section">
    <div class="container">
        <article class="plan-content-body">
            <?php the_content(); ?>
        </article>
    </div>
</section>
