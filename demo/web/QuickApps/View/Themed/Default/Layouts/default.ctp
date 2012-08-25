<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

use Configure;
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo Configure::read('Variable.language.code'); ?>" version="XHTML+RDFa 1.0" dir="<?php echo Configure::read('Variable.language.direction'); ?>">
	<head>
		<title><?php echo $this->Layout->title(); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<?php echo $this->Layout->meta(); ?>
		<?php echo $this->Layout->stylesheets(); ?>
                <?php echo $this->Html->script('jquery-1.7.1.js'); ?>
		<?php echo $this->Layout->javascripts(); ?>
		<?php echo $this->Layout->header(); ?>
                <?php echo $this->Html->css('aloha.css'); ?>
                <?php echo $this->Html->script('require.js') ; ?>
                        
                     
               
      <script>
	var Aloha = window.Aloha || ( window.Aloha = {} );
	
	Aloha.settings = {
		locale: 'en',
		plugins: {
			format: {
				config : [ 'b', 'i','sub','sup'],
			  	editables : {
					// no formatting allowed for title
					'#title'	: [ ], 
					// formatting for all editable DIVs
					'div'		: [ 'b', 'i', 'del', 'sub', 'sup'  ], 
					// content is a DIV and has class .article so it gets both buttons
					'.article'	: [ 'b', 'i', 'p', 'title', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'pre', 'removeFormat']
			  	}
			},
			list: {
			 	// all elements with no specific configuration get an UL, just for fun :)
				config : [ 'ul' ],
			  	editables : {
					// Even if this is configured it is not set because OL and UL are not allowed in H1.
					'#title'	: [ 'ol' ], 
					// all divs get OL
					'div'		: [ 'ol' ], 
					// content is a DIV. It would get only OL but with class .article it also gets UL.
					'.article'	: [ 'ul' ]
			  	}
			},
			link: {
				config : [ 'a' ],
			  	editables : {
					// No links in the title.
					'#title'	: [  ]
			  	}
			}
		},
		sidebar: {
			disabled: false
		}
	};
</script>
         <script>
		var Aloha = {};
		Aloha.settings = {
			logLevels: {'error': true, 'warn': true, 'info': true, 'debug': true},
			errorhandling: false,
			ribbon: false
		};
	</script>
                       
                <?php echo $this->Html->script('aloha.js'); ?>
                   
	<script type="text/javascript">
Aloha.ready( function() {
	// mark the editable parts
        //Aloha.jQuery('#content').aloha();
	$('.service').aloha();
	//$('#header-top').aloha();
	//$('#quote-inner').aloha();	
});
        </script>
	</head>

	<body>
		<div id="header-top">
                    <div id="toolbar-menu" class="clearfix" >
				<?php echo $this->Layout->blocks('management-menu'); ?>
				<div id="right-btns">
					<?php echo $this->Html->link(__t('Login'), '/user/login'); ?>
					<?php echo $this->Html->link(__t('View Dashboard'), '/admin',  array('target' => '_blank')); ?>
				</div>
			</div>
			<div class="container">
				<?php if (Configure::read('Theme.settings.site_logo')): ?>
					<a href="<?php echo $this->Html->url('/'); ?>" id="logo">
						<?php echo $this->Html->image(Configure::read('Theme.settings.site_logo_url')); ?>
					</a>
				<?php endif; ?>

				<?php if (!$this->Layout->emptyRegion('user-menu')): ?>
				<div id="user-menu">
					<?php echo $this->Layout->blocks('user-menu'); ?>
				</div>
				<?php endif; ?>

				<?php if (!$this->Layout->emptyRegion('language-switcher')): ?>
				<div id="language-switcher">
					<?php echo $this->Layout->blocks('language-switcher'); ?>
				</div>
				<?php endif; ?>

				<?php if (!$this->Layout->emptyRegion('search')): ?>
				<div id="search-block">
					<?php echo $this->Layout->blocks('search'); ?>
				</div>
				<?php endif; ?>

			 </div>
		</div>

		<div id="header-bottom">
			<div class="container">
				<?php echo $this->Layout->blocks('main-menu'); ?>
			</div>
		</div>

		<div id="page">
			<div id="top-shadow"></div>
			<?php if (!$this->Layout->emptyRegion('slider')): ?>
			<div class="slider">
				<div class="container clearfix">
					<?php echo $this->Layout->blocks('slider'); ?>
				</div>
			</div>
			<?php endif; ?>

			<?php if ($this->Layout->is('view.frontpage')): ?>
				<?php if (Configure::read('Theme.settings.site_slogan')): ?>
				<div id="quote">
					<div id="quote-inner">
						<div class="container">
							<span id="quote-icon"></span>
							<p id="slogan"><?php echo __t(Configure::read('Variable.site_slogan')); ?></p>
						</div> <!-- end .container -->
					</div> <!-- end #quote-inner -->
				</div> <!-- end #quote -->
				<?php endif; ?>

				<div class="container">
					<div id="services">
						<div class="container clearfix">
							<div class="service">
								<div class="service-container">
									<?php echo $this->Layout->blocks('services-left'); ?>
								</div>
							</div> <!-- end .service -->

							<div class="service">
								<div class="service-container">
									<?php echo $this->Layout->blocks('services-center'); ?>
								</div>
							</div> <!-- end .service -->

							<div class="service last">
								<div class="service-container">
									<?php echo $this->Layout->blocks('services-right'); ?>
								</div>
							</div> <!-- end .service -->
						</div>
					</div>
				</div>
			<?php else: ?>
				<div class="container">
					<div id="help-blocks">
						<?php echo $this->Layout->blocks('help'); ?>
					</div>

					<?php if ($sessionFlash = $this->Layout->sessionFlash()): ?>
					<div class="session-flash">
						<?php echo $sessionFlash; ?>
					</div>
					<?php endif; ?>
					<?php if (!$this->Layout->emptyRegion('sidebar-left')): ?>
						<div id="sidebar-left">
							<div id="sidebar-bottom">
								<div id="sidebar-content">
									<?php echo $this->Layout->blocks('sidebar-left'); ?>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<div id="content" class="clearfix">
						<?php echo $this->Layout->content(); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<div id="footer">
			<div class="container">
				<?php echo $this->Layout->blocks('footer'); ?>
				<?php
					if ($Layout['feed']) {
						echo $this->Html->link(
							$this->Html->image('feed.png'),
							$Layout['feed'],
							array(
								'class' => 'rss-feed-icon',
								'escape' => false
							)
						);
					}
				?>				
				&nbsp;
			</div>
		</div>
                
		<?php echo $this->Html->script('cufon-yui.js'); ?>
		<?php echo $this->Html->script('Colaborate-Thin_400.font.js'); ?>
		<script type="text/javascript">
			Cufon.replace('p#slogan', { fontFamily: 'Colaborate-Thin', fontSize: '30px' });
			Cufon.replace('h3', { fontFamily: 'Colaborate-Thin', fontSize: '30px' });
			Cufon.replace('.node-full h2.node-title', { fontFamily: 'Colaborate-Thin', fontSize: '40px' });
			Cufon.replace('.node-list h2.node-title', { fontFamily: 'Colaborate-Thin', fontSize: '30px' });
		</script>
         
		<?php echo $this->Layout->footer(); ?>
	</body>
</html>