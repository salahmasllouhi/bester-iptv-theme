<?php
/**
 * Template for displaying single pages
 * 
 * Used for Privacy Policy, Terms, About Us, Contact, etc.
 * 
 * @package Nordic_IPTV
 */
get_header();
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    :root {
        --white: #ffffff;
        --bg: #f8fafc;
        --bg-alt: #f1f5f9;
        --dark: #0f172a;
        --dark-alt: #1e293b;
        --text: #0f172a;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --border: #e2e8f0;
        --blue-50: #eff6ff;
        --blue-100: #dbeafe;
        --blue-500: #3b82f6;
        --blue-600: #2563eb;
        --blue-700: #1d4ed8;
        --green-500: #22c55e;
        --green-100: #dcfce7;
        --orange-500: #f97316;
        --orange-100: #ffedd5;
        --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 25px -5px rgb(0 0 0 / 0.1);
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: var(--white);
        color: var(--text);
        line-height: 1.6;
    }

    /* HEADER - Glassmorphic Floating Pill (Dark Style) */
    .site-header {
        position: absolute;
        top: 1rem;
        left: 50%;
        transform: translateX(-50%);
        width: calc(100% - 3rem);
        max-width: 1200px;
        z-index: 100;
        padding: 0.75rem 1.5rem;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 1rem;
        transition: all 0.3s;
    }

    .site-header.scrolled {
        position: fixed;
        top: 1rem;
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .site-header.scrolled .mobile-menu-toggle span {
        background: var(--white);
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    .nav-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .logo {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--white);
        text-decoration: none;
    }

    .logo span {
        color: var(--blue-500);
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
        transition: color 0.2s;
    }

    .nav-links a:hover {
        color: var(--white);
    }

    .btn-header {
        background: var(--blue-600);
        color: var(--white);
        padding: 0.5rem 1.25rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        transition: background 0.2s;
    }

    .btn-header:hover {
        background: var(--blue-700);
    }

    /* Mobile Menu Toggle */
    .mobile-menu-toggle {
        display: none;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.5rem;
    }

    .mobile-menu-toggle span {
        display: block;
        width: 24px;
        height: 2px;
        background: var(--white);
        margin: 5px 0;
        transition: 0.3s;
    }

    /* PAGE HEADER - Compact */
    .page-header {
        background: linear-gradient(135deg, var(--blue-600) 0%, var(--blue-700) 100%);
        padding: 5rem 0 2rem;
        text-align: center;
    }

    .page-header h1 {
        color: var(--white);
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .page-header p {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.875rem;
    }

    /* PAGE CONTENT */
    .page-content {
        padding: 4rem 0;
        background: var(--white);
    }

    .content-wrapper {
        max-width: 800px;
        margin: 0 auto;
        background: var(--white);
        padding: 2rem;
    }

    .content-wrapper h1,
    .content-wrapper h2,
    .content-wrapper h3,
    .content-wrapper h4 {
        color: var(--text);
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .content-wrapper h1 {
        font-size: 2rem;
    }

    .content-wrapper h2 {
        font-size: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--blue-500);
    }

    .content-wrapper h3 {
        font-size: 1.25rem;
    }

    .content-wrapper p {
        margin-bottom: 1rem;
        color: var(--text-secondary);
        line-height: 1.8;
    }

    .content-wrapper ul,
    .content-wrapper ol {
        margin-bottom: 1rem;
        padding-left: 1.5rem;
        color: var(--text-secondary);
    }

    .content-wrapper li {
        margin-bottom: 0.5rem;
    }

    .content-wrapper a {
        color: var(--blue-600);
        text-decoration: none;
    }

    .content-wrapper a:hover {
        text-decoration: underline;
    }

    .content-wrapper strong {
        color: var(--text);
    }

    @media (max-width: 768px) {
        .nav-links {
            display: none;
        }

        .btn-header {
            display: none;
        }

        .mobile-menu-toggle {
            display: flex;
            flex-direction: column;
        }

        .page-header {
            padding: 6rem 0 3rem;
        }

        .page-header h1 {
            font-size: 1.75rem;
        }
    }
</style>

<!-- PAGE HEADER -->
<div class="page-header">
    <div class="container">
        <h1>
            <?php the_title(); ?>
        </h1>
        <p>
            <?php echo get_the_date('F j, Y'); ?>
        </p>
    </div>
</div>

<!-- PAGE CONTENT -->
<main class="page-content">
    <div class="container">
        <div class="content-wrapper">
            <?php
            while (have_posts()):
                the_post();
                the_content();
            endwhile;
            ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>