<?php
/**
 * Translate ACF Fields – page editor metabox
 *
 * Polylang's own machine translation (DeepL) covers post title, content, excerpt
 * and the string-translations table. It does not touch ACF meta — and on this site
 * every word of front page copy is ACF meta. This metabox closes that gap.
 *
 * It reads the English source page's ACF values, sends them through
 * Theme_OpenAI_Translator::translate_batch() (DeepSeek or OpenAI, see
 * inc/openai-translator.php) and writes the result to the page being edited.
 *
 * @package Nordic_IPTV
 */

if (!defined('ABSPATH')) {
    exit;
}

class IPTV_ACF_Translate_Metabox
{
    /**
     * Values per request. One 90-field payload invites a truncated JSON reply,
     * which is the failure translate_batch()'s regex fallback exists to absorb —
     * better not to provoke it.
     */
    const BATCH_SIZE = 25;

    /**
     * Field types whose values are not prose and must never be sent to a model.
     */
    private $skip_types = array(
        'image', 'file', 'gallery', 'link', 'url', 'number', 'range', 'true_false',
        'select', 'checkbox', 'radio', 'button_group', 'date_picker', 'date_time_picker',
        'time_picker', 'color_picker', 'post_object', 'page_link', 'relationship',
        'taxonomy', 'user', 'google_map', 'oembed', 'tab', 'message', 'accordion', 'clone',
    );

    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'register'));
        add_action('wp_ajax_iptv_translate_acf_fields', array($this, 'ajax_translate'));
    }

    /**
     * Show the box on any post type Polylang manages translations for.
     */
    public function register()
    {
        if (!function_exists('pll_get_post_translations') || !function_exists('get_field_objects')) {
            return;
        }

        foreach (array('page', 'post') as $post_type) {
            add_meta_box(
                'iptv-acf-translate',
                '🤖 Translate ACF Fields',
                array($this, 'render'),
                $post_type,
                'side',
                'high'
            );
        }
    }

    /**
     * Find the post this one should be translated from.
     *
     * Prefers the site's default language; falls back to any other translation so
     * the box still works on a two-language site whose default is not English.
     *
     * @return array{id:int,lang:string}|null
     */
    private function get_source($post_id)
    {
        $translations = pll_get_post_translations($post_id);
        if (empty($translations)) {
            return null;
        }

        $current = function_exists('pll_get_post_language') ? pll_get_post_language($post_id) : '';
        $default = function_exists('pll_default_language') ? pll_default_language() : 'en';

        if (isset($translations[$default]) && (int) $translations[$default] !== (int) $post_id) {
            return array('id' => (int) $translations[$default], 'lang' => $default);
        }

        foreach ($translations as $lang => $id) {
            if ((int) $id !== (int) $post_id && $lang !== $current) {
                return array('id' => (int) $id, 'lang' => $lang);
            }
        }

        return null;
    }

    public function render($post)
    {
        $translator = new Theme_OpenAI_Translator();

        if (!$translator->is_configured()) {
            printf(
                '<p>No %s API key configured. Add one under <a href="%s">Settings → AI Translation</a>.</p>',
                esc_html($translator->get_provider_label()),
                esc_url(admin_url('options-general.php?page=openai-api-settings'))
            );
            return;
        }

        $source = $this->get_source($post->ID);

        if (!$source) {
            echo '<p>This page has no linked translation yet. Link one in the Languages box first.</p>';
            return;
        }

        $target_lang = $translator->get_target_language(pll_get_post_language($post->ID));
        $source_lang = $translator->get_target_language($source['lang']);

        if ($target_lang === $source_lang) {
            echo '<p>Source and target resolve to the same language. Add the language to the translator map in <code>inc/openai-translator.php</code>.</p>';
            return;
        }
        ?>
        <p style="margin-top:0;">
            Translating from <strong><?php echo esc_html(get_the_title($source['id'])); ?></strong>
            (<?php echo esc_html($source_lang); ?>) into <strong><?php echo esc_html($target_lang); ?></strong>
            via <?php echo esc_html($translator->get_provider_label()); ?>.
        </p>

        <p>
            <button type="button" class="button button-primary" id="iptv-translate-empty" style="width:100%;">
                Fill empty fields
            </button>
        </p>
        <p class="description" style="margin-top:-8px;">
            Only writes fields that are currently blank. Existing copy is never touched.
            Repeaters (FAQ, reviews) are skipped entirely if they already have rows.
        </p>

        <p>
            <button type="button" class="button" id="iptv-translate-all" style="width:100%;">
                Retranslate everything
            </button>
        </p>
        <p class="description" style="margin-top:-8px;">
            Overwrites every text field, including hand-written SEO copy.
        </p>

        <div id="iptv-translate-status" style="margin-top:10px;"></div>

        <script>
            (function () {
                function run(overwrite) {
                    var status = document.getElementById('iptv-translate-status');
                    var msg = overwrite
                        ? 'Overwrite every translated field on this page? Hand-written copy will be replaced.'
                        : 'Fill all blank fields from the source page?';

                    if (!window.confirm(msg)) {
                        return;
                    }

                    status.innerHTML = '<em>Translating… this can take a minute.</em>';

                    var body = new URLSearchParams({
                        action: 'iptv_translate_acf_fields',
                        nonce: '<?php echo esc_js(wp_create_nonce('iptv_translate_acf')); ?>',
                        post_id: '<?php echo (int) $post->ID; ?>',
                        overwrite: overwrite ? '1' : '0'
                    });

                    fetch(ajaxurl, { method: 'POST', body: body, credentials: 'same-origin' })
                        .then(function (r) { return r.json(); })
                        .then(function (res) {
                            if (!res.success) {
                                status.innerHTML = '<span style="color:#b32d2e;">⚠️ ' + res.data + '</span>';
                                return;
                            }
                            status.innerHTML = '<span style="color:#1a7f37;">✅ ' + res.data.message +
                                '</span><br><em>Reload to see the new values.</em>';
                        })
                        .catch(function (e) {
                            status.innerHTML = '<span style="color:#b32d2e;">⚠️ ' + e.message + '</span>';
                        });
                }

                document.getElementById('iptv-translate-empty').addEventListener('click', function () { run(false); });
                document.getElementById('iptv-translate-all').addEventListener('click', function () { run(true); });
            })();
        </script>
        <?php
    }

    /**
     * Walk a page's ACF field objects and collect the translatable strings.
     *
     * Plain fields are keyed by name. Repeater rows are flattened to
     * `name.0.subname` so a whole FAQ travels in one batch and the model keeps its
     * terminology consistent across question and answer.
     *
     * Repeaters are all-or-nothing in fill-empty mode: a partly filled target
     * repeater is left alone rather than merged row by row. Merging would need the
     * row counts to line up, and when they don't ACF silently drops writes to rows
     * that do not exist yet.
     *
     * @param array      $fields        get_field_objects() output for the source.
     * @param array      $out           Collected key => source text.
     * @param array      $plan          Write plan, see $this->apply().
     * @param array|null $target_values get_fields() for the target, null when overwriting.
     * @param bool       $overwrite     Translate everything rather than blanks only.
     */
    private function collect($fields, &$out, &$plan, $target_values, $overwrite)
    {
        if (!is_array($fields)) {
            return;
        }

        foreach ($fields as $name => $field) {
            $type = $field['type'] ?? '';

            if (in_array($type, $this->skip_types, true)) {
                continue;
            }

            if ($type === 'repeater') {
                $rows = $field['value'];
                if (!is_array($rows) || empty($rows)) {
                    continue;
                }

                if (!$overwrite) {
                    $existing = $target_values[$name] ?? null;
                    if (is_array($existing) && !empty($existing)) {
                        continue; // target already has rows; leave them alone
                    }
                }

                $collected = false;
                foreach ($rows as $i => $row) {
                    if (!is_array($row)) {
                        continue;
                    }
                    foreach ($field['sub_fields'] as $sub) {
                        if (!in_array($sub['type'], array('text', 'textarea', 'wysiwyg'), true)) {
                            continue;
                        }
                        $value = $row[$sub['name']] ?? '';
                        if (!is_string($value) || trim($value) === '') {
                            continue;
                        }
                        $out[$name . '.' . $i . '.' . $sub['name']] = $value;
                        $collected = true;
                    }
                }

                if ($collected) {
                    // Seed the write plan with the source rows so untranslatable
                    // sub-fields (numbers, links, toggles) survive the round trip.
                    $plan[$name] = array(
                        'type' => 'repeater',
                        'key'  => $field['key'],
                        'rows' => $rows,
                    );
                }
                continue;
            }

            if (!in_array($type, array('text', 'textarea', 'wysiwyg'), true)) {
                continue;
            }

            $value = $field['value'] ?? '';
            if (!is_string($value) || trim($value) === '') {
                continue;
            }

            if (!$overwrite) {
                $existing = $target_values[$name] ?? null;
                if (is_string($existing) && trim($existing) !== '') {
                    continue;
                }
            }

            $out[$name]  = $value;
            $plan[$name] = array('type' => 'field', 'key' => $field['key']);
        }
    }

    /**
     * Write the translated values back.
     *
     * Everything goes through update_field() — never update_post_meta(), which
     * would leave ACF's `_fieldname` => `field_key` reference pair unwritten and
     * the value invisible in the editor. Repeaters are written whole, once, from
     * the source rows with translated strings substituted in.
     *
     * @return int Number of fields written.
     */
    private function apply($plan, $translated, $post_id)
    {
        $written = 0;

        foreach ($plan as $name => $entry) {
            if ($entry['type'] === 'field') {
                $new = $translated[$name] ?? null;
                if (is_string($new) && $new !== '') {
                    if (update_field($entry['key'], $new, $post_id)) {
                        $written++;
                    }
                }
                continue;
            }

            // Repeater: rebuild every row, swapping in translations where we have them.
            $rows = $entry['rows'];
            $changed = false;

            foreach ($rows as $i => $row) {
                if (!is_array($row)) {
                    continue;
                }
                foreach ($row as $sub_name => $sub_value) {
                    $new = $translated[$name . '.' . $i . '.' . $sub_name] ?? null;
                    if (is_string($new) && $new !== '' && $new !== $sub_value) {
                        $rows[$i][$sub_name] = $new;
                        $changed = true;
                    }
                }
            }

            if ($changed && update_field($entry['key'], $rows, $post_id)) {
                $written += count($rows);
            }
        }

        return $written;
    }

    public function ajax_translate()
    {
        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Insufficient permissions.', 403);
        }
        check_ajax_referer('iptv_translate_acf', 'nonce');

        $post_id   = isset($_POST['post_id']) ? (int) $_POST['post_id'] : 0;
        $overwrite = !empty($_POST['overwrite']) && $_POST['overwrite'] === '1';

        if (!$post_id || !current_user_can('edit_post', $post_id)) {
            wp_send_json_error('Cannot edit this post.');
        }

        $source = $this->get_source($post_id);
        if (!$source) {
            wp_send_json_error('No linked translation to translate from.');
        }

        $translator = new Theme_OpenAI_Translator();
        if (!$translator->is_configured()) {
            wp_send_json_error('No API key configured.');
        }

        $target_lang = $translator->get_target_language(pll_get_post_language($post_id));
        $source_lang = $translator->get_target_language($source['lang']);

        if ($target_lang === $source_lang) {
            wp_send_json_error('Source and target resolve to the same language.');
        }

        $source_fields = get_field_objects($source['id']);
        if (empty($source_fields)) {
            wp_send_json_error('The source page has no ACF values to copy.');
        }

        $target_values = $overwrite ? null : get_fields($post_id);
        if ($target_values === false) {
            $target_values = array();
        }

        $strings = array();
        $plan    = array();
        $this->collect($source_fields, $strings, $plan, $target_values, $overwrite);

        if (empty($strings)) {
            wp_send_json_success(array('message' => 'Nothing to translate — every field already has a value.'));
        }

        $results = array();
        $failed  = 0;

        foreach (array_chunk($strings, self::BATCH_SIZE, true) as $chunk) {
            try {
                $translated = $translator->translate_batch($chunk, $target_lang, $source_lang);
            } catch (Exception $e) {
                error_log('ACF translate failed: ' . $e->getMessage());
                $failed += count($chunk);
                continue;
            }

            foreach ($chunk as $path => $original) {
                $new = $translated[$path] ?? '';
                // translate_batch() echoes the input back when a value could not be
                // translated; writing that would just copy English across.
                if (is_string($new) && $new !== '' && $new !== $original) {
                    $results[$path] = $new;
                }
            }
        }

        $written = $this->apply($plan, $results, $post_id);

        if ($written === 0 && $failed > 0) {
            wp_send_json_error(
                'Translation failed for all ' . $failed . ' fields. Last error: ' .
                ($translator->get_last_error() ?: 'unknown')
            );
        }

        $message = $written . ' field' . ($written === 1 ? '' : 's') . ' translated';
        if ($failed > 0) {
            $message .= ', ' . $failed . ' failed (see the error log)';
        }
        $message .= '.';

        wp_send_json_success(array('message' => $message));
    }
}

new IPTV_ACF_Translate_Metabox();
