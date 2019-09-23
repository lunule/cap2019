<?php

/**
 * CAP Email Description List Block Template.
 *
 * @param   array           $block      The block settings and attributes.
 * @param   string          $content    The block inner HTML (empty).
 * @param   bool            $is_preview True during AJAX preview.
 * @param   (int|string)    $post_id    The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'description-list-' . $block['id'];

if( !empty($block['anchor']) )
    $id = $block['anchor'];

// Create class attribute allowing for custom "className" and "align" values.
$className = 'cap-description-list';

if( !empty($block['className']) )
    $className .= ' ' . $block['className'];

if( !empty($block['align']) )
    $className .= ' align' . $block['align'];

// Load values and assing defaults.
$btitle = get_field('cap_block_dl_title');
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    
    <?php
    if ( have_rows('cap_block_dl_list') ) :

        echo "<div class='wrap--cap-dl'><h3>{$btitle}</h3><div class='cap-dl'>";

        while ( have_rows('cap_block_dl_list') ) : the_row();

            $dl_group   = get_sub_field('cap_block_dl_item');
            $term       = $dl_group['term'];
            $def        = $dl_group['definition'];
            $has_item   = ( 
                            ( $term && ( '' !== $term ) ) && 
                            ( $def && ( '' !== $def ) ) 
                          );
            ?>
    
            <?php 
            if ( $has_item ) :

                echo '<dl>';

                    if ( $term && ( '' !== $term ) )
                        echo "<dt class='term'>{$term}<i class='fas fa-chevron-down'></i></dt>";

                    if ( $def && ( '' !== $def ) )
                        echo "<dd class='description'>{$def}</dd>";            

                echo '</dl>';

            endif; ?>

        <?php
        endwhile;

        echo '</dl></div>';

    endif; ?>

</div>