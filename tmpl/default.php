<?php
/**
 * @package     WT JShopping Swiper carousel
 * @copyright   Copyright (C) 2022-2023 Sergey Tolkachyov. All rights reserved.
 * @author      Sergey Tolkachyov - https://web-tolk.ru
 * @link 		https://web-tolk.ru
 * @version 	1.1.0
 * @license     GNU General Public License version 3 or later
 */
use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;
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


?>

<?php if (count($list) > 0) : ?>

    <div class="mod_wtjshoppingswipercarousel<?php echo $module->id; ?> swiper">
        <div class="swiper-wrapper">
			<?php foreach ($list as $list_item) : ?>
                <div class="swiper-slide">
					<?php
					$img_attribs = [
						'class' => 'w-100 h-auto'
					];

					echo HTMLHelper::image($list_item->path, $list_item->alt, $img_attribs);
					?>
                </div>
			<?php endforeach; ?>
        </div>

		<?php if ($params->get('show_swiper_pagination') == 1): ?>
            <div class="swiper-pagination swiper-pagination_<?php echo $module->id; ?>"></div>
		<?php endif; ?>

		<?php if ($params->get('show_swiper_navigation') == 1): ?>
            <div class="swiper-button-prev swiper-button-prev_<?php echo $module->id; ?>"></div>
            <div class="swiper-button-next swiper-button-next_<?php echo $module->id; ?>"></div>
		<?php endif; ?>
		<?php if ($params->get('show_swiper_scrollbar') == 1): ?>
            <div class="swiper-scrollbar swiper-scrollbar<?php echo $module->id; ?>"></div>
		<?php endif; ?>
    </div>
<?php endif; ?>
<script>
    if (document.readyState != 'loading') {
        loadWTJSwiper<?php echo $module->id;?>();
    } else {
        document.addEventListener('DOMContentLoaded', loadWTJSwiper<?php echo $module->id;?>);
    }

    function loadWTJSwiper<?php echo $module->id;?>() {

        let swiper_options<?php echo $module->id;?> = Joomla.getOptions('mod_wtjshoppingswipercarousel<?php echo $module->id;?>');
        const swiper<?php echo $module->id;?> = new Swiper('.mod_wtjshoppingswipercarousel<?php echo $module->id;?>', swiper_options<?php echo $module->id;?>);
    }

</script>
