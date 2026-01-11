<?php
/**
 * Blog Archive / Listing Page Template
 * Creative grid layout with featured posts
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Nordic IPTV</title>
    <?php wp_head(); ?>

    <!-- Include Front Page CSS Files -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/front-page/css/variables.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/front-page/css/header.css">

    <style>
        /* Additional Blog-specific styles */
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: #F3F4F6;
            color: #111827;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Blog Header */
        .blog-header {
            background: linear-gradient(135deg, #2563EB 0%, #7C3AED 100%);
            padding: 7rem 0 3rem;
            text-align: center;
            margin-bottom: 3rem;
        }

        .blog-header h1 {
            color: #FFFFFF;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .blog-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.125rem;
        }

        /* Blog Grid */
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-bottom: 4rem;
        }

        /* Post Card */
        .post-card {
            background: #FFFFFF;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .post-card-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #3B82F6, #7C3AED);
        }

        .post-card-content {
            padding: 1.5rem;
        }

        .post-card-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.75rem;
            color: #6B7280;
            margin-bottom: 0.75rem;
        }

        .post-card-category {
            background: #7C3AED;
            color: #FFFFFF;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .post-card h2 {
            font-size: 1.125rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }

        .post-card h2 a {
            color: inherit;
            text-decoration: none;
        }

        .post-card h2 a:hover {
            color: #7C3AED;
        }

        .post-card-excerpt {
            color: #6B7280;
            font-size: 0.875rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .post-card-link {
            color: #7C3AED;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .post-card-link:hover {
            gap: 0.75rem;
        }

        /* Featured Post (First Post) */
        .post-card.featured {
            grid-column: span 2;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .post-card.featured .post-card-image {
            height: 100%;
            min-height: 300px;
        }

        .post-card.featured .post-card-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2rem;
        }

        .post-card.featured h2 {
            font-size: 1.5rem;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 4rem;
        }

        .pagination a,
        .pagination span {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .pagination a {
            background: #FFFFFF;
            color: #374151;
            border: 1px solid #E5E7EB;
        }

        .pagination a:hover {
            background: #7C3AED;
            color: #FFFFFF;
            border-color: #7C3AED;
        }

        .pagination .current {
            background: #7C3AED;
            color: #FFFFFF;
        }

        /* No Posts */
        .no-posts {
            text-align: center;
            padding: 4rem 2rem;
            background: #FFFFFF;
            border-radius: 1rem;
            grid-column: span 3;
        }

        /* Footer */
        .site-footer {
            background: #0F172A;
            padding: 3rem 0 1.5rem;
            color: #FFFFFF;
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
            color: #FFFFFF;
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
            .blog-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .post-card.featured {
                grid-column: span 2;
            }

            .no-posts {
                grid-column: span 2;
            }
        }

        @media (max-width: 768px) {
            .blog-grid {
                grid-template-columns: 1fr;
            }

            .post-card.featured {
                grid-column: span 1;
                grid-template-columns: 1fr;
            }

            .post-card.featured .post-card-image {
                min-height: 200px;
            }

            .no-posts {
                grid-column: span 1;
            }

            .nav-links,
            .nav-right {
                display: none;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .blog-header {
                padding: 6rem 1rem 2rem;
            }

            .blog-header h1 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <!-- Include Universal Header -->
    <?php include get_template_directory() . '/inc/universal-header.php'; ?>

    <!-- BLOG HEADER -->
    <div class="blog-header">
        <div class="container">
            <h1>Blog & News</h1>
            <p>Latest updates, guides, and streaming tips</p>
        </div>
    </div>

    <!-- BLOG CONTENT -->
    <main class="container">
        <div class="blog-grid">
            <?php
            $post_count = 0;
            if (have_posts()):
                while (have_posts()):
                    the_post();
                    $post_count++;
                    $is_featured = ($post_count === 1);
                    $categories = get_the_category();
                    $category_name = !empty($categories) ? $categories[0]->name : 'General';
                    ?>
                    <article class="post-card <?php echo $is_featured ? 'featured' : ''; ?>">
                        <?php if (has_post_thumbnail()): ?>
                            <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>"
                                class="post-card-image">
                        <?php else: ?>
                            <div class="post-card-image"></div>
                        <?php endif; ?>

                        <div class="post-card-content">
                            <div class="post-card-meta">
                                <span class="post-card-category"><?php echo esc_html($category_name); ?></span>
                                <span><?php echo get_the_date('M j, Y'); ?></span>
                            </div>
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <p class="post-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), $is_featured ? 30 : 20); ?>
                            </p>
                            <a href="<?php the_permalink(); ?>" class="post-card-link">
                                Read More <span>→</span>
                            </a>
                        </div>
                    </article>
                    <?php
                endwhile;
            else:
                ?>
                <div class="no-posts">
                    <h2>No posts found</h2>
                    <p>Check back soon for new content!</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php
            echo paginate_links(array(
                'prev_text' => '← Previous',
                'next_text' => 'Next →',
            ));
            ?>
        </div>
    </main>

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
                © <?php echo date('Y'); ?> Nordic IPTV. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Include Currency JS -->
    <script src="<?php echo get_template_directory_uri(); ?>/front-page/js/currency.js"></script>

    <?php wp_footer(); ?>
</body>

</html>