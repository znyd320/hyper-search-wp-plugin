<?php
/**
 * Panda Header Footer Builder - Support Documentation
 * 
 * This support page integrates with:
 * - Elementor widgets system
 * - WooCommerce cart functionality
 * - Custom logo effects module
 * - Navigation menu system
 */
?>
<div class="wrap panda-support-page">
    <header>
        <h1 class="panda-page-title">
            Panda Header Footer Builder
            <span class="panda-page-version"><?php echo PANDA_HF_VERSION; ?></span>
            <span class="panda-status-badge">Active</span>
        </h1>
    </header>

    <nav class="panda-tabs-nav nav-tab-wrapper">
        <a href="#panda-tab-getting-started" class="nav-tab nav-tab-active">Getting Started</a>
        <a href="#panda-tab-widgets" class="nav-tab">Widgets & Modules</a>
        <a href="#panda-tab-integration" class="nav-tab">Integration Guide</a>
        <a href="#panda-tab-advanced" class="nav-tab">Advanced Features</a>
    </nav>

    <main>
        <!-- Getting Started -->
        <section id="panda-tab-getting-started" class="panda-tab-content active">
            <div class="panda-row">
                <div class="panda-card feature-highlight">
                    <h3>Core Features</h3>
                    <div class="feature-grid">
                        <div class="feature-item">
                            <span class="dashicons dashicons-layout"></span>
                            <h4>Template System</h4>
                            <p>Create unlimited headers, footers, and blocks using Elementor</p>
                        </div>
                        <div class="feature-item">
                            <span class="dashicons dashicons-visibility"></span>
                            <h4>Display Controls</h4>
                            <p>Advanced conditions for template visibility</p>
                        </div>
                        <div class="feature-item">
                            <span class="dashicons dashicons-cart"></span>
                            <h4>WooCommerce Ready</h4>
                            <p>Integrated mini-cart with AJAX updates</p>
                        </div>
                    </div>
                </div>

                <div class="panda-card">
                    <h3>Quick Setup Guide</h3>
                    <ol class="setup-steps">
                        <li>
                            <strong>Create Template:</strong>
                            <a href="<?php echo admin_url('post-new.php?post_type=panda_template'); ?>" class="button button-primary">New Template</a>
                        </li>
                        <li>
                            <strong>Choose Type:</strong>
                            <ul>
                                <li>Header Template</li>
                                <li>Footer Template</li>
                                <li>Block Template (for shortcodes)</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Set Display Rules:</strong>
                            <code>Template Settings → Display Conditions</code>
                        </li>
                    </ol>
                </div>
            </div>
        </section>

        <!-- Widgets & Modules -->
        <section id="panda-tab-widgets" class="panda-tab-content">
            <div class="panda-docs-row">
                <div class="panda-doc-card">
                    <h3>Logo Widget</h3>
                    <div class="widget-features">
                        <h4>Effects Available:</h4>
                        <ul>
                            <li>Grow animation</li>
                            <li>Shrink effect</li>
                            <li>Pulse animation</li>
                        </ul>
                        <!-- <div class="code-example">
                            <pre><code>.logo-hover-grow img {
    transition: transform 0.3s ease;
}</code></pre>
                        </div> -->
                    </div>
                </div>

                <div class="panda-doc-card">
                    <h3>Navigation Menu</h3>
                    <div class="widget-features">
                        <h4>Key Features:</h4>
                        <ul>
                            <li>Multi-level dropdowns</li>
                            <li>Mobile responsive</li>
                            <li>RTL support</li>
                            <li>Custom animations</li>
                        </ul>
                    </div>
                </div>

                <div class="panda-doc-card">
                    <h3>Cart Module</h3>
                    <div class="widget-features">
                        <h4>Functionality:</h4>
                        <ul>
                            <li>AJAX quantity updates</li>
                            <li>Real-time total calculation</li>
                            <li>Fragment updates</li>
                            <li>Custom styling options</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Integration Guide -->
        <section id="panda-tab-integration" class="panda-tab-content">
            <div class="panda-integration-grid">
                <div class="integration-card">
                    <h3>Shortcode Usage</h3>
                    <div class="code-block">
                        <code>[panda_template id="123"]</code>
                        <p>Display any template anywhere using shortcodes</p>
                    </div>
                </div>

                <div class="integration-card">
                    <h3>Theme Integration</h3>
                    <pre><code>// Add theme support
add_theme_support('panda_header_footer');</code></pre>
                </div>

                <div class="integration-card">
                    <h3>Supported Themes</h3>
                    <ul class="theme-list">
                        <li>Genesis</li>
                        <li>Astra</li>
                        <li>GeneratePress</li>
                        <li>OceanWP</li>
                        <li>Storefront</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Advanced Features -->
        <section id="panda-tab-advanced" class="panda-tab-content">
            <div class="panda-advanced-features">
                <div class="feature-section">
                    <h3>Display Conditions</h3>
                    <div class="condition-types">
                        <ul>
                            <li>Page-specific rules</li>
                            <li>User role targeting</li>
                            <li>Custom post type support</li>
                            <li>WooCommerce page rules</li>
                        </ul>
                    </div>
                </div>

                <!-- <div class="feature-section">
                    <h3>Extension System</h3>
                    <p>Create custom extensions using the base extension class:</p>
                    <pre><code>class Custom_Extension extends Base_Extension {
    protected function get_extension_path() {
        return __DIR__;
    }
}</code></pre>
                </div> -->
            </div>
        </section>
    </main>
</div>
<style>
/* Enhanced Documentation Styling */
.panda-support-page {
    max-width: 1400px;
    margin: 20px auto;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.panda-page-title {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.panda-status-badge {
    background: linear-gradient(45deg, #00a32a, #2ecc71);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    box-shadow: 0 2px 5px rgba(0,163,42,0.2);
}

.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin: 30px 0;
}

.feature-item {
    background: #ffffff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    transition: transform 0.2s ease;
}

.feature-item:hover {
    transform: translateY(-3px);
}

.feature-item .dashicons {
    font-size: 32px;
    width: 32px;
    height: 32px;
    color: #2271b1;
    margin-bottom: 15px;
}

.panda-doc-card {
    background: #ffffff;
    padding: 25px;
    margin-bottom: 25px;
    border-radius: 10px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.05);
}

.widget-features {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-top: 15px;
}

.widget-features ul {
    margin: 0;
    padding-left: 20px;
}

.widget-features li {
    margin: 10px 0;
    color: #505050;
}

.setup-steps {
    counter-reset: step;
    list-style-type: none;
    padding: 0;
}

.setup-steps li {
    position: relative;
    padding: 15px 0 15px 45px;
    border-left: 2px solid #e5e5e5;
    margin-left: 20px;
}

.setup-steps li:before {
    counter-increment: step;
    content: counter(step);
    position: absolute;
    left: -20px;
    width: 36px;
    height: 36px;
    background: #fff;
    border: 2px solid #2271b1;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.code-example {
    background: #282c34;
    padding: 20px;
    border-radius: 8px;
    margin: 15px 0;
}

.code-example code {
    color: #abb2bf;
    font-family: 'Monaco', 'Consolas', monospace;
}

.integration-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.nav-tab-wrapper {
    margin-bottom: 30px;
    border-bottom: 2px solid #f0f0f0;
}

.nav-tab {
    padding: 12px 20px;
    font-size: 14px;
    border: none;
    background: transparent;
    color: #50575e;
    transition: all 0.2s ease;
}

.nav-tab-active {
    color: #2271b1;
    border-bottom: 2px solid #2271b1;
    margin-bottom: -2px;
}

.panda-tab-content {
    display: none;
}

.panda-tab-content.active {
    display: block;
}

@media (max-width: 768px) {
    .feature-grid {
        grid-template-columns: 1fr;
    }
}
</style>
</style>

<script>
jQuery(document).ready(function($) {
    // Tab Navigation
    $('.panda-tabs-nav a').on('click', function(e) {
        e.preventDefault();
        $('.panda-tabs-nav a').removeClass('nav-tab-active');
        $('.panda-tab-content').removeClass('active');
        $(this).addClass('nav-tab-active');
        $($(this).attr('href')).addClass('active');
    });

    // Code Copy Functionality
    $('.code-block code').click(function() {
        navigator.clipboard.writeText($(this).text());
        $(this).addClass('copied');
        setTimeout(() => $(this).removeClass('copied'), 1000);
    });
});
</script>