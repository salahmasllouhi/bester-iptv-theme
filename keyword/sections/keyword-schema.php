<?php
/**
 * JSON-LD for a keyword landing page
 *
 * FAQPage only. The prices on these pages come from the front page's pricing
 * configurator, which quotes a range rather than one figure, so a Product node
 * here would either repeat what the plan pages already say properly or state a
 * price the page does not actually show.
 *
 * Rank Math emits the page-level WebPage/Breadcrumb graph; this adds only the
 * questions, which it cannot see because they are rendered by the template
 * rather than written into post_content.
 *
 * Expects from template-keyword-landing.php:
 *   $kw (array, the definition)
 */

if (empty($kw['faq'])) {
    return;
}

$questions = array();

foreach ($kw['faq'] as $item) {
    if (empty($item['q'])) {
        continue;
    }

    $questions[] = array(
        '@type'          => 'Question',
        'name'           => wp_strip_all_tags($item['q']),
        'acceptedAnswer' => array(
            '@type' => 'Answer',
            'text'  => wp_strip_all_tags($item['a']),
        ),
    );
}

if (empty($questions)) {
    return;
}

$schema = array(
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    '@id'        => get_permalink() . '#faq',
    'mainEntity' => $questions,
);
?>
<script type="application/ld+json">
<?php echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>
