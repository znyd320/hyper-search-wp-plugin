<?php
/**
 * Support page template for Panda Header Footer Builder
 */
?>
<div class="wrap panda-support-page">

    <!-- Page Header -->
    <header>
        <h1 class="panda-page-title">
            Support
            <span class="panda-page-version">1.0.1</span>
            <span class="panda-wifi-icon dashicons dashicons-wifi"></span>
        </h1>
    </header>

    <!-- Tab Navigation -->
    <nav class="panda-tabs-nav nav-tab-wrapper">
        <a href="#panda-tab-getting-started" class="nav-tab nav-tab-active">Getting Started</a>
        <a href="#panda-tab-documentation" class="nav-tab">Documentation</a>
        <a href="#panda-tab-free-vs-pro" class="nav-tab">Free vs Pro</a>
        <a href="#panda-tab-improve" class="nav-tab">Help us improve!</a>
    </nav>

    <!-- Tab Content -->
    <main>
        <!-- Getting Started Tab -->
        <section id="panda-tab-getting-started" class="panda-tab-content" style="display: block;">
            <h2>Getting Started</h2>
            
            <!-- Welcome Section -->
            <div class="panda-row">
                <div class="panda-card">
                    <h3>Welcome to Panda Header Footer for Elementor!</h3>
                    <p>Create & manage custom Headers and Footers for your WordPress site, all within Elementor's free version. Enjoy features such as:</p>
                    <ul>
                        <li>Easily build custom Headers & Footers without Elementor Pro.</li>
                        <li>Display them across your entire site or on selected pages only.</li>
                        <li>Add custom Elementor "blocks" anywhere using shortcodes.</li>
                        <li>Compatible with many top page builders.</li>
                        <li>Centralized styling for headers & footers.</li>
                        <li>No coding required—intuitive, user-friendly interface.</li>
                    </ul>
                    <p>
                        <a href="<?php echo admin_url('post-new.php?post_type=panda_template'); ?>" class="button button-primary">
                            Create Your First Header
                        </a>
                    </p>
                </div>

                <div class="panda-card">
                    <h3>Quick Links</h3>
                    <p>New here? Learn how to use Panda Header Footer for Elementor with these resources:</p>
                    <ul class="panda-link-list">
                        <li><a href="#" target="_blank">General Setup Guide</a></li>
                        <li><a href="#" target="_blank">How to create your first Header</a></li>
                        <li><a href="#" target="_blank">How to create your first Footer</a></li>
                        <li><a href="#" target="_blank">Troubleshooting Guide</a></li>
                        <li><a href="#" target="_blank">Customizing Headers & Footers</a></li>
                        <li><a href="#" target="_blank">Using Shortcodes for Custom Blocks</a></li>
                    </ul>
                </div>
            </div>

            <!-- Features Section -->
            <div class="panda-row">
                <div class="panda-card">
                    <h3>Shortcode</h3>
                    <p>
                        Display your custom Elementor blocks anywhere using a shortcode, for example:
                        <code>[panda_template id='123']</code>.
                        The advantage of using a shortcode is that you can embed it in any Page, Post, or Widget area.
                    </p>
                </div>
                <div class="panda-card">
                    <h3>Pagebuilder Integration</h3>
                    <p>
                        Panda Header Footer for Elementor is fully integrated with Elementor.
                        Build your custom header or footer visually—no complex code needed!
                        You can also combine it with other popular page builders if you prefer.
                    </p>
                </div>
            </div>
        </section>

        <!-- Documentation Tab -->
        <section id="panda-tab-documentation" class="panda-tab-content" style="display: none;">
            <h2>Documentation</h2>
            
            <!-- Documentation Cards - Row 1 -->
            <div class="panda-docs-row">
                <div class="panda-doc-card">
                    <div class="panda-doc-image">
                        <img src="https://placehold.co/400x200?text=Video+Thumbnail" alt="Creating Headers Tutorial" />
                    </div>
                    <h3>Creating Your First Header</h3>
                    <p>Learn how to build a custom header layout with free Elementor and display it site-wide or on specific pages.</p>
                    <p><a href="#" class="panda-learn-more">Learn More</a></p>
                </div>

                <div class="panda-doc-card">
                    <div class="panda-doc-image">
                        <img src="https://placehold.co/400x200?text=Video+Thumbnail" alt="Creating Footers Tutorial" />
                    </div>
                    <h3>Creating Your First Footer</h3>
                    <p>Learn how to build a custom footer layout to replace your theme's default footer.</p>
                    <p><a href="#" class="panda-learn-more">Learn More</a></p>
                </div>

                <div class="panda-doc-card">
                    <div class="panda-doc-image">
                        <img src="https://placehold.co/400x200?text=Video+Thumbnail" alt="Shortcodes Tutorial" />
                    </div>
                    <h3>Using Shortcodes for Blocks</h3>
                    <p>Place custom Elementor sections or blocks anywhere on your site using shortcodes.</p>
                    <p><a href="#" class="panda-learn-more">Learn More</a></p>
                </div>
            </div>

            <!-- Documentation Cards - Row 2 -->
            <div class="panda-docs-row">
                <div class="panda-doc-card">
                    <div class="panda-doc-image">
                        <img src="https://placehold.co/400x200?text=Thumbnail" alt="Display Conditions Tutorial" />
                    </div>
                    <h3>Advanced Display Conditions</h3>
                    <p>Discover how to show or hide headers, footers, or blocks based on specific pages, categories, or user roles.</p>
                    <p><a href="#" class="panda-learn-more">Learn More</a></p>
                </div>
            </div>
        </section>

        <!-- Free vs Pro Tab -->
        <section id="panda-tab-free-vs-pro" class="panda-tab-content" style="display: none;">
            <h2>Free vs Pro</h2>
            <p class="panda-subtitle">Powerful features available only with Panda Header Footer for Elementor Pro</p>
            
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th>Feature</th>
                        <th>Free</th>
                        <th>Pro</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Create custom Headers & Footers</td>
                        <td><span class="dashicons dashicons-yes-alt"></span></td>
                        <td><span class="dashicons dashicons-yes-alt"></span></td>
                    </tr>
                    <tr>
                        <td>Display on entire site or specific pages</td>
                        <td><span class="dashicons dashicons-yes-alt"></span></td>
                        <td><span class="dashicons dashicons-yes-alt"></span></td>
                    </tr>
                    <tr>
                        <td>Advanced Display Conditions (roles, time, device)</td>
                        <td><span class="dashicons dashicons-no-alt"></span></td>
                        <td><span class="dashicons dashicons-yes-alt"></span></td>
                    </tr>
                    <tr>
                        <td>Sticky & Transparent Headers</td>
                        <td><span class="dashicons dashicons-no-alt"></span></td>
                        <td><span class="dashicons dashicons-yes-alt"></span></td>
                    </tr>
                    <tr>
                        <td>Multiple Header/Footer Templates</td>
                        <td><span class="dashicons dashicons-no-alt"></span></td>
                        <td><span class="dashicons dashicons-yes-alt"></span></td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Help us improve Tab -->
        <section id="panda-tab-improve" class="panda-tab-content" style="display: none;">
            <h2>Help us improve!</h2>
            <div class="panda-improve-row">
                <div class="panda-card">
                    <h3>Answer a few questions to help us improve Panda Header Footer for Elementor</h3>
                    <p>
                        We're always looking for suggestions to further enhance the plugin.
                        If your feedback is especially insightful and we invite you for an interview, 
                        you can even receive a complimentary yearly Pro membership.
                    </p>
                    <p>
                        <a href="#" class="button button-primary">Take The Survey</a>
                    </p>
                </div>
                <div class="panda-improve-illustration">
                    <img src="https://placehold.co/500x300?text=Illustration" alt="Improve illustration" />
                </div>
            </div>
        </section>
    </main>
</div>

<!-- Styles -->
<style>
    /* Core Layout */
    .panda-support-page {
        max-width: 1200px;
    }

    /* Header Styles */
    .panda-page-title {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
    }

    .panda-page-version {
        font-size: 14px;
        color: #777;
        margin-left: 1rem;
    }

    .panda-wifi-icon {
        margin-left: 0.5rem;
        font-size: 20px;
    }

    /* Navigation */
    .panda-tabs-nav {
        margin-bottom: 1.5rem;
        border-bottom: 1.5px solid #eee;
    }

    .panda-tabs-nav .nav-tab {
        cursor: pointer;
        font-size: 14px;
    }

    .panda-tabs-nav .nav-tab-active {
        border-bottom: 1.5px solid #0073aa;
    }

    /* Grid Layout */
    .panda-row,
    .panda-docs-row,
    .panda-improve-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 2rem;
    }

    /* Cards */
    .panda-card,
    .panda-doc-card {
        background: #fff;
        border: 1px solid #ddd;
        padding: 20px;
        box-sizing: border-box;
    }

    .panda-card {
        flex: 1 1 calc(50% - 20px);
    }

    .panda-doc-card {
        width: calc(33.333% - 20px);
        min-height: 240px;
        display: flex;
        flex-direction: column;
    }

    /* Typography */
    .panda-card h3,
    .panda-doc-card h3 {
        margin-top: 0;
        font-size: 1.2rem;
    }

    .panda-subtitle {
        margin-bottom: 1rem;
        font-size: 16px;
        color: #555;
    }

    /* Links */
    .panda-link-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .panda-link-list li {
        margin-bottom: 0.5em;
    }

    .panda-link-list a,
    .panda-learn-more {
        text-decoration: none;
        color: #0073aa;
    }

    .panda-learn-more:hover {
        text-decoration: underline;
    }

    /* Images */
    .panda-doc-image img,
    .panda-improve-illustration img {
        width: 100%;
        height: auto;
        display: block;
        margin-bottom: 1rem;
    }

    /* Tables */
    .widefat.striped th {
        background: #f3f3f3;
        font-weight: 600;
    }

    .widefat.striped td {
        vertical-align: middle;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .panda-row,
        .panda-docs-row,
        .panda-improve-row {
            flex-direction: column;
        }

        .panda-card,
        .panda-doc-card,
        .panda-improve-illustration {
            width: 100% !important;
        }
    }
</style>

<!-- Tab Functionality -->
<script>
    (function($) {
        $('.panda-tabs-nav a').on('click', function(e) {
            e.preventDefault();
            
            // Update tab states
            $('.panda-tabs-nav a').removeClass('nav-tab-active');
            $('.panda-tab-content').hide();
            
            // Show selected tab
            $(this).addClass('nav-tab-active');
            $($(this).attr('href')).show();
        });
    })(jQuery);
</script>