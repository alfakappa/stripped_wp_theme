<?php get_header(); ?>
			<section id="carousel">
				<!-- Carousel
				================================================== -->
				<div id="post-carousel" class="carousel slide" data-ride="carousel">
			      <div class="container">
			      	<?php
					$args = array(
						'posts_per_page' => 5,
						'category_name' =>'featured'
						);
					$slider_query = new WP_Query( $args );
					?>

					<?php if ( $slider_query->have_posts() ) : 
						$i = 0;
					?>
					<ol class="carousel-indicators">
					    <?php while ( $slider_query->have_posts() ) : $slider_query->the_post();
		
					    ?>
					    <li data-target="#post-carousel" data-slide-to="<?php echo $i;?>" class="<?php if ( $i == 0 ) { echo 'active'; } ?>"></li>
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
									</div>
									<div class="image">
										<?php the_post_thumbnail('full');?>
									</div>
								</div>
						<?php endwhile;?>
					<?php wp_reset_postdata(); ?>
					<?php endif;?>
			     	</div> <!--.carousel-inner -->
			      <a class="left carousel-control" href="#post-carousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
			      <a class="right carousel-control" href="#post-carousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
			      </div>
			    </div><!-- /.carousel -->
			</section>
			<section id="blog">
				<div class="container">
					<?php
					$args = array(
						'posts_per_page' => 2,
						);
					$blog_query = new WP_Query( $args );
					?>
					<?php if ( $blog_query->have_posts() ) : while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>
						<article class="blog">
							<h3><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
							<?php the_excerpt();?>
							<a href="<?php the_permalink();?>" class="btn btn-default">
								<?php _e('Read more','stripped');?>
							</a>
						</article>
						<?php endwhile;?>
						<?php wp_reset_postdata();?>
					<?php else : ?>
					<article class="no-blog">
						<?php _e('There are no blogs available yet','stripped');?>
					</article>
					<?php endif;?>
				</div>
			</section>
<?php get_footer(); ?>