<?php
/*
Plugin Name: Rel Publisher
Plugin URI: https://github.com/AgencyPMG/Rel-Publisher
Description: A simple plugin that adds a <link rel="publisher" /> to your <head> section -- userful for Google+
Version: 1.0
Text Domain: rel-publisher
Domain Path: /lang
Author: Christopher Davis
Author URI: http://pmg.co/people/chris
License: GPL2

    Copyright 2012 Performance Media Group <seo@pmg.co>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

PMG_Rel_Publisher::init();

class PMG_Rel_Publisher
{
    /**
     * Our option name
     * 
     * @since   1.0
     */
    const OPTION = 'pmg_rel_publisher';

    /**
     * Page on which this option will reside.
     *
     * @since   1.0
     */
    const PAGE = 'general';

    /**
     * Secton for this option.
     *
     * @since   1.0
     */
    const SECT = 'rel_publisher';

    /**
     * Adds actions and such.
     *
     * @since   1.0
     * @access  public
     * @uses    add_action
     * @return  null
     */
    public static function init()
    {
        add_action(
            'admin_init',
            array(__CLASS__, 'settings')
        );

        add_action(
            'wp_head',
            array(__CLASS__, 'head'),
            3
        );
    }

    /**
     * Hooked into `admin_init`.  Registers the settings and adds settings
     * fields and sections.
     *
     * @since   1.0
     * @access  public
     * @uses    register_setting
     * @uses    add_settings_section
     * @uses    add_settings_field
     * @return  null
     */
    public static function settings()
    {
        register_setting(
            self::PAGE,
            self::OPTION,
            array(__CLASS__, 'validate')
        );

        add_settings_section(
            self::SECT,
            __('Rel Publisher', 'rel-publisher'),
            '__return_false',
            self::PAGE
        );

        add_settings_field(
            'rel-publisher-gplus',
            __('Google+ URL', 'rel-publisher'),
            array(__CLASS__, 'field'),
            self::PAGE,
            self::SECT,
            array('label_for' => self::OPTION)
        );
    }

    /**
     * Callback function for the settings field.
     *
     * @since   1.0
     * @access  public
     * @uses    get_option
     * @uses    esc_attr
     * @return  null
     */
    public static function field()
    {
        printf(
            '<input type="text" class="regular-text" id="%1$s" name="%1$s" value="%2$s" />',
            esc_attr(self::OPTION),
            esc_attr(get_option(self::OPTION, ''))
        );
    }

    /**
     * Settings validation callback.  Escapes the URL if it happens to be there.
     *
     * @since   1.0
     * @access  public
     * @uses    esc_url_raw
     * @return  string Empty or the escaped URL.
     */
    public static function validate($dirty)
    {
        $clean = '';
        if($dirty)
            $clean = esc_url_raw($dirty);

        return $clean;
    }

    /**
     * Hooked into `wp_head`.  Spits out the rel="publish" link if we have a
     * google+ url.
     *
     * @since   1.0
     * @access  public
     * @uses    get_option
     * @return  null
     */
    public static function head()
    {
        $gplus = get_option(self::OPTION);

        if(!$gplus)
            return;

        printf("<link rel='publisher' href='%s' />\n", esc_url($gplus));
    }
} // end class
