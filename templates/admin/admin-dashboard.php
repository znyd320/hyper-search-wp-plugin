<div class="wrap pandahfb-admin">
    <div class="pandahfb-sidebar">
        <div class="pandahfb-logo">
            <img src="<?php echo plugin_dir_url(__FILE__) . '../assets/images/panda-logo.png'; ?>" alt="PandaHFB Logo">
            <span class="version-tag">v<?php echo PANDA_HF_VERSION; ?></span>
        </div>
        <nav class="pandahfb-nav">
            <a href="<?php echo admin_url('admin.php?page=pandahfb-blocks'); ?>" class="nav-item">
                <span class="dashicons dashicons-welcome-widgets-menus"></span>
                <span>Blocks</span>
            </a>
            <a href="<?php echo admin_url('admin.php?page=pandahfb-settings'); ?>" class="nav-item">
                <span class="dashicons dashicons-admin-settings"></span>
                <span>Settings</span>
            </a>
            <a href="#" class="nav-item">
                <span class="dashicons dashicons-book"></span>
                <span>Documentation</span>
            </a>
        </nav>
        <div class="pandahfb-sidebar-footer">
            <a href="#" class="nav-item"><span class="dashicons dashicons-editor-help"></span> Support</a>
            <a href="#" class="nav-item"><span class="dashicons dashicons-star-filled"></span> Rate Us</a>
            <a href="#" class="nav-item"><span class="dashicons dashicons-admin-plugins"></span> More Plugins</a>
        </div>
    </div>

    <div class="pandahfb-main">
        <div class="pandahfb-header">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        </div>

        <div class="pandahfb-dashboard-content">
            <div class="pandahfb-stats-grid">
                <div class="pandahfb-card">
                    <h2><span class="dashicons dashicons-welcome-widgets-menus"></span> Header Blocks</h2>
                    <div class="stat">5 Active</div>
                    <a href="<?php echo admin_url('admin.php?page=pandahfb-blocks'); ?>" class="card-link">Manage Headers →</a>
                </div>

                <div class="pandahfb-card">
                    <h2><span class="dashicons dashicons-welcome-widgets-menus"></span> Footer Blocks</h2>
                    <div class="stat">3 Active</div>
                    <a href="<?php echo admin_url('admin.php?page=pandahfb-blocks'); ?>" class="card-link">Manage Footers →</a>
                </div>

                <div class="pandahfb-card">
                    <h2><span class="dashicons dashicons-admin-settings"></span> Configuration</h2>
                    <p>Manage your global settings and preferences</p>
                    <a href="<?php echo admin_url('admin.php?page=pandahfb-settings'); ?>" class="card-link">View Settings →</a>
                </div>

                <div class="pandahfb-card">
                    <h2><span class="dashicons dashicons-book"></span> Getting Started</h2>
                    <p>Learn how to use PandaHFB effectively</p>
                    <a href="#" class="card-link">Read Documentation →</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .pandahfb-admin {
            display: flex;
            margin: -20px -20px 0 -20px;
            min-height: calc(100vh - 32px);
            background: #f0f2f5;
        }

        .pandahfb-sidebar {
            width: 260px;
            background: #fff;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: calc(100vh - 32px);
        }

        .pandahfb-logo {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
        }

        .pandahfb-logo img {
            max-height: 40px;
            margin-bottom: 10px;
        }

        .version-tag {
            background: #f0f2f5;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            color: #6b7280;
        }

        .pandahfb-nav {
            padding: 20px 0;
            flex-grow: 1;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.2s;
            gap: 12px;
        }

        .nav-item:hover {
            background: #f0f2f5;
            color: #000;
        }

        .pandahfb-sidebar-footer {
            padding: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .pandahfb-main {
            flex-grow: 1;
            margin-left: 260px;
            padding: 30px;
        }

        .pandahfb-header {
            margin-bottom: 30px;
        }

        .pandahfb-header h1 {
            font-size: 24px;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }

        .pandahfb-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }

        .pandahfb-card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .pandahfb-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .pandahfb-card h2 {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0 0 16px 0;
            font-size: 18px;
            color: #111827;
        }

        .stat {
            font-size: 24px;
            font-weight: 600;
            color: #2271b1;
            margin: 10px 0;
        }

        .card-link {
            display: inline-block;
            margin-top: 16px;
            color: #2271b1;
            text-decoration: none;
            font-weight: 500;
        }

        .card-link:hover {
            color: #135e96;
        }

        .dashicons {
            width: 24px;
            height: 24px;
            font-size: 24px;
        }
    </style>
</div>
