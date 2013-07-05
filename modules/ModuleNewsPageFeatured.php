<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  terminal42 gmbh 2013
 * @author     Andreas Schempp <andreas.schempp@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;



class ModuleNewsPageFeatured extends \ModuleNewsList
{

    /**
	 * Generate the module
	 */
	protected function compile()
	{
		$offset = intval($this->skipFirst);
		$limit = null;
		$this->Template->articles = array();

		// Maximum number of items
		if ($this->numberOfItems > 0)
		{
			$limit = $this->numberOfItems;
		}

		// Get the total number of items
		$intTotal = static::countFeaturedNews($this->news_archives);

		if ($intTotal < 1)
		{
			$this->Template = new \FrontendTemplate('mod_newsarchive_empty');
			$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['emptyList'];
			return;
		}

		$total = $intTotal - $offset;

		// Split the results
		if ($this->perPage > 0 && (!isset($limit) || $this->numberOfItems > $this->perPage))
		{
			// Adjust the overall limit
			if (isset($limit))
			{
				$total = min($limit, $total);
			}

			// Get the current page
			$id = 'page_n' . $this->id;
			$page = \Input::get($id) ?: 1;

			// Do not index or cache the page if the page number is outside the range
			if ($page < 1 || $page > max(ceil($total/$this->perPage), 1))
			{
				global $objPage;
				$objPage->noSearch = 1;
				$objPage->cache = 0;

				// Send a 404 header
				header('HTTP/1.1 404 Not Found');
				return;
			}

			// Set limit and offset
			$limit = $this->perPage;
			$offset += (max($page, 1) - 1) * $this->perPage;

			// Overall limit
			if ($offset + $limit > $total)
			{
				$limit = $total - $offset;
			}

			// Add the pagination menu
			$objPagination = new \Pagination($total, $this->perPage, $GLOBALS['TL_CONFIG']['maxPaginationLinks'], $id);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}

		// Get the items
		if (isset($limit))
		{
			$objArticles = static::findFeaturedNews($this->news_archives, $limit, $offset);
		}
		else
		{
			$objArticles = static::findFeaturedNews($this->news_archives, 0, $offset);
		}

		// No items found
		if ($objArticles === null)
		{
			$this->Template = new \FrontendTemplate('mod_newsarchive_empty');
			$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['emptyList'];
		}
		else
		{
			$this->Template->articles = $this->parseArticles($objArticles);
		}

		$this->Template->archives = $this->news_archives;
	}


	public static function countFeaturedNews($arrPids)
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return 0;
		}

        global $objPage;

		$t = \NewsModel::getTable();
		$arrColumns = array("$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");
        $arrColumns[] = "($t.featuredPages LIKE '%:\"" . $objPage->id . "\";%' OR $t.featuredPages LIKE '%i:" . $objPage->id . ";%')";

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return \NewsModel::countBy($arrColumns);
	}


	public static function findFeaturedNews($arrPids, $intLimit=0, $intOffset=0)
	{
		if (!is_array($arrPids) || empty($arrPids))
		{
			return null;
		}

		global $objPage;

		$t = \NewsModel::getTable();
		$arrColumns = array("$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")");
        $arrColumns[] = "($t.featuredPages LIKE '%:\"" . $objPage->id . "\";%' OR $t.featuredPages LIKE '%i:" . $objPage->id . ";%')";

		// Never return unpublished elements in the back end, so they don't end up in the RSS feed
		if (!BE_USER_LOGGED_IN || TL_MODE == 'BE')
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		$arrOptions['order']  = "$t.date DESC";
		$arrOptions['limit']  = $intLimit;
		$arrOptions['offset'] = $intOffset;

		return \NewsModel::findBy($arrColumns, null, $arrOptions);
	}
}
