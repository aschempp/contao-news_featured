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
 * Config
 */
$GLOBALS['TL_DCA']['tl_news']['config']['onload_callback'][] = array('NewsFeatured', 'onloadNewsDataContainer');


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_news']['fields']['featuredPages'] = array
(
    'label'         => &$GLOBALS['TL_LANG']['tl_news']['featuredPages'],
    'exclude'       => true,
    'inputType'     => 'checkbox',
    'eval'          => array('multiple'=>true, 'tl_class'=>'w50 clr'),
    'sql'           => "blob NULL",
);
