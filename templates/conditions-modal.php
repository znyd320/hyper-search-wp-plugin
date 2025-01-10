<?php if (!defined('ABSPATH')) exit; ?>

<div id="panda-conditions-modal" style="display: none;">
    <div class="panda-modal-content">
        <div class="panda-modal-header">
            <h2>Display Conditions</h2>
            <button class="panda-modal-close">&times;</button>
        </div>
        
        <div class="panda-modal-body">
            <div class="panda-conditions-list">
                <?php foreach ($saved_conditions as $condition): ?>
                    <div class="panda-condition-item">
                        <span class="condition-type"><?php echo esc_html($condition['type']); ?></span>
                        <span class="condition-value"><?php echo esc_html($condition['value']); ?></span>
                        <button class="remove-condition" data-id="<?php echo esc_attr($condition['id']); ?>">&times;</button>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <button class="add-new-condition">Add New Condition</button>
        </div>
    </div>
</div>
