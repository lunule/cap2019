<?php 

// register the widgets
function register_cap_widgets() {

    register_widget( 'CAP_Contributors' );
    register_widget( 'CAP_Podcast_Guests' );

}

add_action( 'widgets_init', 'register_cap_widgets' );

/**
 * Adds CAP_Contributors widget by extending CAP_Guests.
 */
class CAP_Podcast_Guests extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {

		$widget_ops = array(
			'classname'  					=> 'cap-widget--contr',
			'customize_selective_refresh' 	=> true,
			'description' 					=> esc_html__( 'Displays a list of CAP podcast guests.', 'cap' ),
		);

		parent::__construct(

			'cap_podguest_widget', // Base ID
			esc_html__( 'CAP Podcast Guests Widget', 'cap' ), // Name
			$widget_ops

		);
	
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		
		extract( $args );

		// Check the widget options
		$title    		= isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		
		$posts_per_page = ( 
							isset( $instance['posts_per_page'] ) && 
							is_int( $instance['posts_per_page'] )  
						  )
							? $instance['posts_per_page'] 
							: 12;

		echo $args['before_widget']; 
	
		if ( ! empty( $instance['title'] ) ) :

			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		
		endif;

		$allposts = get_posts( array(
			'post_type' 		=> array('podcast'),
			'posts_per_page' 	=> $posts_per_page,
			'post_status' 		=> array('publish'),
		) );

		$guests = [];

		// Get the array of author IDs
		if ( $allposts ) :

			$i = 0;
		    foreach ( $allposts as $post ) : setup_postdata( $post );

				$guest_name 	= get_field('cap_pod_single_guest', $post->ID);
				$guest_has_name = (
									isset($guest_name) &&
									( '' !== $guest_name ) &&
									( NULL !== $guest_name )
								  );

				$guest_img_Obj 	= get_field('cap_pod_single_guestimg', $post->ID);
				$guest_img 		= $guest_img_Obj['sizes']['thumbnail'];
				$guest_has_img 	= (
									!empty( $guest_img_Obj ) &&						
									( filter_var( $guest_img, FILTER_VALIDATE_URL) )
								  );
									

				if ( $guest_has_name && $guest_has_img ) :

					$guests[$i]['name'] = $guest_name;
					$guests[$i]['img'] 	= $guest_img;					
					$guests[$i]['pos'] 	= get_field('cap_pod_single_guestpos', $post->ID);
					$guests[$i]['inst'] = get_field('cap_pod_single_guestinst', $post->ID);

				endif;

				$i++;
			endforeach;

		    wp_reset_postdata();
		
		endif;		

		//var_dump( $guests );

		$guests = array_unique( $guests, SORT_REGULAR );

		//var_dump( $guests );

		if ( !empty( $guests ) ) : ?>

			<div class="contributors">

				<?php
				foreach ( $guests as $guest ) : 

					$name 	= $guest['name'];
					$img 	= $guest['img'];
					$pos 	= $guest['pos'];
					$inst 	= $guest['inst'];

					echo '<div class="contributor flex-container">';
					?>

						<div class="contr-img flex-item">
							
							<img src="<?php echo $img; ?>" width="" height="" alt="<?php _e( 'Guest avatar', 'cap' ); ?>" />

						</div>

						<div class="contr-data flex-item">

							<?php
							echo "<h4 class='contr-name'>{$name}</h4>";
							
							$has_pos 	= ( $pos && ( '' !== $pos ) );
							$has_inst 	= ( $inst && ( '' !== $inst ) );
							$has_both 	= ( 
											( $pos && ( '' !== $pos ) ) 	&&
											( $inst && ( '' !== $inst ) )
										  );
							$has_some 	= ( 
											( $pos && ( '' !== $pos ) ) 	||
											( $inst && ( '' !== $inst ) )
										  );
							$glue 		= __( 'at', 'cap' );

							$meta_Str = '';
							if ( $has_some ) 	$meta_Str .= "<h4 class='contr-meta'>";
							if ( $has_pos ) 	$meta_Str .= "<span class='contr-pos'>{$pos}</span>";
							if ( $has_both ) 	$meta_Str .= "<span class='contr-meta-glue'> {$glue} </span>";
							if ( $has_inst ) 	$meta_Str .= "<span class='contr-inst'>{$inst}</span>";
							if ( $has_some ) 	$meta_Str .= "</h4>";

							echo $meta_Str;
							?>

						</div>

					</div>

				<?php
				endforeach; ?>

			</div>
		
		<?php
		endif;

		echo $args['after_widget'];		
	
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		
		// Set widget defaults
		$defaults = array(
			'title'    			=> !empty( $instance['title'] ) 
										? $instance['title'] 
										: esc_html__( 'CAP Contributors Include', 'cap' ),
			'posts_per_page'    => 12,
		);

		// Parse current settings with defaults
		extract( wp_parse_args( ( array ) $instance, $defaults ) );
		?>

		<p>

			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'cap' ); ?></label> 
	
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">

		</p>

		<p>

			<label for="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>"><?php _e( 'Number of Contributors', 'cap' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_per_page' ) ); ?>" type="number" value="<?php echo esc_attr( $posts_per_page ); ?>" />

		</p>

		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']    			= ( ! empty( $new_instance['title'] ) ) 
											? sanitize_text_field( $new_instance['title'] ) 
											: '';
		
		$instance['posts_per_page']   	= isset( $new_instance['posts_per_page'] ) 
											? absint( $new_instance['posts_per_page'] )
											: '';
						
		return $instance;		

	}

}

/**
 * Adds CAP_Contributors widget.
 */
class CAP_Contributors extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {

		$widget_ops = array(
			'classname'  					=> 'cap-widget--contr',
			'customize_selective_refresh' 	=> true,
			'description' 					=> esc_html__( 'Displays a list of CAP contributors.', 'cap' ),
		);

		parent::__construct(

			'cap_contr_widget', // Base ID
			esc_html__( 'CAP Contributors Widget', 'cap' ), // Name
			$widget_ops

		);
	
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		
		extract( $args );

		// Check the widget options
		$title    		= isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';

		$post_type     	= ( 
							isset( $instance['post_type'] ) 	&&
							is_array( $instance['post_type'] ) 	&&
							!empty( $instance['post_type'] ) 	&&
							!( ( 1 == count( $instance['post_type'] ) ) && ( '' == $instance['post_type'][0] ) ) 
						  ) 
							? $instance['post_type'] 
							: array('post', 'podcast', 'analysis', 'studentpost');
		
		$posts_per_page = ( 
							isset( $instance['posts_per_page'] ) && 
							is_int( $instance['posts_per_page'] )  
						  )
							? $instance['posts_per_page'] 
							: 12;

		echo $args['before_widget']; 
	
		if ( ! empty( $instance['title'] ) ) :

			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		
		endif;

		$allposts = get_posts( array(
			'post_type' 		=> $post_type,
			'posts_per_page' 	=> -1,
			'post_status' 		=> array('publish'),
		) );

		$authors = [];

		// Get the array of author IDs
		if ( $allposts ) :

		    foreach ( $allposts as $post ) : setup_postdata( $post );
				$authors[] = get_the_author_meta( 'ID' );
			endforeach;

		    wp_reset_postdata();
		
		endif;		

		$authors = array_slice( array_unique( $authors ), 0, $posts_per_page );
		shuffle( $authors ); 

		if ( !empty( $authors ) ) : ?>

			<div class="contributors">

				<?php
				foreach ( $authors as $author ) : 

					echo '<div class="contributor flex-container">';

						/** 
						 * Kelleni fog:
						 * - A NÉV STRING
						 * - AZ AVATAR
						 * - MEG A POSITION STB. SZIRSZAR
						 */
						
						$author_ID 		= $author;

						$author_url 	= get_author_posts_url( $author_ID );

						$avatar 		= get_avatar( $author_ID, 100 );
						
						$fname 			= get_the_author_meta( 
											'first_name', 
											$author_ID 
										  );
						$lname 			= get_the_author_meta( 
											'last_name', 
											$author_ID 
										  );
						$nick 			= get_the_author_meta( 
											'display_name', 
											$author_ID 
										  );

						$name 			= ( $fname && $lname ) 
											? $fname . ' ' . $lname
											: $nick;

						$name_Str 		= ( $name && ( '' !== $name ) )
											? "<a href='{$author_url}'>{$name}</a>"
											: '';

						$stud_inst 		= get_field(
											'student_inst', 
											'user_' . $author_ID 
										  );

						$age 			= get_field(
											'student_age', 
											'user_' . $author_ID 
										  );

						$pos 			= get_field(
											'user_position', 
											'user_' . $author_ID 
										  );

						$inst 			= get_field(
											'user_institution', 
											'user_' . $author_ID 
										  );

						$umeta 			= get_userdata( $author_ID );
						$role 			= $umeta->roles;

						if ( in_array( 'administrator', $role ) ) :
							$is_admin = true; 	$is_student = false; 	$is_author = false;
						elseif ( in_array( 'student_author', $role ) ) :			
							$is_admin = false; 	$is_student = true; 	$is_author = false;
						else :
							$is_admin = false; 	$is_student = false; 	$is_author = true;
						endif;

						if ( $is_student ) :
							$pos 	= '';
							$inst 	= $stud_inst;
						endif; 

						?>

						<div class="contr-img flex-item">
							
							<a href="<?php echo $author_url; ?>"><?php echo $avatar; ?></a>

						</div>

						<div class="contr-data flex-item">

							<?php
							echo "<h4 class='contr-name'><a href='{$author_url}'>{$name}</a></h4>";
							
							$has_pos 	= ( $pos && ( '' !== $pos ) );
							$has_inst 	= ( $inst && ( '' !== $inst ) );
							$has_both 	= ( 
											( $pos && ( '' !== $pos ) ) 	&&
											( $inst && ( '' !== $inst ) )
										  );
							$has_some 	= ( 
											( $pos && ( '' !== $pos ) ) 	||
											( $inst && ( '' !== $inst ) )
										  );
							$glue 		= __( 'at', 'cap' );

							$meta_Str = '';
							if ( $has_some ) 	$meta_Str .= "<h4 class='contr-meta'>";
							if ( $has_pos ) 	$meta_Str .= "<span class='contr-pos'>{$pos}</span>";
							if ( $has_both ) 	$meta_Str .= "<span class='contr-meta-glue'> {$glue} </span>";
							if ( $has_inst ) 	$meta_Str .= "<span class='contr-inst'>{$inst}</span>";
							if ( $has_some ) 	$meta_Str .= "</h4>";

							echo $meta_Str;
							?>

						</div>

					</div>

				<?php
				endforeach; ?>

			</div>
		
		<?php
		endif;

		echo $args['after_widget'];		
	
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		
		// Set widget defaults
		$defaults = array(
			'title'    			=> !empty( $instance['title'] ) 
										? $instance['title'] 
										: esc_html__( 'CAP Contributors Include', 'cap' ),
			'posts_per_page'    => 12,
			'post_type'  		=> array(),
		);

		// Parse current settings with defaults
		extract( wp_parse_args( ( array ) $instance, $defaults ) );
		?>

		<p>

			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'cap' ); ?></label> 
	
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">

		</p>

		<p>

			<label for="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>"><?php _e( 'Number of Contributors', 'cap' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_per_page' ) ); ?>" type="number" value="<?php echo esc_attr( $posts_per_page ); ?>" />

		</p>

		<p>

			<label for="<?php echo $this->get_field_id( 'post_type' ); ?>"><?php _e( 'Post Types Included', 'cap' ); ?></label>

			<select name="<?php echo $this->get_field_name( 'post_type' ); ?>[]" id="<?php echo $this->get_field_id( 'post_type' ); ?>" class="widefat" multiple>
			
				<?php
				// Your options array
				$options = array(
					''        		=> __( 'Select', 'cap' ),
					'analysis' 		=> __( 'Analysis', 'cap' ),
					'post' 			=> __( 'Post', 'cap' ),
					'podcast' 		=> __( 'Podcast', 'cap' ),
					'studentpost' 	=> __( 'Student Post', 'cap' ),
				);

				// Loop through options and add each one to the select dropdown
				foreach ( $options as $key => $name ) :
					
	                printf(
	                    '<option value="%s" id="%s" %s>%s</option>',
	                    esc_attr( $key ),
	                    esc_attr( $key ),
	                    in_array( esc_attr( $key ), $post_type ) ? 'selected="selected"' : '',
	                    $name
	                );				

				endforeach; ?>
			
			</select>
		
		</p>		

		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title']    			= ( ! empty( $new_instance['title'] ) ) 
											? sanitize_text_field( $new_instance['title'] ) 
											: '';
		
		$instance['posts_per_page']   	= isset( $new_instance['posts_per_page'] ) 
											? absint( $new_instance['posts_per_page'] )
											: '';
		
		$post_type 				  		= isset( $new_instance['post_type'] ) 
											? (array) $new_instance['post_type'] 
											: array();
		$instance['post_type'] 			= $post_type;
				
		return $instance;		

	}

}