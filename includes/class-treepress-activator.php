<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.blackandwhitedigital.eu/
 * @since      1.0.0
 *
 * @package    Treepress
 * @subpackage Treepress/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Treepress
 * @subpackage Treepress/includes
 * @author     Black and White Digital Ltd <bd.kabiruddin@gmail.com>
 */
class Treepress_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

        /**
         * Save TreePress version.
         * 
         * */
        update_option('treepress_version', TREEPRESS_VERSION);

        /**
         * Save TreePress activatation time.
         * 
         * */
        update_option('treepress_activatation_time', time());

        self::fixv3();
	}



    public static function fixv3(){

        $args = array(
            'post_type' => 'member',
            'posts_per_page' => -1,
            'post_status' => array( 'publish' ),
            'meta_query' => array(
                array(
                    'key'     => 'v3',
                    'compare' => 'NOT EXISTS',
                ),
            )
        );
        $query = new WP_Query( $args );

        global $wpdb;

        $sql = "UPDATE  `wp_term_taxonomy` SET  `taxonomy` =  'family-group' WHERE  `taxonomy` = 'family'";

        $results = $wpdb->get_results($sql);


        foreach ($query->posts as $key => $value) {

            update_post_meta($value->ID, 'v3', time());


            update_post_meta($value->ID, 'Id', $value->ID);
            $names = array( array(
                'name' => $value->post_title,
                'npfx' => '',
                'givn' => '',
                'nick' => '',
                'spfx' => '',
                'surn' => '',
                'nsfx' => '',
            ) );
            $names_ex = get_post_meta($value->ID, 'names') ? get_post_meta($value->ID, 'names') : array();
            foreach ($names as $key => $name) {
                if( ! array_search( $name['name'], array_column( $names_ex, 'name' ) ) ) {
                    add_post_meta($value->ID, 'names', $name);
                }
            }

            $sex = get_post_meta($value->ID, 'gender', true);

            update_post_meta($value->ID, 'sex', $sex);

            $father = get_post_meta($value->ID, 'father', true);
            $mother = get_post_meta($value->ID, 'mother', true);
            $spouse = get_post_meta($value->ID, 'spouse', true);

            if( $father || $mother ) {
                $fam_id = self::getFamcFamiID($father, $mother);

                $famc_ex = get_post_meta($value->ID, 'famc') ? get_post_meta($value->ID, 'famc') : array();
                if( ! array_search( $fam_id, array_column( $famc_ex, 'fam' ) ) ) {
                    add_post_meta($value->ID, 'famc', array('fam' => $fam_id, 'pedi' => ''));
                }

                $chill_ex = get_post_meta($fam_id, 'chil') ? get_post_meta($fam_id, 'chil') : array();
                if( ! in_array( $value->ID, $chill_ex ) ) {
                    add_post_meta($fam_id, 'chil', $value->ID);
                }

            }

            if( $spouse ) {
                $husb = $value->ID;
                $wife = $spouse;
                if( strtolower( $sex ) == 'f' ) {
                    $husb = $spouse;
                    $wife = $value->ID;
                }
                self::getFamcFamiID($husb, $wife);
            }

            $event = get_post_meta($value->ID, 'event', true) ? get_post_meta($value->ID, 'event', true) : array();
            $even = [];
            foreach ($event as $key_et => $et) {
                foreach ($et as $dc => $e) {
                    array_push($even, array(
                        'type'=> strtoupper(str_replace('_', ' ', $key_et)),
                        'date'=> $e['date'],
                        'plac'=> $e['place'],
                    ));
                }
            }

            $even_ex = get_post_meta($value->ID, 'even') ? get_post_meta($value->ID, 'even') : array();
            if(!empty($even)){
                foreach ($even as $key => $ev) {
                    if( ! array_search( $ev['type'], array_column( $even_ex, 'type' ) ) && ! array_search( $ev['date'], array_column( $even_ex, 'date' ) ) && ! array_search( $ev['plac'], array_column( $even_ex, 'plac' ) )) {
                        add_post_meta($value->ID, 'even', $ev);
                    }
                }
            }

            $born = get_post_meta($value->ID, 'born', true);
            if($born){
                if( ! array_search( 'BIRT', array_column( $even_ex, 'type' ) ) && ! array_search( $born, array_column( $even_ex, 'date' ) ) ) {
                    add_post_meta($value->ID, 'even', array(
                        'type'=> 'BIRT',
                        'date'=> $born,
                        'plac'=> '',
                    ));
                }
            }

            $died = get_post_meta($value->ID, 'died', true);
            if($died){
                if( ! array_search( 'DEAT', array_column( $even_ex, 'type' ) ) && ! array_search( $born, array_column( $even_ex, 'date' ) ) ) {
                    add_post_meta($value->ID, 'even', array(
                        'type'=> 'DEAT',
                        'date'=> $died,
                        'plac'=> '',
                    ));
                }
            }
        }
    }



    public static function getFamcFamiID( $father, $mother ) {

        if ( $father && $mother) {
            $args = array(
                'post_type' => 'family',
                'posts_per_page' => 1,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'husb',
                        'value' => $father,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wife',
                        'value' => $mother,
                        'compare' => '=',
                    ),
                ),
            );
        }

        if ( $father && !$mother) {
            $args = array(
                'post_type' => 'family',
                'posts_per_page' => 1,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'husb',
                        'value' => $father,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wife',
                        'compare' => 'NOT EXISTS',
                    ),
                ),
            );
        }

        if ( !$father && $mother) {
            $args = array(
                'post_type' => 'family',
                'posts_per_page' => 1,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'husb',
                        'compare' => 'NOT EXISTS',
                    ),
                    array(
                        'key' => 'wife',
                        'value' => $mother,
                        'compare' => '=',
                    ),
                ),
            );
        }

        $query = new WP_Query( $args );

        $family_id = current($query->posts) ? current($query->posts)->ID : NULL;

        if( $family_id ) {
            return $family_id;
        }

        $my_post = array(
            'post_title' => '',
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'family',
        );

        $family_id = wp_insert_post($my_post);

        $post_update = array(
            'ID' => $family_id,
            'post_title' => $family_id
        );

        wp_update_post( $post_update );

        update_post_meta($family_id, 'Id', $family_id);

        if ( $father) {
            update_post_meta($family_id, 'husb', $father);

            $fams_ex_father = get_post_meta($father, 'fams') ? get_post_meta($father, 'fams') : array();
            if( ! array_search( $family_id, array_column( $fams_ex_father, 'fam' ) ) ) {
                add_post_meta($father, 'fams', array('fam'=> $family_id));
            }
        }

        if ( $mother) {
            update_post_meta($family_id, 'wife', $mother);
            $fams_ex_mother = get_post_meta($mother, 'fams') ? get_post_meta($mother, 'fams') : array();
            if( ! array_search( $family_id, array_column( $fams_ex_mother, 'fam' ) ) ) {
                add_post_meta($mother, 'fams', array('fam'=> $family_id));
            }
        }

        return $family_id;

    }












}
