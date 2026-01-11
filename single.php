<?php
/**
 * Single Blog Post Template
 * Clean, readable design with sidebar
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php the_title(); ?> - Nordic IPTV</title>
    <?php wp_head(); ?>

    <!-- Include Front Page CSS Files -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/front-page/css/variables.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/front-page/css/header.css">

    <style>
        :root {
            --dark: #0F172A;
            --blue-500: #3B82F6;
            --blue-600: #2563EB;
            --blue-700: #1D4ED8;
            --violet-500: #7C3AED;
            --violet-600: #6D28D9;
            --white: #FFFFFF;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-500: #6B7280;
            --gray-700: #374151;
            --gray-900: #111827;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: var(--gray-50);
            color: var(--gray-900);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Header Styles */
        .site-header {
            position: fixed;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 30px);
            max-width: 1200px;
            z-index: 1000;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(20px);
            border-radius: 80px;
            padding: 0.75rem 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--white);
            text-decoration: none;
        }

        .logo-img {
            height: 32px;
            width: auto;
            display: block;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            text-decoration: none;
            font-size: 0.875rem;
        }

        .nav-links a:hover {
            color: var(--white);
        }

        .btn-header {
            background: var(--violet-500);
            color: var(--white);
            padding: 0.625rem 1.25rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .mobile-menu-toggle {
            display: none;
        }

        /* Post Header */
        .post-header {
            background: linear-gradient(135deg, var(--dark) 0%, var(--blue-700) 100%);
            padding: 7rem 0 3rem;
            margin-bottom: 3rem;
        }

        .post-header-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }

        .post-category {
            display: inline-block;
            background: var(--violet-500);
            color: var(--white);
            padding: 0.375rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .post-header h1 {
            color: var(--white);
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .post-meta {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
        }

        .post-meta span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Main Layout */
        .post-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 3rem;
            margin-bottom: 4rem;
        }

        /* Post Content */
        .post-content {
            background: var(--white);
            border-radius: 1rem;
            padding: 3rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        /* Featured Image */
        .post-featured-image {
            width: 100%;
            border-radius: 0.75rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* Article Typography */
        .post-body {
            color: var(--gray-700);
            font-size: 1.0625rem;
            line-height: 1.8;
        }

        .post-body h2 {
            color: var(--gray-900);
            font-size: 1.75rem;
            font-weight: 700;
            margin: 2.5rem 0 1rem;
        }

        .post-body h3 {
            color: var(--gray-900);
            font-size: 1.375rem;
            font-weight: 600;
            margin: 2rem 0 0.75rem;
        }

        .post-body p {
            margin-bottom: 1.5rem;
        }

        .post-body img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1.5rem 0;
        }

        .post-body ul,
        .post-body ol {
            margin: 1.5rem 0 1.5rem 1.5rem;
        }

        .post-body li {
            margin-bottom: 0.5rem;
        }

        .post-body a {
            color: var(--violet-500);
            text-decoration: underline;
        }

        .post-body blockquote {
            border-left: 4px solid var(--violet-500);
            background: var(--gray-50);
            padding: 1.5rem;
            margin: 2rem 0;
            border-radius: 0 0.5rem 0.5rem 0;
            font-style: italic;
            color: var(--gray-700);
        }

        .post-body pre {
            background: var(--gray-900);
            color: var(--gray-100);
            padding: 1.5rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin: 1.5rem 0;
        }

        .post-body code {
            background: var(--gray-100);
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }

        .post-body pre code {
            background: none;
            padding: 0;
        }

        /* Tags */
        .post-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--gray-200);
        }

        .post-tag {
            background: var(--gray-100);
            color: var(--gray-700);
            padding: 0.375rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
            text-decoration: none;
        }

        .post-tag:hover {
            background: var(--violet-500);
            color: var(--white);
        }

        /* Sidebar */
        .sidebar {
            position: sticky;
            top: 100px;
            height: fit-content;
        }

        .sidebar-widget {
            background: var(--white);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .sidebar-widget h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--gray-100);
        }

        /* Table of Contents */
        .toc-list {
            list-style: none;
        }

        .toc-list li {
            margin-bottom: 0.75rem;
        }

        .toc-list a {
            color: var(--gray-600);
            text-decoration: none;
            font-size: 0.875rem;
            display: block;
            padding: 0.25rem 0;
            border-left: 2px solid transparent;
            padding-left: 0.75rem;
        }

        .toc-list a:hover {
            color: var(--violet-500);
            border-left-color: var(--violet-500);
        }

        /* Related Posts */
        .related-post {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--gray-100);
        }

        .related-post:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .related-post-image {
            width: 70px;
            height: 50px;
            border-radius: 0.5rem;
            object-fit: cover;
            background: var(--gray-200);
        }

        .related-post-content h4 {
            font-size: 0.875rem;
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 0.25rem;
        }

        .related-post-content h4 a {
            color: var(--gray-900);
            text-decoration: none;
        }

        .related-post-content h4 a:hover {
            color: var(--violet-500);
        }

        .related-post-date {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        /* CTA Widget */
        .cta-widget {
            background: linear-gradient(135deg, var(--violet-500), var(--blue-600));
            color: var(--white);
            text-align: center;
        }

        .cta-widget h3 {
            color: var(--white);
            border-bottom-color: rgba(255, 255, 255, 0.2);
        }

        .cta-widget p {
            font-size: 0.875rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }

        .cta-btn {
            display: block;
            background: var(--white);
            color: var(--violet-500);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* Post Navigation */
        .post-nav {
            background: var(--white);
            border-radius: 1rem;
            padding: 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .post-nav-item {
            text-decoration: none;
        }

        .post-nav-item.next {
            text-align: right;
        }

        .post-nav-label {
            font-size: 0.75rem;
            color: var(--gray-500);
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .post-nav-title {
            color: var(--gray-900);
            font-weight: 600;
            font-size: 1rem;
        }

        .post-nav-item:hover .post-nav-title {
            color: var(--violet-500);
        }

        /* Footer */
        .site-footer {
            background: var(--dark);
            padding: 3rem 0 1.5rem;
            color: var(--white);
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .footer-logo-img {
            height: 36px;
            width: auto;
            margin-bottom: 1rem;
        }

        .footer-col h4 {
            font-size: 0.875rem;
            margin-bottom: 1rem;
            color: var(--white);
        }

        .footer-col a {
            display: block;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.5);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .post-layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
        }

        @media (max-width: 768px) {

            .nav-links,
            .btn-header {
                display: none;
            }

            .mobile-menu-toggle {
                display: flex;
                flex-direction: column;
                gap: 4px;
                background: none;
                border: none;
                cursor: pointer;
            }

            .mobile-menu-toggle span {
                width: 24px;
                height: 2px;
                background: var(--white);
            }

            .post-header {
                padding: 6rem 1rem 2rem;
            }

            .post-header h1 {
                font-size: 1.75rem;
            }

            .post-content {
                padding: 1.5rem;
            }

            .sidebar {
                grid-template-columns: 1fr;
            }

            .post-nav {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <!-- Include Universal Header -->
    <?php include get_template_directory() . '/inc/universal-header.php'; ?>

    <?php while (have_posts()):
        the_post();
        $categories = get_the_category();
        $category_name = !empty($categories) ? $categories[0]->name : 'General';
        $tags = get_the_tags();
        ?>

        <!-- POST HEADER -->
        <div class="post-header">
            <div class="container">
                <div class="post-header-content">
                    <span class="post-category">
                        <?php echo esc_html($category_name); ?>
                    </span>
                    <h1>
                        <?php the_title(); ?>
                    </h1>
                    <div class="post-meta">
                        <span>📅
                            <?php echo get_the_date('F j, Y'); ?>
                        </span>
                        <span>👤
                            <?php the_author(); ?>
                        </span>
                        <span>⏱
                            <?php echo ceil(str_word_count(get_the_content()) / 200); ?> min read
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- POST CONTENT -->
        <main class="container">
            <div class="post-layout">
                <article class="post-content">
                    <?php if (has_post_thumbnail()): ?>
                        <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>"
                            class="post-featured-image">
                    <?php endif; ?>

                    <div class="post-body">
                        <?php the_content(); ?>
                    </div>

                    <?php if ($tags): ?>
                        <div class="post-tags">
                            <?php foreach ($tags as $tag): ?>
                                <a href="<?php echo get_tag_link($tag->term_id); ?>" class="post-tag">#
                                    <?php echo $tag->name; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>

                <!-- Sidebar -->
                <aside class="sidebar">
                    <!-- CTA Widget -->
                    <div class="sidebar-widget cta-widget">
                        <h3>Get Nordic IPTV</h3>
                        <p>35,000+ channels, 150,000+ movies. Start streaming today!</p>
                        <a href="<?php echo home_url('/#pricing'); ?>" class="cta-btn">View Plans</a>
                    </div>

                    <!-- Related Posts -->
                    <div class="sidebar-widget">
                        <h3>Related Posts</h3>
                        <?php
                        $related = new WP_Query(array(
                            'post_type' => 'post',
                            'posts_per_page' => 3,
                            'post__not_in' => array(get_the_ID()),
                            'category__in' => wp_get_post_categories(get_the_ID()),
                        ));
                        if ($related->have_posts()):
                            while ($related->have_posts()):
                                $related->the_post();
                                ?>
                                <div class="related-post">
                                    <?php if (has_post_thumbnail()): ?>
                                        <img src="<?php the_post_thumbnail_url('thumbnail'); ?>" class="related-post-image" alt="">
                                    <?php else: ?>
                                        <div class="related-post-image"></div>
                                    <?php endif; ?>
                                    <div class="related-post-content">
                                        <h4><a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?>
                                            </a></h4>
                                        <span class="related-post-date">
                                            <?php echo get_the_date('M j, Y'); ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endwhile;
                            wp_reset_postdata();
                        endif; ?>
                    </div>
                </aside>
            </div>

            <!-- Post Navigation -->
            <div class="post-nav">
                <?php
                $prev_post = get_previous_post();
                $next_post = get_next_post();
                ?>
                <div>
                    <?php if ($prev_post): ?>
                        <a href="<?php echo get_permalink($prev_post); ?>" class="post-nav-item prev">
                            <div class="post-nav-label">← Previous</div>
                            <div class="post-nav-title">
                                <?php echo get_the_title($prev_post); ?>
                            </div>
                        </a>
                    <?php endif; ?>
                </div>
                <div>
                    <?php if ($next_post): ?>
                        <a href="<?php echo get_permalink($next_post); ?>" class="post-nav-item next">
                            <div class="post-nav-label">Next →</div>
                            <div class="post-nav-title">
                                <?php echo get_the_title($next_post); ?>
                            </div>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </main>

    <?php endwhile; ?>

    <!-- FOOTER -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-col">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/logo/light logo 500_150.png"
                        alt="Nordic IPTV" class="footer-logo-img">
                    <p style="font-size:0.875rem;color:rgba(255,255,255,0.6);">Premium IPTV streaming for the Nordic
                        region.</p>
                </div>
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <a href="<?php echo home_url('/'); ?>">Home</a>
                    <a href="<?php echo home_url('/#pricing'); ?>">Pricing</a>
                    <a href="<?php echo home_url('/blog/'); ?>">Blog</a>
                </div>
                <div class="footer-col">
                    <h4>Support</h4>
                    <a href="<?php echo home_url('/user-guide/'); ?>">User Guide</a>
                    <a href="<?php echo home_url('/contact-us/'); ?>">Contact Us</a>
                </div>
                <div class="footer-col">
                    <h4>Legal</h4>
                    <a href="<?php echo home_url('/privacy-policy/'); ?>">Privacy Policy</a>
                    <a href="<?php echo home_url('/terms-of-service/'); ?>">Terms of Service</a>
                </div>
            </div>
            <div class="footer-bottom">
                ©
                <?php echo date('Y'); ?> Nordic IPTV. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Include Currency JS -->
    <script src="<?php echo get_template_directory_uri(); ?>/front-page/js/currency.js"></script>

    <?php wp_footer(); ?>
</body>

</html>