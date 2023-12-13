<?php
/**
 * @package     WT JShopping Swiper carousel
 * @copyright   Copyright (C) 2022-2023 Sergey Tolkachyov. All rights reserved.
 * @author      Sergey Tolkachyov - https://web-tolk.ru
 * @link 		https://web-tolk.ru
 * @version 	1.0.0
 * @license     GNU General Public License version 3 or later
 */

namespace Joomla\Module\Wtjshoppingswipercarousel\Site\Helper;

use Joomla\CMS\Filesystem\File;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Component\Jshopping\Site\Table\ConfigTable;
use Joomla\Filesystem\Folder;
use Joomla\Component\Jshopping\Site\Model\Productlist;


\defined('_JEXEC') or die;


/**
 * Helper for mod_wtjshoppingswipercarousel
 *
 * @since  1.0.0
 */
class WtjshoppingswipercarouselHelper
{
	public function getList($params, $app): array
	{
		$list = [];

		if ($params->get('carousel_type') == 'folder')
		{
			$list = $this->getImagesFromFolder($params);
		}
		elseif ($params->get('carousel_type') == 'images')
		{
			$list = $this->getImagesFromModuleParams($params);
		}
		elseif ($params->get('carousel_type') == 'joomshopping_products')
		{
			$list = $this->getJshoppingProducts($params, $app);
		}

		return $list;
	}

	/**
	 * Return Joomshopping config object
	 *
	 * @return object|ConfigTable|mixed
	 *
	 * @since 1.0.0
	 */
	public function getJshopConfig(): object
	{
		$jshopConfig = \JSFactory::getConfig();

		return $jshopConfig;
	}

	/**
	 * This function is calling from module dispatcher
	 *
	 * @param $data
	 * @param $app
	 *
	 *
	 * @since 1.0.0
	 */
	public function getSwiperParams($data, $app): void
	{
		$params        = $data['params'];
		$module_id     = ($data['module'])->id;
		$swiper_params = [
			'speed'          => $params->get('speed', 400),
			'spaceBetween'   => $params->get('spaceBetween', 100),
			'allowTouchMove' => $params->get('allowTouchMove', 1),
			'autoHeight'     => $params->get('autoHeight', 0),
			'direction'      => $params->get('direction', 'horizontal'),
			'allowSlideNext' => $params->get('allowSlideNext', 1),
			'allowSlidePrev' => $params->get('allowSlidePrev', 1),

		];
		/**
		 * Navigation
		 */
		if ($params->get('show_swiper_navigation', 0) == 1)
		{
			$pagination                  = [
				'nextEl' => '.swiper-button-next_' . $module_id,
				'prevEl' => '.swiper-button-prev_' . $module_id,
			];
			$swiper_params['navigation'] = $pagination;
		}
		else
		{
			$swiper_params['navigation'] = false;
		}

		/**
		 * Pagination
		 */

		if ($params->get('show_swiper_pagination', 0) == 1)
		{
			$pagination                       = [];
			$pagination['el']                 = '.swiper-pagination_' . $module_id;
			$pagination['dynamicBullets']     = $params->get('dynamicBullets', 1);
			$pagination['dynamicMainBullets'] = $params->get('dynamicMainBullets', 4);
			$pagination['type']               = $params->get('pagination_type', 'bullets');

			$swiper_params['pagination'] = $pagination;
		}
		else
		{
			$swiper_params['pagination'] = false;
		}
		/**
		 * Breakpoints
		 */
		if ($params->get('use_breakpoints', 0) == 1 && count((array) $params->get('breakpoints')) > 0)
		{
			$breakpoints = [];
			foreach ($params->get('breakpoints') as $breakpoint)
			{
				$breakpoints[$breakpoint->breakpoint] = [
					'slidesPerView' => $breakpoint->slidesPerView,
					'spaceBetween'  => $breakpoint->spaceBetween,
					'direction'  =>  (!empty($breakpoint->direction) ? $breakpoint->direction : 'horizontal'),
				];
			}

			$swiper_params['breakpoints'] = $breakpoints;
		}

		/**
		 * Scrollbar
		 */

		if ($params->get('show_swiper_scrollbar', 0) == 1)
		{
			$scrollbar              = [];
			$scrollbar['el']        = '.swiper-scrollbar' . $module_id;
			$scrollbar['draggable'] = $params->get('scrollbar_draggable', 0);
			if (!empty($params->get('dragSize')))
			{
				$scrollbar['dragSize'] = $params->get('dragSize');
			}

			$swiper_params['scrollbar'] = $scrollbar;
		}
		else
		{
			$swiper_params['scrollbar'] = false;
		}

		/**
		 * Autoplay
		 */

		if ($params->get('enable_autoplay', 0) == 1)
		{
			$autoplay                         = [];
			$autoplay['disableOnInteraction'] = $params->get('scrollbar_draggable', 0);
			$autoplay['delay']                = $params->get('delay', 3000);


			$swiper_params['autoplay'] = $autoplay;
		}
		else
		{
			$swiper_params['autoplay'] = false;
		}

		$app->getDocument()->addScriptOptions('mod_wtjshoppingswipercarousel' . $module_id, $swiper_params);

	}

	/**
	 * @param $params
	 *
	 * @return array images
	 *
	 * @since 1.0.0
	 */
	private function getImagesFromFolder($params): array
	{
		$images            = [];
		$files_from_folder = Folder::files(JPATH_ROOT . '/images/' . $params->get('folder'));
		$images_extensions = ['bmp', 'gif', 'jpg', 'png', 'jpeg', 'webp', 'avif'];
		foreach ($files_from_folder as $file)
		{
			if (in_array(File::getExt($file), $images_extensions))
			{
				$image       = new \stdClass();
				$image->path = 'images/' . $params->get('folder') . '/' . $file;
				$image->alt  = $params->get('folder_images_alt');
				$images[]    = $image;
			}
		}

		return $images;
	}

	/**
	 *
	 * @param $params
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */
	private function getImagesFromModuleParams($params): array
	{
		$list = [];
		foreach ($params->get("fields") as $field)
		{
			$image            = new \stdClass();
			$clean_image_path = HTMLHelper::cleanImageURL($field->image);
			$image->path      = $clean_image_path->url;
			$image->alt       = $field->image_alt;
			$list[]           = $image;
		}

		return $list;
	}


	/**
	 * Return JoomShopping product list
	 * @param $params
	 * @param $app
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */
	private function getJshoppingProducts($params, $app): array
	{
		PluginHelper::importPlugin('jshoppingproducts');
		$jshopConfig = $this->getJshopConfig();
		$noimage     = $jshopConfig->image_product_live_path . "/noimage.gif";
		$filters     = [];
		$count       = $params->get('joomshopping_products_count_products', 4);
		$list = [];
		if ($params->get('joomshopping_products_type') == 'last_products')
		{
			$productlist = \JSFactory::getModel('last', 'Site\\Productlist');
			$categories = $params->get('catids', []);
			if(is_array($categories) && count($categories) > 0){
				$categories = array_map('intval', $categories);
			}
			$filters['categorys'] = $categories;

			$order = 'prod.product_id';
//		$order = 'prod.'.$params->get('order_by', 'product_id');
			$rows = $productlist->getLoadProducts($filters, $order, 'DESC', 0, $count);

			$app->triggerEvent('onBeforeDisplayProductList', [&$rows]);
			$view       = new \stdClass();
			$view->rows = $rows;
			$app->triggerEvent('onBeforeDisplayProductListView', [&$view, &$productlist]);
			$list = $view->rows;

		}

		if ($params->get('joomshopping_products_type') == 'products_by_ids')
		{
			$product_ids = $params->get('joomshopping_products_ids');

			if (!empty($product_ids))
			{
				if (strpos($product_ids, ',') !== false)
				{
					$product_ids = explode(',', $product_ids);
					$product_ids = array_map('trim',$product_ids);

				} else {
					$product_ids = (array)trim($product_ids);
				}

				if (is_array($product_ids) && count($product_ids) > 0)
				{

					$product_list = new Productlist\ListModel();

					/**
					 * @method getLoadProducts()
					 * @var $filters               array - ['products'=> $product_ids]
					 * @var $order                 string
					 * @var $order_by              string
					 * @var $limitstart            int
					 * @var $limit                 int
					 * @var $listProductUpdateData bool
					 */
					$list = $product_list->getLoadProducts(['products' => $product_ids], 'FIELD(prod.product_id, ' . implode(',', $product_ids) . ')', null, 0, 0, 1);
				}
			}
		}

		if ($params->get('joomshopping_products_type') == 'toprating_products')
		{
			$product = \JSFactory::getModel('toprating', 'Site\\Productlist');
			$categories = $params->get('catids', []);
			if(is_array($categories) && count($categories) > 0){
				$categories = array_map('intval', $categories);
			}
			$filters['categorys'] = $categories;
			$rows                 = $product->getLoadProducts($filters, null, 'DESC', 0, $count);

			$app->triggerEvent('onBeforeDisplayProductList', [&$rows]);
			$view       = new \stdClass();
			$view->rows = $rows;
			$app->triggerEvent('onBeforeDisplayProductListView', [&$view, &$product]);
			$list = $view->rows;

		}
		if ($params->get('joomshopping_products_type') == 'tophits_products')
		{
			$product = \JSFactory::getModel('tophits', 'Site\\Productlist');
			$categories = $params->get('catids', []);
			if(is_array($categories) && count($categories) > 0){
				$categories = array_map('intval', $categories);
			}
			$filters['categorys'] = $categories;

			$rows = $product->getLoadProducts($filters, null, 'DESC', 0, $count);

			$app->triggerEvent('onBeforeDisplayProductList', [&$rows]);
			$view       = new \stdClass();
			$view->rows = $rows;
			$app->triggerEvent('onBeforeDisplayProductListView', [&$view, &$product]);
			$list = $view->rows;

		}
		if ($params->get('joomshopping_products_type') == 'bestseller_products')
		{
			$categories = $params->get('catids', []);
			if(is_array($categories) && count($categories) > 0){
				$categories = array_map('intval', $categories);
			}

			$filters['categorys'] = $categories;
			$productlist          = \JSFactory::getModel('bestseller', 'Site\\Productlist');
			$rows                 = $productlist->getLoadProducts($filters, null, 'DESC', 0, $count);
			$app->triggerEvent('onBeforeDisplayProductList', [&$rows]);
			$view       = new \stdClass();
			$view->rows = $rows;
			$app->triggerEvent('onBeforeDisplayProductListView', [&$view]);
			$list = $view->rows;

		}
		if ($params->get('joomshopping_products_type') == 'label_products')
		{

			$product  = \JSFactory::getModel('label', 'Site\\Productlist');
			$label_ids = (array) $params->get('label_id');

			if ($label_ids)
			{
				$filters['labels'] = $label_ids;
			}

			$categories = $params->get('catids', []);
			if(is_array($categories) && count($categories) > 0){
				$categories = array_map('intval', $categories);
			}

			$filters['categorys'] = $categories;

			$rows = $product->getLoadProducts($filters, null, 'DESC', 0, $count);

			$app->triggerEvent('onBeforeDisplayProductList', [&$rows]);
			$view       = new \stdClass();
			$view->rows = $rows;
			$app->triggerEvent('onBeforeDisplayProductListView', [&$view, &$product]);
			$list = $view->rows;

		}

		return $list;
	}
}