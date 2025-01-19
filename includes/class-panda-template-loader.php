<?php

namespace Panda\Header_Footer;

use Error;

class Template_Loader
{

    private static $instance = null;
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        add_shortcode('panda_template', [$this, 'template_shortcode']);

        global $is_rendered_hook_templates;
        if (!$is_rendered_hook_templates) {
            $is_rendered_hook_templates = true;
            $this->render_hook_templates();
        }
    }

    public function render_hook_templates()
    {
        add_filter('the_content', [$this, 'handle_single_page_content_filter']);
    }

    public function handle_single_page_content_filter($content)
    {
        $templates = $this->get_matching_templates('shortcode');
        if (empty($templates)) {
            return $content;
        }

        $before_content_templates = [];
        $after_content_templates = [];

        foreach ($templates as $template) {
            $display_location = get_post_meta($template['id'], '_display_location', true) ?: '';
            $post_type_name = explode('--', $display_location)[1];
            if(!$post_type_name){
                continue;
            }
            if ($display_location === "before_single--{$post_type_name}" && is_singular($post_type_name)) {
                $before_content_templates[] = $template;
            } elseif ($display_location === "after_single--{$post_type_name}" && is_singular($post_type_name)) {
                $after_content_templates[] = $template;
            }
        }

        $before_content = '';
        $after_content = '';

        if (!empty($before_content_templates)) {
            foreach ($before_content_templates as $template) {
                $shortcode = '[panda_template id="' . $template['id'] . '"]';
                $before_content .= do_shortcode($shortcode);
            }
        }

        if (!empty($after_content_templates)) {
            foreach ($after_content_templates as $template) {
                $shortcode = '[panda_template id="' . $template['id'] . '"]';
                $after_content .= do_shortcode($shortcode);
            }
        }

        return $before_content . $content . $after_content;
    }
    /**
     * Usage: [panda_template id="123"]
     */
    public function template_shortcode($atts)
    {
        $atts = shortcode_atts(array(
            'id' => '',
        ), $atts);

        error_log('template_shortcode: ' . $atts['id']);
        
        if (empty($atts['id'])) {
            return '';
        }

        ob_start();
        $this->load_template('shortcode', $atts['id']);
        $this->load_template('hook', $atts['id']); 
        return ob_get_clean();
    }

    public function load_template($type, $template_id = null)
    {
        
        if (($type === 'shortcode' || $type === 'hook') && $template_id) {
            
            return $this->render_template_by_id($template_id, $type);   
        }

        $templates = $this->get_matching_templates($type);
        if (empty($templates)) {
            return;
        }
    }

    public function render_header()
    {
        $this->render_template('header');
    }

    public function render_footer()
    {

        $this->render_template('footer');
    }

    public function render_before_footer()
    {
        $this->render_template('before_footer');
    }

    public function isHeaderActive()
    {
        $matching_templates = $this->get_matching_templates('header');
        return !empty($matching_templates);
    }

    public function isBeforeFooterActive()
    {
        $matching_templates = $this->get_matching_templates('before_footer');
        return !empty($matching_templates);
    }

    public function isFooterActive()
    {
        $matching_templates = $this->get_matching_templates('footer');
        return !empty($matching_templates);
    }

    private function render_template($type)
    {
        $templates = $this->get_matching_templates($type);

        if (!empty($templates)) {
            if (class_exists('\Elementor\Plugin')) {
                $elementor = \Elementor\Plugin::$instance;
                $content = $elementor->frontend->get_builder_content_for_display($templates[0]['id'], true);
                echo $content;
            }
        }
    }

    private function render_template_by_id($template_id, $type = 'shortcode')
    {
        $args = array(
            'post_type' => 'panda_template',
            'posts_per_page' => -1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_template_type',
                    'value' => $type,
                    'compare' => '=',
                    'type' => 'CHAR'
                )
            ),
            'p' => $template_id
        );

        $templates = get_posts($args);

        if (!empty($templates) && class_exists('\Elementor\Plugin')) {
            $elementor = \Elementor\Plugin::$instance;
            $content = $elementor->frontend->get_builder_content_for_display($template_id, true);
            echo $content;
        }
    }


    private function get_matching_templates($type)
    {
        $specific_templates = [];
        $general_templates = [];
        $entire_website_templates = [];

        $args = array(
            'post_type' => 'panda_template',
            'posts_per_page' => -1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_template_type',
                    'value' => $type,
                    'compare' => '=',
                    'type' => 'CHAR'
                ),
                array(
                    'key' => '_display_condition',
                    'compare' => 'EXISTS'
                ),
                array(
                    'key' => '_user_roles',
                    'compare' => 'EXISTS'
                )
            )
        );

        $templates = get_posts($args);

        if (!empty($templates)) {
            foreach ($templates as $template) {
                $conditions = get_post_meta($template->ID, '_display_condition', true);
                $allowed_roles = get_post_meta($template->ID, '_user_roles', true);

                if ($this->check_user_role_condition($allowed_roles)) {
                    if ($conditions === 'entire_website') {
                        $entire_website_templates[] = array(
                            'id' => $template->ID,
                            'priority' => 10
                        );
                    };

                    if ($this->is_specific_template($conditions)) {
                        $specific_templates[] = array(
                            'id' => $template->ID,
                            'priority' => 30
                        );
                    };

                    if ($this->is_general_template($conditions)) {
                        $general_templates[] = array(
                            'id' => $template->ID,
                            'priority' => 20
                        );
                    };
                }
            }
        }


        if (!empty($specific_templates)) {
            return $specific_templates;
        }

        if (!empty($general_templates)) {
            return $general_templates;
        }

        return $entire_website_templates;
    }

    private function is_specific_template($conditions)
    {
        $specific_conditions = [
            'all_posts' => is_singular(post_types: 'post'),
            'all_pages' => is_page(),
            'posts_archive' => is_post_type_archive('post'),
            'category_archive' => is_category(),
            'tag_archive' => is_tag(),
            '404' => is_404(),
            'search' => is_search(),
            'blog' => is_home(),
            'front_page' => is_front_page(),
            'date_archive' => is_date(),
            'author_archive' => is_author()
        ];

        return isset($specific_conditions[$conditions]) && $specific_conditions[$conditions];
    }

    private function is_general_template($conditions)
    {
        $general_conditions = [
            'all_singulars' => is_singular(),
            'all_archives' => is_archive()
        ];

        return isset($general_conditions[$conditions]) && $general_conditions[$conditions];
    }

    private function check_display_conditions($template_id)
    {
        $conditions = get_post_meta($template_id, '_display_condition', true);
        $allowed_roles = get_post_meta($template_id, '_user_roles', true);
        return $this->evaluate_conditions($conditions, $allowed_roles);
    }

    private function evaluate_conditions($conditions, $allowed_roles)
    {
        if (!$this->check_user_role_condition($allowed_roles)) {
            return false;
        }

        if ($conditions === 'entire_website') {
            return true;
        }

        return $this->is_specific_template($conditions) || $this->is_general_template($conditions);
    }

    private function check_user_role_condition($user_roles)
    {
        if ($user_roles === 'all') {
            return true;
        }

        if ($user_roles === 'logged_in' && is_user_logged_in()) {
            return true;
        }

        if ($user_roles === 'logged_out' && !is_user_logged_in()) {
            return true;
        }

        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            return in_array($user_roles, (array)$user->roles);
        }

        return false;
    }
}
