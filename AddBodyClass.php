<?php
/*
    Copyright 2012 Povilas Kanapickas <tir5c3@yahoo.co.uk>
    Copyright 2016 Will Stott <willstott101@gmail.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

$wgExtensionCredits['parserhook'][] = array(
    'path'           => __FILE__,
    'name'           => 'AddBodyClass',
    'author'         => 'Povilas Kanapickas & Will Stott',
    'descriptionmsg' => 'addbodyclass_desc',
    'url'            => 'https://github.com/p12tic/AddBodyClass',
    'version'        => '1.2',
);

$wgExtensionMessagesFiles['AddBodyClassMagic'] = dirname( __FILE__ ) . '/' . 'AddBodyClass.i18n.magic.php';
$wgExtensionMessagesFiles['AddBodyClass'] = dirname( __FILE__ ) . '/' . 'AddBodyClass.i18n.php';

$wgHooks['ParserFirstCallInit'][] = 'AddBodyClass::setup';
$wgHooks['OutputPageBodyAttributes'][] = 'AddBodyClass::add_attrs';
$wgHooks['OutputPageBeforeHTML'][] = 'AddBodyClass::on_output_before_html';

$wgCategoriesAsBodyClasses = true;

class AddBodyClass {

    static protected $classes = '';

    static function setup(&$parser)
    {
        $parser->setFunctionHook('addbodyclass', 'AddBodyClass::on_parse');

        return true;
    }

    static function on_parse(&$parser, $param1 = '')
    {
        $output = '';
        if ($param1 !== '') {
            /* if parser cache is used, the page is parsed only once, so we
               need to store our data in the cached text */
            $output = 'ADD_BODY_CLASS_BEGIN '
                        . htmlspecialchars($param1)
                        . ' ADD_BODY_CLASS_END';
        }
        return $output;
    }

    static function on_output_before_html(&$out, &$text)
    {
        if (($found = strpos($text, 'ADD_BODY_CLASS_BEGIN')) !== false) {
            if (preg_match_all("/ADD_BODY_CLASS_BEGIN (.*?) ADD_BODY_CLASS_END/",
                               $text, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $m) {
                    self::$classes .= ' '.$m[1];
                    $text = str_replace($m[0], "", $text);
                }
            }
        }
        return true;
    }

    static function add_attrs($out, $sk, &$bodyAttrs)
    {
        global $wgCategoriesAsBodyClasses;

        if (self::$classes !== '') {
            $bodyAttrs['class'] .= self::$classes;
        }

        if ($wgCategoriesAsBodyClasses) {
            foreach ($out->getCategories() as $categoryName) {
                $safeCategoryName = str_replace(array('.', ' '), '_', $categoryName);
                $bodyAttrs['class'] .= ' cat-' . $safeCategoryName . ' icat-' . strtolower($safeCategoryName);
            }
        }
        return true;
    }

}
