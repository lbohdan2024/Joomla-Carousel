<?php
/**
 * @package     WT JShopping Swiper carousel
 * @copyright   Copyright (C) 2022-2023 Sergey Tolkachyov. All rights reserved.
 * @author      Sergey Tolkachyov - https://web-tolk.ru
 * @link 		https://web-tolk.ru
 * @version 	1.0.0
 * @license     GNU General Public License version 3 or later
 */
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

/**
 * @var $module
 * @var $app
 * @var $input
 * @var $params
 * @var $template
 * @var $list
 * @var $moduleclass_sfx
 * @var $jshopConfig
 */


\defined('_JEXEC') or die;

?>

<?php if(count($list) > 0) :?>

<div class="mod_wtjshoppingswipercarousel<?php echo $module->id;?> swiper">
	<div class="swiper-wrapper">
		<?php foreach ($list as $product) :?>
			<div class="swiper-slide">
				<article class="card border-0 p-0 h-100">
						<?php if ($params->get('joomshopping_products_show_image', 1)) : ?>
							<div class="item_image position-relative">
								<?php if ($params->get('joomshopping_products_label_prod', 1) == 1 && property_exists($product,'_label_image')) : ?>
									<div class="product_label d-flex justify-content-start position-absolute z-1">
                                        <?php
                                            $label_attribs = [
//                                                    'id' => 'some_id',
//                                                    'loading' => 'lazy',
                                            ];
                                            echo HTMLHelper::image($product->_label_image,$product->_label_name,$label_attribs);
                                        ?>
									</div>
								<?php endif; ?>
								<a href="<?php echo $product->product_link; ?>" class="stretched-link">

									<?php
									$img_options = [
										'title'   => $product->name,
										'loading' => 'lazy',
										'class'=>'img-fluid'
									];
									if($jshopConfig->image_product_width > 0){
										$img_options['width'] = $jshopConfig->image_product_width;
									}
									if($jshopConfig->image_product_height > 0){
										$img_options['height'] = $jshopConfig->image_product_height;
									}
									echo HTMLHelper::image($product->image,$product->name,$img_options);
									?>

								</a>
							</div>
						<?php endif; ?>
						<div class="card-body bg-white d-flex flex-column justify-content-center">
							<?php if ($params->get('show_name', 1)) : ?>
								<div class="item_name mt-auto text-center">
									<a href="<?php echo $product->product_link ?>" class="text-decoration-none"><h6><?php echo $product->name ?></h6></a>
								</div>
							<?php endif; ?>
							<?php if ($params->get('joomshopping_products_product_price', 1)) : ?>
								<?php if ($params->get('joomshopping_products_show_old_price', 1) && !empty($product->product_old_price)) : ?>
									<s class="text-center"><?php echo \JSHelper::formatprice($product->product_old_price); ?></s>
								<?php endif; ?>
								<?php if (!empty($product->product_price)) : ?>
									<div class="item_price h3 text-center">
										<?php echo \JSHelper::formatprice($product->product_price); ?>
									</div>
								<?php endif; ?>
							<?php endif; ?>
						</div>
						<?php if ($params->get('joomshopping_products_show_buy_button', 0) && !empty($product->buy_link)) : ?>
							<div class="card-footer d-flex bg-white border-0 justify-content-center">
								<a href="<?php echo $product->buy_link;?>" class="<?php echo $params->get('joomshopping_products_buy_button_css_class','btn btn-primary');?>"><?php echo Text::_('JSHOP_BUY');?></a>
							</div>
						<?php endif; ?>

				</article>


			</div>
		<?php endforeach;?>
	</div>

	<?php if($params->get('show_swiper_pagination') == 1):?>
		<div class="swiper-pagination swiper-pagination_<?php echo $module->id;?>"></div>
	<?php endif;?>

	<?php if($params->get('show_swiper_navigation') == 1):?>
		<div class="swiper-button-prev swiper-button-prev_<?php echo $module->id;?>"></div>
		<div class="swiper-button-next swiper-button-next_<?php echo $module->id;?>"></div>
	<?php endif;?>
	<?php if($params->get('show_swiper_scrollbar') == 1):?>
		<div class="swiper-scrollbar swiper-scrollbar<?php echo $module->id;?>"></div>
	<?php endif;?>
</div>
<?php endif;?>
<script>
	if (document.readyState != 'loading') {
		loadWTJSwiper<?php echo $module->id;?>();
	} else {
		document.addEventListener('DOMContentLoaded', loadWTJSwiper<?php echo $module->id;?>);
	}
	function loadWTJSwiper<?php echo $module->id;?>(){

		let swiper_options<?php echo $module->id;?> = Joomla.getOptions('mod_wtjshoppingswipercarousel<?php echo $module->id;?>');
		const swiper<?php echo $module->id;?> = new Swiper('.mod_wtjshoppingswipercarousel<?php echo $module->id;?>', swiper_options<?php echo $module->id;?>);
	}

</script>

<?php

/*

{
			speed: 400,
			spaceBetween: 100,
			autoplay: false,
			breakpoints: {
				320: {
					slidesPerView: 1,
					spaceBetween: 10
				},
				768: {
					slidesPerView: 2,
					spaceBetween: 20
				},
				982: {
					slidesPerView: 3,
					spaceBetween: 20
				}
			}
			<?php if($params->get('show_swiper_pagination') == 1) :?>
				,pagination: {
					el: '.swiper-pagination_<?php echo $module->id;?>',
					type: 'bullets',
				}
			<?php endif; ?>

			<?php if($params->get('show_swiper_scrollbar') == 1) :?>
				,scrollbar: {

					el: '.swiper-scrollbar_<?php echo $module->id;?>',
					draggable: true,
				}
			<?php endif; ?>
			<?php if($params->get('show_swiper_navigation') == 1) :?>
				,navigation: {
					nextEl: '.swiper-button-next_<?php echo $module->id;?>',
					prevEl: '.swiper-button-prev_<?php echo $module->id;?>',
				}
			<?php endif; ?>
		}

 */

?>