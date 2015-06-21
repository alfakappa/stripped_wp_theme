<?php get_header(); ?>
			<section id="carousel">
				<!-- Carousel
				================================================== -->
				<div id="auto-carousel" class="carousel slide" data-ride="carousel">
			      <div class="container">
			      	<?php
					$args = array(
						'posts_per_page' => 5,
						'post_type' => 'posts',
						'category' =>'featured'
						);
					$slider_query = new WP_Query( $args );
					?>

					<?php if ( $slider_query->have_posts() ) : 
						$i = 0;
					?>
					<ol class="carousel-indicators">
					    <?php while ( $slider_query->have_posts() ) : $slider_query->the_post();
		
					    ?>
					    <li data-target="#auto-carousel" data-slide-to="<?php echo $i;?>" class="<?php if ( $i == 0 ) { echo 'active'; } ?>"></li>
					    <?php
					    $i++;
					    endwhile; ?>
					 </ol>
					 <?php wp_reset_postdata();?>
					<?php endif;?>

					<div class="carousel-inner">
					<?php if ( $slider_query->have_posts() ) :
						$i = 0;
						while ( $slider_query->have_posts() ) : $slider_query->the_post();
						$i++; ?>
							  	<div class="item <?php if ( $i == 1 ) { echo 'active'; } ?>">
									<div class="content">
										<h3 class="h1"><?php the_title();?></h3>
										<?php the_content();?>
										<a href="#contact-form" class="btn btn-blue smoothscroll"><?php _e('Ja! Ik wil deze auto!','wprf');?></a>
									</div>
									<div class="image">
										<?php the_post_thumbnail('full');?>
									</div>
								</div>
						<?php endwhile;?>
					<?php wp_reset_postdata(); ?>
					<?php endif;?>
			     	</div> <!--.carousel-inner -->
			      <a class="left carousel-control" href="#auto-carousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
			      <a class="right carousel-control" href="#auto-carousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
			      </div>
			    </div><!-- /.carousel -->
			</section>
			<section id="blog">
				<?php
					$args = array(
						'posts_per_page' => 2,
						'post_type' => 'posts',
						'category' =>'blog'
						);
					$blog_query = new WP_Query( $args );
					?>
					<?php if ( $blog_query->have_posts() ) : while ( $slider_query->have_posts() ) : $slider_query->the_post(); ?>
						<article class="blog">
							<h3><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
							<?php the_excerpt();?>
							<a href="<?php the_permalink();?>" class="btn btn-default">
								<?php _e('Lees verder','endt');?>
							</a>
						</article>
						<?php endwhile;?>
						<?php wp_reset_postdata();?>
					<?php endif;?>
				
			</section>
			<section id="contact-form">
				<div class="container">
					<div class="col-md-8 col-sm-10 col-md-offset-2 col-sm-offset-1">
						<h1><?php _e('Neem direct contact op!','endt');?></h1>
						<?php gravity_form( 1, $display_title = false, $display_description = true, $display_inactive = false, $field_values = null, $ajax = false );?>
					</div>
				</div>
			</section>

<?php get_footer(); ?>