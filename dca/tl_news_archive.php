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
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_news_archive']['palettes']['default'] .= ';{featured_legend},featuredPages';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_news_archive']['fields']['featuredPages'] = array
(
    'label'         => &$GLOBALS['TL_LANG']['tl_news_archive']['featuredPages'],
    'exclude'       => true,
    'inputType'     => 'pageTree',
    'foreignKey'    => 'tl_page.title',
    'eval'          => array('fieldType'=>'checkbox', 'multiple'=>true, 'tl_class'=>'clr'),
    'sql'           => "blob NULL",
    'relation'      => array('type'=>'hasMany', 'load'=>'lazy')
);
