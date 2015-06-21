<?php get_header(); ?>
			
			<section id="content" role="main">
			
				<div class="container">

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
						
						<header>
							<h1 itemprop="headline"><?php the_title(); ?></h1>
						</header>
						<section class="post_content clearfix" itemprop="articleBody">
							<?php the_content(); ?>
						</section> <!-- end article section -->
						<footer>
							
						</footer>
						
					</article> <!-- end article -->
					
					<?php endwhile; ?>			
					
					<?php else : ?>
					
					<article id="post-not-found">
					    <h1><?php _e("Not Found", "stripped"); ?></h1>
					    <p><?php _e("Sorry, but the requested resource was not found on this site.", "stripped"); ?></p>
					</article>	
					<?php endif; ?>
				</div> <!-- end .container -->
			</div> <!-- end #content -->

<?php get_footer(); ?>