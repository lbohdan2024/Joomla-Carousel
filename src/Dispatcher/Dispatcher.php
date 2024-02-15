<?php
/**
 * @package     WT JShopping Swiper carousel
 * @copyright   Copyright (C) 2022-2023 Sergey Tolkachyov. All rights reserved.
 * @author      Sergey Tolkachyov - https://web-tolk.ru
 * @link 		https://web-tolk.ru
 * @version 	1.1.1
 * @license     GNU General Public License version 3 or later
 */

namespace Joomla\Module\Wtjshoppingswipercarousel\Site\Dispatcher;

\defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Module\Wtjshoppingswipercarousel\Site\Helper\WtjshoppingswipercarouselHelper;

/**
 * Dispatcher class for mod_wtyandexmapitems
 *
 * @since  1.0.0
 */
class Dispatcher extends AbstractModuleDispatcher
{
	/**
	 * Returns the layout data.
	 *
	 * @return  array
	 *
	 * @since   1.0.0
	 */
	protected function getLayoutData()
	{
		if (!PluginHelper::isEnabled('system', 'wtjswiper'))
		{
			$this->getApplication()->enqueueMessage('<strong>WT Jshopping Swiper carousel module:</strong> Please, install or enable the plugin <code>System - WT JSwiper</code>', 'error');

			return false;
		}

		$data = parent::getLayoutData();
		$moduleclass_sfx = ($data['params'])->get('moduleclass_sfx');
		if(!empty($moduleclass_sfx)){
			$data['moduleclass_sfx'] =	htmlspecialchars($moduleclass_sfx, ENT_COMPAT, 'UTF-8');
		} else {
			$data['moduleclass_sfx'] = '';
		}

		$module_helper = new WtjshoppingswipercarouselHelper;

		$module_helper->getSwiperParams($data, $this->getApplication());

		$data['list'] = $module_helper->getList($data['params'], $this->getApplication());
		$data['jshopConfig'] = $module_helper->getJshopConfig();


		/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
		$wa = $this->getApplication()->getDocument()->getWebAssetManager();
		$wa->useScript('swiper-bundle')
			->useStyle('swiper-bundle');

		return $data;
	}
}