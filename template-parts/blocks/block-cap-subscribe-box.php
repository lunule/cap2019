<?php

/**
 * CAP Email Subscription Box Block Template.
 *
 * @param   array           $block      The block settings and attributes.
 * @param   string          $content    The block inner HTML (empty).
 * @param   bool            $is_preview True during AJAX preview.
 * @param   (int|string)    $post_id    The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'subscribe-box-' . $block['id'];

if( !empty($block['anchor']) )
    $id = $block['anchor'];

// Create class attribute allowing for custom "className" and "align" values.
$className = 'cap-subscribe-box';
if( !empty($block['className']) )
    $className .= ' ' . $block['className'];

if( !empty($block['align']) )
    $className .= ' align' . $block['align'];

// Load values and assing defaults.
$formcontent = get_field('cap_general_single_inpost', 'option');

if ( $formcontent && ( '' !== $formcontent ) ) : ?>

    <div class="wrap--in-post block-gen">

        <div class="in-post">

            <?php echo $formcontent; ?>

        </div>

    </div>

<?php
else: ?>

	<div class="cap-acf-block-error field-not-set">

		<p>The related theme settings are empty/not set. Please populate the fields of the setting block "Subscribe Form Content" at <strong>CAP Theme Settings > General ></strong> <em>Single Template > In-Post Subscribe Form</em>.</p>

	</div>

<?php
endif;  
