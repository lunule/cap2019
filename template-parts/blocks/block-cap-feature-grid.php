<?php

/**
 * CAP Feature Grid Block Template.
 *
 * @param   array           $block      The block settings and attributes.
 * @param   string          $content    The block inner HTML (empty).
 * @param   bool            $is_preview True during AJAX preview.
 * @param   (int|string)    $post_id    The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'feature-grid-' . $block['id'];

if( !empty($block['anchor']) )
    $id = $block['anchor'];

// Create class attribute allowing for custom "className" and "align" values.
$className = 'cap-feature-grid';

if( !empty($block['className']) )
    $className .= ' ' . $block['className'];

if( !empty($block['align']) )
    $className .= ' align' . $block['align'];

// Load values and assing defaults.
$btitle     = get_field('cap_block_featgrid_title');
$featcount  = count( get_field('cap_block_featgrid_feats') );
$featnth    = 3;

if ( 1 == $featcount )
    $featnth = 1;

if ( 0 == ( $featcount % 2 ) )
    $featnth = 2;
?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">

    <?php
    if ( have_rows('cap_block_featgrid_feats') ) :

        echo "<div class='wrap--cap-features'><h3>{$btitle}</h3><div class='cap-features flex-container featcount-{$featcount} nth-{$featnth}'>";

        while ( have_rows('cap_block_featgrid_feats') ) : the_row();

            $title  = get_sub_field('title');
            $icon   = get_sub_field('icon');
            $desc   = get_sub_field('desc');
            ?>

            <div class="cap-feature flex-item">
        
                <?php if ( $title && ( '' !== $title ) ) : ?>

                    <div class="feature__title">
                        <h4><i class="<?php echo $icon; ?>"></i><?php echo $title; ?></h4>
                    </div>

                <?php 
                endif;

                if ( $desc && ( '' !== $desc ) ) : ?>

                    <div class="feature_desc">
                        <?php echo $desc; ?>
                    </div>

                <?php 
                endif; ?>

            </div>

        <?php
        endwhile;

        echo '</div></div>';

    endif;  ?>

</div>