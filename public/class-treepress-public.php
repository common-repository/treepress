<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link  https://www.blackandwhitedigital.eu/
 * @since 1.0.0
 *
 * @package    Treepress
 * @subpackage Treepress/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Treepress
 * @subpackage Treepress/public
 * @author     Black and White Digital Ltd <bd.kabiruddin@gmail.com>
 */
class Treepress_Public
{
    /**
     * The ID of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @since 1.0.0
     * @param string $plugin_name The name of the plugin.
     * @param string $version     The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Treepress_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Treepress_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'css/treepress-public.css',
            array(),
            $this->version,
            'all'
        );
    }
    
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Treepress_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Treepress_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script(
            $this->plugin_name . '-raphael',
            plugin_dir_url( __FILE__ ) . 'js/raphael.js',
            array( 'jquery' ),
            $this->version,
            false
        );
        wp_enqueue_script(
            $this->plugin_name . '-public',
            plugin_dir_url( __FILE__ ) . 'js/treepress-public.js',
            array( 'jquery' ),
            $this->version,
            false
        );
        wp_enqueue_script(
            $this->plugin_name . '-panzoom',
            plugin_dir_url( __FILE__ ) . 'js/panzoom.min.js',
            array( 'jquery' ),
            $this->version,
            false
        );
    }
    
    /**
     * Render the tree.
     *
     * @param  string $root root.
     * @param  string $family family.
     * @param  array  $meta meta.
     * @param  string $html html.
     *
     * @return string
     */
    public function treepress_family_tree_svg(
        $root = '',
        $family = '',
        $meta = array(),
        $html = ''
    )
    {
        if ( !$root ) {
            $root = $this->get_root_by_family( $family );
        }
        if ( !$root ) {
            return __( 'Root Id is Required.', 'treepress' );
        }
        $html .= "<script type='text/javascript'>";
        $tree_data_js = "\n var tree_txt = new Array(\n";
        $the_family_all = (array) $this->get_tree( $family );
        foreach ( $the_family_all as $key => $value ) {
            $the_family_all[$value->ID] = $value;
            unset( $the_family_all[$key] );
        }
        $the_family = array_chunk( $the_family_all, 200, true )[0];
        $first = true;
        foreach ( $the_family as $node ) {
            
            if ( !$first ) {
                $tree_data_js .= ',' . "\n";
            } else {
                $first = false;
            }
            
            $str = '"EsscottiFTID=' . $node->ID . '",' . "\n";
            $str .= '"Name=' . addslashes( $node->name ) . '",' . "\n";
            
            if ( strtolower( $node->gender ) == 'm' ) {
                $str .= '"Male",' . "\n";
            } elseif ( strtolower( $node->gender ) == 'f' ) {
                $str .= '"Female",' . "\n";
            }
            
            $str .= '"Birthday=' . $node->born . '",' . "\n";
            if ( !empty($node->died) && '-' != $node->died ) {
                $str .= '"Deathday=' . $node->died . '",' . "\n";
            }
            if ( isset( $node->partners ) && is_array( $node->partners ) && !empty($node->partners) ) {
                foreach ( $node->partners as $partner ) {
                    if ( is_numeric( $partner ) ) {
                        if ( isset( $the_family[$partner] ) ) {
                            $str .= '"Spouse=' . $the_family[$partner]->ID . '",' . "\n";
                        }
                    }
                }
            }
            $str .= '"Toolbar=toolbar' . $node->ID . '",' . "\n";
            $str .= '"Thumbnaildiv=thumbnail' . $node->ID . '",' . "\n";
            $mother_id = null;
            $father_id = null;
            if ( $node->mother && isset( $the_family[$node->mother] ) ) {
                $mother_id = $the_family[$node->mother]->ID;
            }
            if ( $node->father && isset( $the_family[$node->father] ) ) {
                $father_id = $the_family[$node->father]->ID;
            }
            $str .= '"Parent=' . $mother_id . '",' . "\n";
            $str .= '"Parent=' . $father_id . '"';
            $tree_data_js .= $str;
        }
        $tree_data_js .= "\n" . ');' . "\n\n";
        $html .= $tree_data_js;
        $html .= 'BOX_LINE_Y_SIZE = "' . get_option( 'generationheight', '80' ) . '";' . "\n";
        $html .= 'canvasbgcol = "' . get_option( 'canvasbgcol', '#f1eef1' ) . '";' . "\n";
        $html .= 'nodeoutlinecol = "' . get_option( 'nodeoutlinecol', '#adbbbd' ) . '";' . "\n";
        $html .= 'nodefillcol   = "' . get_option( 'nodefillcol', '#ced6d7' ) . '";' . "\n";
        $html .= 'nodefillopacity = ' . get_option( 'nodefillopacity', '1' ) . ';' . "\n";
        $html .= 'nodetextcolour = "' . get_option( 'nodetextcolour', '#555555' ) . '";' . "\n";
        $html .= 'setOneNamePerLine(' . get_option( 'bOneNamePerLine', 'false' ) . ');' . "\n";
        $html .= 'setOnlyFirstName(' . get_option( 'bOnlyFirstName', 'true' ) . ');' . "\n";
        $html .= 'setBirthAndDeathDates(' . get_option( 'bBirthAndDeathDates', 'true' ) . ');' . "\n";
        $html .= 'setBirthAndDeathDatesOnlyYear(' . get_option( 'bBirthAndDeathDatesOnlyYear', 'true' ) . ');' . "\n";
        $html .= 'setBirthDatePrefix("' . get_option( 'bBirthDatePrefix', 'b' ) . '");' . "\n";
        $html .= 'setDeathDatePrefix("' . get_option( 'bDeathDatePrefix', 'd' ) . '");' . "\n";
        $html .= 'setConcealLivingDates(' . get_option( 'bConcealLivingDates', 'true' ) . ');' . "\n";
        $html .= 'setShowSpouse(' . get_option( 'bShowSpouse', 'true' ) . ');' . "\n";
        $html .= 'setShowOneSpouse(' . get_option( 'bShowOneSpouse', 'false' ) . ');' . "\n";
        $html .= 'setVerticalSpouses(' . get_option( 'bVerticalSpouses', 'false' ) . ');' . "\n";
        $html .= 'setMaidenName(' . get_option( 'bMaidenName', 'true' ) . ');' . "\n";
        $html .= 'setShowGender(' . get_option( 'bShowGender', 'true' ) . ');' . "\n";
        $html .= 'setDiagonalConnections(' . get_option( 'bDiagonalConnections', 'false' ) . ');' . "\n";
        $html .= 'setRefocusOnClick(' . get_option( 'bRefocusOnClick', 'true' ) . ');' . "\n";
        $html .= 'setShowToolbar(' . get_option( 'bShowToolbar', 'true' ) . ');' . "\n";
        $html .= 'setNodeRounding(' . get_option( 'nodecornerradius', 5 ) . ');' . "\n";
        
        if ( get_option( 'bShowToolbar', 'true' ) == 'true' ) {
            $html .= 'setToolbarYPad(20);' . "\n";
        } else {
            $html .= 'setToolbarYPad(0);' . "\n";
        }
        
        $html .= 'setToolbarPos(true, 5, 5);' . "\n";
        $html .= 'setMinBoxWidth(' . get_option( 'nodeminwidth', '0' ) . ');' . "\n\n";
        $html .= 'jQuery(document).ready(function($){' . "\n";
        $html .= '  familytreemain();' . "\n\n";
        $html .= '  var scene = document.getElementById(\'tree-container\');' . "\n";
        if ( !$this->ae_detect_ie() ) {
            $html .= '  panzoom(scene, { onTouch: function(e) { return false; } });' . "\n";
        }
        $html .= '});' . "\n";
        $html .= '</script>' . "\n";
        $html .= '<input type="hidden" size="30" name="focusperson" id="focusperson" value="' . $root . '">' . "\n";
        $html .= '<div id="borderBox" style="background-color:' . get_option( 'canvasbgcol', '#f1eef1' ) . '">' . "\n";
        $html .= '<div id="dragableElement">';
        $html .= '<div id="tree-container">' . "\n";
        $html .= '<div id="toolbar-container">' . "\n";
        foreach ( $the_family as $node ) {
            $html .= $this->get_toolbar_div( $node );
        }
        $html .= '</div>' . "\n";
        $html .= '<div id="thumbnail-container">' . "\n";
        foreach ( $the_family as $node ) {
            $html .= $this->get_thumbnail_div( $node );
        }
        $html .= '</div>' . "\n";
        $html .= '<div id="familytree" style="background-color:' . get_option( 'canvasbgcol', '#f1eef1' ) . '"></div>' . "\n";
        $html .= '<img name="hoverimage" id="hoverimage" style="visibility:hidden;" >' . "\n";
        $html .= '</div>' . "\n";
        $html .= '</div>' . "\n";
        $html .= '</div>' . "\n";
        $html .= '
		<!--[if IE]>
		<style>
			#dragableElement{
				position:initial !important;
				transform: none !important;
			}
			#borderBox {
				overflow: scroll !important;
			}
		</style>
		<![endif]-->
		' . "\n";
        $showcreditlink_x = get_option( 'showcreditlink', 'true' );
        
        if ( tre_fs()->is_not_paying() ) {
            $showcreditlink_x = 'true';
        } else {
            $showcreditlink_x = false;
        }
        
        if ( 'true' == $showcreditlink_x ) {
            $html .= '<p style="text-align:left"><small>Powered by <a target="_blank" href="http://www.treepress.net">TreePress</a></small></p>' . "\n";
        }
        return $html;
    }
    
    /**
     * Function for `get_tree`.
     *
     * @param  string $family family.
     * @return mixwd
     */
    public function get_tree( $family )
    {
        $args = array(
            'post_type'      => 'member',
            'posts_per_page' => -1,
            'tax_query'      => array( array(
            'taxonomy' => 'family-group',
            'field'    => 'id',
            'terms'    => $family,
        ) ),
        );
        $query = new WP_Query( $args );
        $data = $query->posts;
        foreach ( $data as $key => $value ) {
            $ref_id = get_post_meta( $value->ID, 'Id', true );
            $value->post_id = $ref_id;
            $name = ( get_post_meta( $value->ID, 'names' ) ? get_post_meta( $value->ID, 'names' )[0] : array(
                'name' => '',
                'npfx' => '',
                'givn' => '',
                'nick' => '',
                'spfx' => '',
                'surn' => '',
                'nsfx' => '',
            ) );
            $value->name = $name['name'];
            $even = ( get_post_meta( $value->ID, 'even' ) ? get_post_meta( $value->ID, 'even' ) : array() );
            $birt = array(
                'date' => '',
            );
            $found_birt = false;
            foreach ( $even as $key => $event ) {
                
                if ( 'BIRT' === $event['type'] && false === $found_birt ) {
                    $birt = $event;
                    $found_birt = true;
                }
            
            }
            $value->born = $birt['date'];
            $deat = array(
                'date' => '',
            );
            $found_deat = false;
            foreach ( $even as $key => $event ) {
                
                if ( 'DEAT' === $event['type'] && false === $found_deat ) {
                    $deat = $event;
                    $found_deat = true;
                }
            
            }
            $value->died = $deat['date'];
            $value->gender = get_post_meta( $value->ID, 'sex', true );
            $fams = ( get_post_meta( $value->ID, 'fams' ) ? get_post_meta( $value->ID, 'fams' ) : array() );
            $partners = array();
            foreach ( $fams as $key => $fam ) {
                $fam_id = null;
                
                if ( isset( $fam['fam'] ) && $fam['fam'] ) {
                    $args = array(
                        'post_type'      => 'family',
                        'posts_per_page' => 1,
                        'meta_query'     => array( array(
                        'key'     => 'Id',
                        'value'   => $fam['fam'],
                        'compare' => '=',
                    ) ),
                    );
                    $query = new WP_Query( $args );
                    if ( !empty($query->posts) ) {
                        $fam_id = current( $query->posts )->ID;
                    }
                }
                
                $husb = null;
                $wife = null;
                
                if ( $fam_id ) {
                    $husb = ( get_post_meta( $fam_id, 'husb', true ) ? get_post_meta( $fam_id, 'husb', true ) : null );
                    $wife = ( get_post_meta( $fam_id, 'wife', true ) ? get_post_meta( $fam_id, 'wife', true ) : null );
                }
                
                $spouse = null;
                if ( $ref_id == $husb ) {
                    $spouse = $wife;
                }
                if ( $ref_id == $wife ) {
                    $spouse = $husb;
                }
                if ( $spouse ) {
                    $partners[] = $this->get_member_id( $spouse );
                }
            }
            $value->partners = array_filter( $partners );
            $famc = ( get_post_meta( $value->ID, 'famc' ) ? get_post_meta( $value->ID, 'famc' ) : array( array() ) );
            foreach ( $famc as $famc_key => $fac ) {
                $args = array(
                    'post_type'      => 'family',
                    'posts_per_page' => '1',
                    'post_status'    => array( 'publish' ),
                    'meta_query'     => array( array(
                    'key'     => 'Id',
                    'value'   => ( isset( $fac['fam'] ) ? $fac['fam'] : '' ),
                    'compare' => '=',
                ) ),
                );
                $query = new WP_Query( $args );
                if ( !$query->posts ) {
                    unset( $famc[$famc_key] );
                }
            }
            $famc = current( $famc );
            $fam_id = null;
            
            if ( isset( $famc['fam'] ) ) {
                $args = array(
                    'post_type'      => 'family',
                    'posts_per_page' => 1,
                    'meta_query'     => array( array(
                    'key'     => 'Id',
                    'value'   => $famc['fam'],
                    'compare' => '=',
                ) ),
                );
                $query = new WP_Query( $args );
                if ( !empty($query->posts) ) {
                    $fam_id = current( $query->posts )->ID;
                }
            }
            
            $father = null;
            $mother = null;
            
            if ( $fam_id ) {
                $father = ( get_post_meta( $fam_id, 'husb', true ) ? get_post_meta( $fam_id, 'husb', true ) : null );
                $mother = ( get_post_meta( $fam_id, 'wife', true ) ? get_post_meta( $fam_id, 'wife', true ) : null );
            }
            
            $value->father = $this->get_member_id( $father );
            $value->mother = $this->get_member_id( $mother );
            
            if ( function_exists( 'get_post_thumbnail_id' ) ) {
                $thumbid = get_post_thumbnail_id( $value->ID );
                $thumbsrc = wp_get_attachment_image_src( $thumbid, 'thumbnail' );
                
                if ( isset( $thumbsrc[0] ) ) {
                    $value->thumbsrc = $thumbsrc[0];
                } else {
                    $value->thumbsrc = '';
                }
                
                $value->thumbhtml = get_the_post_thumbnail( $value->ID, 'thumbnail' );
            }
        
        }
        return $data;
    }
    
    /**
     * Function for `get_toolbar_div`.
     *
     * @param  object $node node.
     * @param  string $html html.
     * @return string
     */
    public function get_toolbar_div( $node, $html = '' )
    {
        $terms = wp_get_post_terms( $node->ID, 'family-group' );
        $ftlink = '';
        
        if ( !empty($terms) && !is_wp_error( $terms ) ) {
            $term_id = $terms[0]->term_id;
            $ftlink = get_term_meta( $term_id, 'family_tree_link', true );
        }
        
        
        if ( strpos( $ftlink, '?' ) === false ) {
            $ftlink = $ftlink . '?ancestor=' . $node->ID;
        } else {
            $ftlink = $ftlink . '&ancestor=' . $node->ID;
        }
        
        $cslink = get_post_meta( $node->ID, 'cslink', true );
        
        if ( get_option( 'bShowToolbar', 'true' ) == 'true' ) {
            $html .= '<div class="toolbar" id="toolbar' . $node->ID . '">';
            if ( get_option( 'treepress_toolbar_blogpage', 'true' ) == 'true' ) {
                $html .= '
				<a class="toolbar-blogpage" href="' . get_permalink( $node->ID ) . '" title="' . __( 'View information about', 'treepress' ) . ' ' . htmlspecialchars( $node->name ) . '">
					<img border="0" class="toolbar-blogpage" src="' . plugin_dir_url( __FILE__ ) . 'imgs/open-book.png">
				</a>';
            }
            if ( get_option( 'treepress_toolbar_treenav', 'true' ) == 'true' ) {
                $html .= '
				<a class="toolbar-treenav" href="' . $ftlink . '" title="' . __( 'View the family of', 'treepress' ) . ' ' . htmlspecialchars( $node->name ) . '">
					<img width="13" border="0" class="toolbar-treenav" src="' . plugin_dir_url( __FILE__ ) . 'imgs/tree.gif">
				</a>';
            }
            if ( $cslink ) {
                $html .= '
				<a class="toolbar-treenav" href="' . $cslink . '" title="' . __( 'View the family of', 'treepress' ) . ' ' . htmlspecialchars( $node->name ) . '">
					<img width="13" border="0" class="toolbar-treenav" src="' . plugin_dir_url( __FILE__ ) . 'imgs/cslink.jpg">
				</a>';
            }
            $html .= '</div>';
        }
        
        return $html;
    }
    
    /**
     * Function for `get_thumbnail_div`.
     *
     * @param  object $node node.
     * @param  string $html html.
     * @return string
     */
    public function get_thumbnail_div( $node, $html = '' )
    {
        $html .= '<div class="treepress_thumbnail" id="thumbnail' . $node->ID . '">';
        
        if ( !empty($node->thumbsrc) ) {
            $html .= '<img src="' . $node->thumbsrc . '">';
        } else {
            $html .= '<img style="width:50px;" src="' . plugin_dir_url( __FILE__ ) . 'imgs/no-avatar.png">';
        }
        
        $html .= '</div>';
        return $html;
    }
    
    /**
     * Function for `create_tree_by_root_alt`.
     *
     * @param  string $root root.
     * @return array
     */
    public function create_tree_by_root_alt( $root )
    {
        $member_id = get_post_meta( $root, 'Id', true );
        $tree = array();
        $sex = get_post_meta( $root, 'sex', true );
        $famc = ( get_post_meta( $root, 'famc' ) ? get_post_meta( $root, 'famc' ) : array() );
        $tree['ind'] = $root;
        $tree['title'] = get_the_title( $root );
        $tree['sex'] = $sex;
        foreach ( $famc as $key => $fam ) {
            $family_id = $this->get_family_id( $fam['fam'] );
            $husb = get_post_meta( $family_id, 'husb', true );
            $wife = get_post_meta( $family_id, 'wife', true );
            if ( $husb ) {
                $tree['fam'][$key]['father'] = $this->create_tree_by_root_alt( $this->get_member_id( $husb ) );
            }
            if ( $wife ) {
                $tree['fam'][$key]['mother'] = $this->create_tree_by_root_alt( $this->get_member_id( $wife ) );
            }
        }
        return $tree;
    }
    
    /**
     * Function for `create_tree_by_root`.
     *
     * @param  string $root root.
     * @return array
     */
    public function create_tree_by_root( $root )
    {
        $member_id = get_post_meta( $root, 'Id', true );
        $tree = array();
        $sex = get_post_meta( $root, 'sex', true );
        $fams = ( get_post_meta( $root, 'fams' ) ? get_post_meta( $root, 'fams' ) : array() );
        $tree['ind'] = $root;
        $tree['title'] = get_the_title( $root );
        $tree['sex'] = $sex;
        $sd = 0;
        foreach ( $fams as $key => $fam ) {
            $family_id = $this->get_family_id( $fam['fam'] );
            $spouse = '';
            $husb = get_post_meta( $family_id, 'husb', true );
            $wife = get_post_meta( $family_id, 'wife', true );
            $chil = ( get_post_meta( $family_id, 'chil' ) ? get_post_meta( $family_id, 'chil' ) : array() );
            $chils = array();
            foreach ( $chil as $keyx => $value ) {
                $chils[] = $this->create_tree_by_root( $this->get_member_id( $value ) );
            }
            if ( $husb == $member_id ) {
                $spouse = $wife;
            }
            if ( $wife == $member_id ) {
                $spouse = $husb;
            }
            if ( $spouse ) {
                $tree['fam'][$sd]['spouse'] = $this->get_member_id( $spouse );
            }
            if ( !empty($chils) ) {
                $tree['fam'][$sd]['chil'] = $chils;
            }
            if ( $spouse || !empty($chils) ) {
                $sd++;
            }
        }
        return $tree;
    }
    
    /**
     * Function for `toolbarlink`
     *
     * @param  string $post post.
     * @param  array  $meta meta.
     * @return string
     */
    public function toolbarlink( $post, $meta )
    {
        if ( !$post ) {
            return '';
        }
        if ( !get_post( $post ) ) {
            return '';
        }
        $post = get_post( $post );
        $cslink = get_post_meta( $post->ID, 'cslink', true );
        $permalink = get_permalink( $post->ID );
        $terms = wp_get_post_terms( $post->ID, 'family-group' );
        $single_page_link = false;
        $tree_nav_link = false;
        if ( isset( $meta['box'] ) && isset( $meta['box']['single_page_link'] ) && 'on' == $meta['box']['single_page_link'] ) {
            $single_page_link = true;
        }
        if ( isset( $meta['box'] ) && isset( $meta['box']['tree_nav_link'] ) && 'on' == $meta['box']['tree_nav_link'] ) {
            $tree_nav_link = true;
        }
        
        if ( $terms ) {
            $term_id = $terms[0]->term_id;
            $ftlink = get_term_meta( $term_id, 'family_tree_link', true );
        } else {
            $ftlink = '';
        }
        
        
        if ( strpos( $ftlink, '?' ) === false ) {
            $ftlink = $ftlink . '?ancestor=' . $post->ID;
        } else {
            $ftlink = $ftlink . '&ancestor=' . $post->ID;
        }
        
        ?>

		<div class="toolbar" id="toolbar<?php 
        echo  esc_attr( $post->ID ) ;
        ?>">
			<div class="toolbar-inner">
			<?php 
        
        if ( $single_page_link ) {
            ?>
				<a class="toolbar-blogpage" href="<?php 
            echo  esc_attr( $permalink ) ;
            ?>" title="<?php 
            echo  esc_attr__( 'View information about', 'treepress' ) ;
            ?> 
					<?php 
            echo  esc_html( htmlspecialchars( $post->name ) ) ;
            ?>">
					<img border="0" class="toolbar-blogpage" src="<?php 
            echo  esc_attr( plugin_dir_url( __FILE__ ) ) ;
            ?>imgs/open-book.png">
				</a>
			<?php 
        }
        
        ?>
			<?php 
        
        if ( $tree_nav_link ) {
            ?>
				<a class="toolbar-treenav" href="<?php 
            echo  esc_attr( $ftlink ) ;
            ?>" title="<?php 
            echo  esc_attr__( 'View the family of', 'treepress' ) ;
            ?> 
					<?php 
            echo  esc_html( htmlspecialchars( $post->name ) ) ;
            ?>">
					<img width="13" border="0" class="toolbar-treenav" src="<?php 
            echo  esc_attr( plugin_dir_url( __FILE__ ) ) ;
            ?>imgs/tree.gif">
				</a>
			<?php 
        }
        
        ?>
			<?php 
        
        if ( $cslink ) {
            ?>
				<a class="toolbar-treenav" href="<?php 
            echo  esc_attr( $cslink ) ;
            ?>" title="<?php 
            echo  esc_attr__( 'View the family of', 'treepress' ) ;
            ?> 
					<?php 
            echo  esc_html( htmlspecialchars( $post->name ) ) ;
            ?>">
					<img width="13" border="0" class="toolbar-treenav" src="<?php 
            echo  esc_attr( plugin_dir_url( __FILE__ ) ) ;
            ?>imgs/cslink.jpg">
				</a>
			<?php 
        }
        
        ?>
			</div>
		</div>
		<?php 
    }
    
    /**
     * Function for `ae_detect_ie`.
     *
     * @return bool
     */
    public function ae_detect_ie()
    {
        
        if ( preg_match( '~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT'] ) || strpos( $_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0' ) !== false ) {
            return true;
        } else {
            return false;
        }
    
    }
    
    /**
     * Function for `get_member_id`.
     *
     * @param  string $ref_id ref_id.
     *
     * @return string
     */
    public function get_member_id( $ref_id )
    {
        if ( !$ref_id ) {
            return null;
        }
        $args = array(
            'post_type'      => 'member',
            'posts_per_page' => 1,
            'meta_query'     => array( array(
            'key'     => 'Id',
            'value'   => $ref_id,
            'compare' => '=',
        ) ),
        );
        $query = new WP_Query( $args );
        
        if ( !empty($query->posts) ) {
            $member_id = current( $query->posts )->ID;
            return $member_id;
        }
    
    }
    
    /**
     * Function for `get_family_id`.
     *
     * @param  string $ref_id ref_id.
     *
     * @return string
     */
    public function get_family_id( $ref_id )
    {
        if ( !$ref_id ) {
            return null;
        }
        $args = array(
            'post_type'      => 'family',
            'posts_per_page' => 1,
            'meta_query'     => array( array(
            'key'     => 'Id',
            'value'   => $ref_id,
            'compare' => '=',
        ) ),
        );
        $query = new WP_Query( $args );
        
        if ( !empty($query->posts) ) {
            $family_id = current( $query->posts )->ID;
            return $family_id;
        }
    
    }
    
    /**
     * Function for `get_root_by_family`.
     *
     * @param  string $family family.
     *
     * @return string
     */
    public function get_root_by_family( $family )
    {
        $the_family = $this->get_tree( $family );
        $roots = array();
        foreach ( $the_family as $key => $value ) {
            if ( !$value->father && !$value->father && empty($value->partners) ) {
                $roots[] = $value->ID;
            }
        }
        return current( $roots );
    }
    
    /**
     * Function for `get_the_post_thumbnail_url_tree`.
     *
     * @param  string $id id.
     * @return string
     */
    public function get_the_post_thumbnail_url_tree( $id )
    {
        if ( !$id ) {
            return plugin_dir_url( __FILE__ ) . 'imgs/no-avatar.png';
        }
        return ( get_the_post_thumbnail_url( $id, 'full' ) ? get_the_post_thumbnail_url( $id, 'full' ) : plugin_dir_url( __FILE__ ) . 'imgs/no-avatar.png' );
    }
    
    /**
     * Function for `name_full`.
     *
     * @param  string $id id.
     * @return string
     */
    public function name_full( $id )
    {
        $name_or = get_the_title( $id );
        $name_or = str_replace( '  ', ' ', $name_or );
        if ( get_option( 'bOneNamePerLine', 'false' ) == 'true' ) {
            $name_or = str_replace( ' ', '<br>', $name_or );
        }
        return $name_or;
    }
    
    /**
     * Function for `name_first`.
     *
     * @param  string $id id.
     * @return string
     */
    public function name_first( $id )
    {
        $name_or = get_the_title( $id );
        $name = explode( ' ', $name_or );
        $name = $name[0];
        return $name;
    }
    
    /**
     * Function for `dob_full`.
     *
     * @param  string $id id.
     * @return string
     */
    public function dob_full( $id )
    {
        $born = get_post_meta( $id, 'born', true );
        return $born;
    }
    
    /**
     * Function for `dob_year`.
     *
     * @param  string $id id.
     * @return string
     */
    public function dob_year( $id )
    {
        $born = get_post_meta( $id, 'born', true );
        $born = explode( '-', $born )[0];
        return $born;
    }
    
    /**
     * Function for `dod_full`.
     *
     * @param  string $id id.
     * @return string
     */
    public function dod_full( $id )
    {
        $died = get_post_meta( $id, 'died', true );
        return $died;
    }
    
    /**
     * Function for `dod_year`.
     *
     * @param  string $id id.
     * @return string
     */
    public function dod_year( $id )
    {
        $died = get_post_meta( $id, 'died', true );
        $died = explode( '-', $died )[0];
        return $died;
    }
    
    /**
     * Function for `member_html`.
     *
     * @param  string $member_id member_id.
     * @return string
     */
    public function member_html( $member_id )
    {
        $name = ( get_post_meta( $member_id, 'names', true ) ? get_post_meta( $member_id, 'names', true ) : array(
            'name' => '',
        ) );
        $sex = ( get_post_meta( $member_id, 'sex', true ) ? get_post_meta( $member_id, 'sex', true ) : null );
        $terms = wp_get_post_terms( $member_id, 'family-group' );
        
        if ( $terms ) {
            $term_id = $terms[0]->term_id;
            $ftlink = get_term_meta( $term_id, 'family_tree_link', true );
        } else {
            $ftlink = '';
        }
        
        $fams = ( get_post_meta( $member_id, 'fams' ) ? get_post_meta( $member_id, 'fams' ) : array() );
        $family = array();
        foreach ( $fams as $key => $value ) {
            $fam_id = $this->get_family_id( $value['fam'] );
            $father_id = $this->get_member_id( get_post_meta( $fam_id, 'husb', true ) );
            $mother_id = $this->get_member_id( get_post_meta( $fam_id, 'wife', true ) );
            
            if ( $father_id == $member_id ) {
                $spouse_id = $mother_id;
            } else {
                $spouse_id = $father_id;
            }
            
            $family[$key]['father']['id'] = $father_id;
            $family[$key]['father']['name'] = ( get_post_meta( $father_id, 'names', true ) ? get_post_meta( $father_id, 'names', true ) : array(
                'name' => '',
            ) );
            $family[$key]['mother']['id'] = $mother_id;
            $family[$key]['mother']['name'] = ( get_post_meta( $mother_id, 'names', true ) ? get_post_meta( $mother_id, 'names', true ) : array(
                'name' => '',
            ) );
            $family[$key]['spouse']['id'] = $spouse_id;
            $family[$key]['spouse']['name'] = ( get_post_meta( $spouse_id, 'names', true ) ? get_post_meta( $spouse_id, 'names', true ) : array(
                'name' => '',
            ) );
            $chil = ( get_post_meta( $fam_id, 'chil' ) ? get_post_meta( $fam_id, 'chil' ) : array() );
            foreach ( $chil as $key_chil => $value ) {
                $chil_id = $this->get_member_id( $value );
                
                if ( $chil_id != $member_id ) {
                    $family[$key]['chil'][$key_chil]['id'] = $chil_id;
                    $family[$key]['chil'][$key_chil]['name'] = ( get_post_meta( $chil_id, 'names', true ) ? get_post_meta( $chil_id, 'names', true ) : array(
                        'name' => '',
                    ) );
                }
            
            }
        }
        $famc = ( get_post_meta( $member_id, 'famc' ) ? get_post_meta( $member_id, 'famc' ) : array() );
        $parents = array();
        foreach ( $famc as $key => $value ) {
            $fam_id = $this->get_family_id( $value['fam'] );
            $father_id = $this->get_member_id( get_post_meta( $fam_id, 'husb', true ) );
            $mother_id = $this->get_member_id( get_post_meta( $fam_id, 'wife', true ) );
            $parents[$key]['father']['id'] = $father_id;
            $parents[$key]['father']['name'] = ( get_post_meta( $father_id, 'names', true ) ? get_post_meta( $father_id, 'names', true ) : array(
                'name' => '',
            ) );
            $parents[$key]['mother']['id'] = $mother_id;
            $parents[$key]['mother']['name'] = ( get_post_meta( $mother_id, 'names', true ) ? get_post_meta( $mother_id, 'names', true ) : array(
                'name' => '',
            ) );
            $chil = ( get_post_meta( $fam_id, 'chil' ) ? get_post_meta( $fam_id, 'chil' ) : array() );
            $parents[$key]['chil'] = array();
            foreach ( $chil as $key_chil => $value ) {
                $chil_id = $this->get_member_id( $value );
                
                if ( $chil_id != $member_id ) {
                    $parents[$key]['chil'][$key_chil]['id'] = $chil_id;
                    $parents[$key]['chil'][$key_chil]['name'] = ( get_post_meta( $chil_id, 'names', true ) ? get_post_meta( $chil_id, 'names', true ) : array(
                        'name' => '',
                    ) );
                }
            
            }
        }
        $facts = array(
            'adop' => 'ADOPTION',
            'birt' => 'BIRTH',
            'bapm' => 'BAPTISM',
            'barm' => 'BAR_MITZVAH',
            'bles' => 'BLESSING',
            'buri' => 'BURIAL',
            'cens' => 'CENSUS',
            'chr'  => 'CHRISTENING',
            'crem' => 'CREMATION',
            'deat' => 'DEATH',
            'emig' => 'EMIGRATION',
            'grad' => 'GRADUATION',
            'immi' => 'IMMIGRATION',
            'natu' => 'NATURALIZATION',
            'reti' => 'RETIREMENT',
            'prob' => 'PROBATE',
            'will' => 'WILL',
        );
        $events = ( get_post_meta( $member_id, 'even' ) ? get_post_meta( $member_id, 'even' ) : array() );
        $birth = array();
        $death = array();
        foreach ( $events as $key_e => $value ) {
            
            if ( strtolower( $value['type'] ) == 'birt' ) {
                unset( $events[$key_e] );
                $birth[] = $value;
            }
            
            
            if ( strtolower( $value['type'] ) == 'deat' ) {
                unset( $events[$key_e] );
                $death[] = $value;
            }
        
        }
        $note = ( get_post_meta( $member_id, 'note' ) ? get_post_meta( $member_id, 'note' ) : array() );
        ob_start();
        ?>
		<table border="0" width="100%" cellpadding="4">
			<tbody>
				<tr>
					<td colspan="2" style="vertical-align:bottom">
						<b>
							<a href="<?php 
        echo  esc_attr( get_the_permalink( $member_id ) ) ;
        ?>">
								<?php 
        echo  esc_html( $name['name'] ) ;
        ?>
							</a>
						</b>
						<?php 
        
        if ( strtolower( $sex ) == 'f' ) {
            ?>
							<img alt="Female" title="Female" src="<?php 
            echo  esc_attr( plugin_dir_url( __FILE__ ) ) ;
            ?>/imgs/icon-female-small.gif">
						<?php 
        }
        
        ?>

						<?php 
        
        if ( strtolower( $sex ) == 'm' ) {
            ?>
							<img alt="Male" title="Male" src="<?php 
            echo  esc_attr( plugin_dir_url( __FILE__ ) ) ;
            ?>/imgs/icon-male-small.gif">
						<?php 
        }
        
        ?>

						<?php 
        
        if ( strpos( $ftlink, '?' ) === false ) {
            ?>
							<a href="<?php 
            echo  esc_attr( $ftlink ) ;
            ?>?ancestor=<?php 
            echo  esc_attr( $member_id ) ;
            ?>">
								<img border="0" alt="<?php 
            esc_html_e( 'View tree', 'treepress' );
            ?>" title="<?php 
            esc_html_e( 'View tree', 'treepress' );
            ?>" src="<?php 
            echo  esc_attr( plugin_dir_url( __FILE__ ) ) ;
            ?>/imgs/icon-tree-small.gif"/>
							</a>
						<?php 
        } else {
            ?>
							<a href="<?php 
            echo  esc_attr( $ftlink ) ;
            ?>&ancestor=<?php 
            echo  esc_attr( $member_id ) ;
            ?>">
								<img border="0" alt="<?php 
            esc_html_e( 'View tree', 'treepress' );
            ?>" title="<?php 
            esc_html_e( 'View tree', 'treepress' );
            ?>" src="<?php 
            echo  esc_attr( plugin_dir_url( __FILE__ ) ) ;
            ?>/imgs/icon-tree-small.gif"/>
							</a>
						<?php 
        }
        
        ?>
					</td>
				</tr>
				<tr>
					<td colspan="2"><?php 
        esc_html_e( 'Born:', 'treepress' );
        ?>
						<?php 
        echo  ( isset( $birth[0] ) && isset( $birth[0]['date'] ) ? esc_html( $birth[0]['date'] ) : '' ) ;
        ?>
					</td>
				</tr>
				<tr>
					<td colspan="2"><?php 
        esc_html_e( 'Died:', 'treepress' );
        ?>
						<?php 
        echo  ( isset( $death[0] ) && isset( $death[0]['date'] ) ? esc_html( $death[0]['date'] ) : '' ) ;
        ?>
					</td>
				</tr>
				<?php 
        foreach ( $parents as $key => $value ) {
            ?>
				<tr>
					<td><?php 
            esc_html_e( 'Father:', 'treepress' );
            ?>
						<?php 
            echo  ( $value['father']['name']['name'] ? '<a href="' . esc_attr( get_the_permalink( $value['father']['id'] ) ) . '">' . esc_html( $value['father']['name']['name'] ) . '</a> ' : 'Unspecified' ) ;
            ?>
					</td>
					<td><?php 
            esc_html_e( 'Mother:', 'treepress' );
            ?>
						<?php 
            echo  ( $value['mother']['name']['name'] ? '<a href="' . esc_attr( get_the_permalink( $value['father']['id'] ) ) . '">' . esc_html( $value['mother']['name']['name'] ) . '</a> ' : 'Unspecified' ) ;
            ?>
					</td>
				</tr>
				<tr>
					<td colspan="2"><?php 
            esc_html_e( 'Siblings:', 'treepress' );
            ?>
						<?php 
            if ( empty($value['chil']) ) {
                echo  esc_html__( 'None', 'treepress' ) ;
            }
            foreach ( $value['chil'] as $key => $c ) {
                echo  '<a href="' . esc_attr( get_the_permalink( $c['id'] ) ) . '">' . esc_html( $c['name']['name'] ) . '</a>, ' ;
            }
            ?>
					</td>
				</tr>
				<?php 
        }
        ?>
				<?php 
        foreach ( $family as $key => $value ) {
            ?>
				<tr>
					<td colspan="2">
						<?php 
            esc_html_e( 'Spouse:', 'treepress' );
            ?>
						<?php 
            echo  '<a href="' . esc_attr( get_the_permalink( $value['spouse']['id'] ) ) . '">' . esc_html( $value['spouse']['name']['name'] ) . '</a> ' ;
            ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<?php 
            esc_html_e( 'Children:', 'treepress' );
            ?>
						<?php 
            
            if ( empty($value['chil']) ) {
                echo  esc_html__( 'None', 'treepress' ) ;
            } else {
                foreach ( $value['chil'] as $key => $c ) {
                    echo  '<a href="' . esc_attr( get_the_permalink( $c['id'] ) ) . '">' . esc_html( $c['name']['name'] ) . '</a>, ' ;
                }
            }
            
            ?>
					</td>
				</tr>
				<?php 
        }
        ?>
			</tbody>
		</table>
		<?php 
        
        if ( $events && current( $events ) ) {
            ?>
			<h4> <?php 
            echo  esc_html__( 'Member Events', 'treepress' ) ;
            ?></h4>
			<?php 
            foreach ( $events as $key_event => $e ) {
                $fac_name = ( isset( $facts[strtolower( $e['type'] )] ) ? $facts[strtolower( $e['type'] )] : $e['type'] );
                ?>
				<h5> <?php 
                echo  esc_html( strtoupper( str_replace( '_', ' ', $fac_name ) ) ) ;
                ?></h5>
				<table cellpadding="4" border="0" width="100%">
					<tr>
						<td width="160"><?php 
                echo  esc_html__( 'Date', 'treepress' ) ;
                ?></td>
						<td><?php 
                echo  esc_html( $e['date'] ) ;
                ?></td>
					</tr>
					<tr>
						<td><?php 
                echo  esc_html__( 'Place', 'treepress' ) ;
                ?></td>
						<td><?php 
                echo  esc_html( $e['plac'] ) ;
                ?></td>
					</tr>
				</table>
			<?php 
            }
            ?>
		<?php 
        }
        
        ?>

		<?php 
        
        if ( $note && current( $note ) ) {
            ?>
			<h4> <?php 
            echo  esc_html__( 'Member Notes', 'treepress' ) ;
            ?> </h4>
			<?php 
            foreach ( $note as $key_not => $not ) {
                ?>
				<table cellpadding="4" border="0" width="100%">
					<tr>
						<td width="1"> <?php 
                echo  esc_html( $key_not + 1 ) ;
                ?>. </td>
						<td> <?php 
                echo  esc_html( $not['note'] ) ;
                ?> </td>
					</tr>
				</table>
			<?php 
            }
            ?>
		<?php 
        }
        
        ?>

		<?php 
        
        if ( class_exists( 'TreepressGallery' ) ) {
            $obje = get_post_meta( $member_id, 'Obje' );
            $args = array(
                'post_type'      => 'attachment',
                'post_status'    => 'any',
                'posts_per_page' => -1,
                'post__in'       => $obje,
            );
            
            if ( $obje ) {
                $query = new WP_Query( $args );
                $attachments = $query->posts;
            } else {
                $attachments = array();
            }
            
            $skarr = array();
            
            if ( is_array( $attachments ) && !empty($attachments) ) {
                ?>
				<h4><?php 
                echo  esc_html__( 'Media', 'treepress' ) ;
                ?></h4>
				<table border="0" width="100%">
					<tr>
						<td>
							<ul class="treepress-gallery-gallery">
							<?php 
                foreach ( $attachments as $attachment ) {
                    
                    if ( !in_array( (int) $attachment->ID, $skarr, true ) ) {
                        
                        if ( in_array( (string) $attachment->post_mime_type, array(
                            'image/jpeg',
                            'image/gif',
                            'image/jpg',
                            'image/png'
                        ), true ) ) {
                            ?>
									<li>
										<a href="">
											<img style="max-height: 150px;max-width: 100%; width: auto;" src="<?php 
                            echo  esc_attr( wp_get_attachment_url( $attachment->ID ) ) ;
                            ?>">
										</a>
										<p class="treepress_gallery_gallery_caption">
											<?php 
                            echo  esc_html( get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ) ) ;
                            ?>
										</p>
									</li>
									<?php 
                        } else {
                            ?>
									<li>
										<a href="<?php 
                            echo  esc_attr( wp_get_attachment_url( $attachment->ID ) ) ;
                            ?>">
											<?php 
                            echo  esc_html( get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ) ) ;
                            ?>
										</a>
									</li>
										<?php 
                        }
                        
                        array_push( $skarr, (int) $attachment->ID );
                    }
                
                }
                ?>
							</ul>
						</td>
					</tr>
				</table>
				<?php 
            }
        
        }
        
        $html = ob_get_clean();
        return $html;
    }
    
    /**
     * Function for `bio_data_insert_in_single_page`
     *
     * @param  string $content content.
     * @return string
     */
    public function bio_data_insert_in_single_page( $content )
    {
        global  $post ;
        $html = '';
        
        if ( 'member' == $post->post_type ) {
            $member_id = $post->ID;
            $html .= $this->member_html( $member_id );
        }
        
        
        if ( 'chart' == $post->post_type ) {
            $id = $post->ID;
            ob_start();
            include 'partials/treepress-public-chart-style.php';
            $html .= ob_get_clean();
            $html .= '<div style="max-width:initial;" class="' . $treepress_rand . '">';
            $html .= '<div class="cont_bg">';
            $html .= do_shortcode( '[family-tree style=' . $chart_type . ' root=' . $root_id . ' chart=' . $id . ']' );
            // treepress_family_tree_shortcode.
            $html .= '</div>';
            $html .= '</div>';
        }
        
        return $html . $content;
    }
    
    /**
     * It takes the shortcode attributes and passes them to the function that generates the family tree.
     *
     * @param array  $atts    The attributes passed to the shortcode.
     * @param string $content The content of the shortcode.
     *
     * @return string         The output of the function treepress_family_list()
     */
    public function treepress_family_members_shortcode( $atts, $content = null )
    {
        
        if ( is_array( $atts ) && array_key_exists( 'root', $atts ) ) {
            $root = $atts['root'];
        } else {
            $root = '';
        }
        
        
        if ( is_array( $atts ) && array_key_exists( 'family', $atts ) ) {
            $family = $atts['family'];
        } else {
            $family = '';
        }
        
        $ft_output = $this->treepress_family_list( $family );
        return $ft_output;
    }
    
    /**
     * It takes a family name, gets the family tree, and returns the HTML for the first 200 members of the
     * family.
     *
     * @param string $family the family name.
     *
     * @return string The HTML for the family tree.
     */
    public function treepress_family_list( $family = '' )
    {
        /* Getting the family tree of the family. */
        $the_family_all = (array) $this->get_tree( $family );
        $the_family = array_chunk( $the_family_all, 200, true )[0];
        $html = '';
        foreach ( $the_family as $fm ) {
            $html .= $this->member_html( $fm->ID );
            $html .= '<hr>';
        }
        return $html;
    }
    
    /**
     * It takes the ID of a member and returns a list of their family members
     *
     * @param array  $atts    The attributes passed to the shortcode.
     * @param string $content The content of the shortcode.
     *
     * @return string The function treepress_family_lists is being returned.
     */
    public function treepress_members_shortcode( $atts, $content = null )
    {
        $memberid = $atts['id'];
        $ft_output = $this->treepress_family_lists( $memberid );
        return $ft_output;
    }
    
    /**
     * Function for `treepress_family_list`
     *
     * @param  string $memberid memberid.
     *
     * @return string
     */
    public function treepress_family_lists( $memberid = '' )
    {
        if ( class_exists( 'TreepressShortcode' ) && $memberid ) {
            return $this->member_html( $memberid );
        }
    }
    
    /**
     * It takes the shortcode attributes and returns the HTML for the chart
     *
     * @param array  $atts    The attributes passed to the shortcode.
     * @param string $content The content of the shortcode.
     *
     * @return string         The shortcode is being returned.
     */
    public function chart_shortcode( $atts, $content = null )
    {
        
        if ( is_array( $atts ) && array_key_exists( 'id', $atts ) ) {
            $id = $atts['id'];
        } else {
            return esc_html__( 'ID Missing', 'treepress' );
        }
        
        $html = '';
        ob_start();
        include 'partials/treepress-public-chart-style.php';
        $html .= ob_get_clean();
        ob_start();
        ?>
		<div style="max-width:initial;" class="<?php 
        echo  esc_attr( $treepress_rand ) ;
        ?>">
			<div class="cont_bg">
				<?php 
        // family-tree - 'treepress_family_tree_shortcode'.
        do_shortcode( '[family-tree style=' . $chart_type . ' root=' . $root_id . ' chart=' . $id . ']' );
        ?>
			</div>
		</div>
		<?php 
        $html .= ob_get_clean();
        return $html;
    }
    
    /**
     * Function for `treepress_family_tree_shortcode`.
     *
     * @param  array  $atts atts.
     * @param  string $content content.
     * @return string
     */
    public function treepress_family_tree_shortcode( $atts, $content = null )
    {
        
        if ( is_array( $atts ) && array_key_exists( 'chart', $atts ) ) {
            $default_chart = $this->default_tree_meta();
            $saved_chart = ( get_post_meta( $atts['chart'], 'chart', true ) ? get_post_meta( $atts['chart'], 'chart', true ) : array() );
            $default = ( empty($saved_chart) ? true : false );
            $meta = $this->merge_chart( $default_chart, $saved_chart, $default );
            if ( isset( $meta['group_id'] ) && $meta['group_id'] ) {
                $atts['family'] = $meta['group_id'];
            }
            if ( isset( $meta['root_id'] ) && $meta['root_id'] ) {
                $atts['root'] = $meta['root_id'];
            }
        } else {
            $meta = array();
        }
        
        
        if ( is_array( $atts ) && array_key_exists( 'style', $atts ) ) {
            $style = (int) $atts['style'];
        } else {
            $style = (int) 1;
        }
        
        
        if ( is_array( $atts ) && array_key_exists( 'treetype', $atts ) ) {
            $tree_type = $atts['treetype'];
        } else {
            $tree_type = get_option( 'TreeType', 'html' );
        }
        
        
        if ( is_array( $atts ) && array_key_exists( 'root', $atts ) ) {
            $root = $atts['root'];
        } else {
            $root = '';
        }
        
        
        if ( is_array( $atts ) && array_key_exists( 'align', $atts ) ) {
            $align = $atts['align'];
        } else {
            $align = 'center';
        }
        
        if ( isset( $_GET['ancestor'] ) ) {
            $root = $_GET['ancestor'];
        }
        
        if ( is_array( $atts ) && array_key_exists( 'family', $atts ) ) {
            $family = $atts['family'];
        } else {
            $family = '';
        }
        
        if ( !$family ) {
            return esc_html__( 'Family Id is Required.', 'treepress' );
        }
        $root = $this->get_root_by_family( $family );
        if ( !$root ) {
            return esc_html__( 'Root Id is Required.', 'treepress' );
        }
        if ( 'svg' === (string) $tree_type && 1 === $style ) {
            return $this->treepress_family_tree_svg( $root, $family, $meta );
        }
        
        if ( !class_exists( 'Treepress_Charts' ) ) {
            return $this->treepress_family_default(
                $root,
                $family,
                $align,
                $meta
            );
        } else {
            
            if ( $style && (1 === $style || 11 === $style || 12 === $style) ) {
                return $this->treepress_family_default(
                    $root,
                    $family,
                    $align,
                    $meta
                );
            } else {
                return apply_filters(
                    'more_tree',
                    $root,
                    $style,
                    $family,
                    $meta
                );
            }
        
        }
    
    }
    
    /**
     * It creates a family tree based on the root ID and the chart type.
     *
     * @param string $root   The ID of the person you want to start the tree with.
     * @param string $family The family ID of the family you want to display.
     * @param string $align  left or center.
     * @param array  $meta   This is an array of all the settings for the chart.
     *
     * @return string        The HTML for the family tree.
     */
    public function treepress_family_default(
        $root = '',
        $family = '',
        $align = 'center',
        $meta = array()
    )
    {
        $chart_type = ( isset( $meta['chart_type'] ) ? (int) $meta['chart_type'] : (int) 1 );
        $rand = md5( uniqid( wp_rand(), true ) );
        $show_spouse = 'hide-spouse';
        if ( !$root ) {
            $root = $this->get_root_by_family( $family );
        }
        if ( !$root ) {
            return esc_html__( 'Root Id is Required.', 'treepress' );
        }
        if ( isset( $meta['align'] ) && 'on' === (string) $meta['align'] ) {
            $align = 'left';
        }
        if ( isset( $meta['privacy'] ) && isset( $meta['privacy']['show_spouse'] ) && 'on' === (string) $meta['privacy']['show_spouse'] ) {
            $show_spouse = 'show-spouse';
        }
        $tree = $this->create_tree_by_root( $root );
        $tree_alt = $this->create_tree_by_root_alt( $root );
        ob_start();
        ?>
		<div id="borderBox">
			<div id="dragableElement">
				<div class="tree-default chart-type-<?php 
        echo  esc_attr( $chart_type ) ;
        ?>-container <?php 
        echo  ( 'left' === (string) $align ? 'tree-default-left' : '' ) ;
        ?> " id="tree-container-default-<?php 
        echo  esc_attr( $rand ) ;
        ?>">
					
					<?php 
        
        if ( 11 === $chart_type || 1 === $chart_type ) {
            ?>
					<div class="chart-type-11 tree-descendant <?php 
            echo  esc_attr( $show_spouse ) ;
            ?>">
						<ul class="childs">
							<li>
								<?php 
            $this->family_default11( $tree, $meta );
            ?>
							</li>
						</ul>
					</div>
					<?php 
        }
        
        ?>

					<?php 
        
        if ( 12 === $chart_type ) {
            ?>
					<div class="chart-type-12 tree-pedigree <?php 
            echo  esc_attr( $show_spouse ) ;
            ?>">
						<ul class="childs chart-type-12up">
							<li>
								<?php 
            $this->family_default12( $tree_alt, $meta );
            ?>
							</li>
						</ul>
					</div>
					<?php 
        }
        
        ?>

				</div>
			</div>
		</div>
		<?php 
        
        if ( !$this->ae_detect_ie() ) {
            ?>
			<script>
			jQuery(document).ready(function($){
				var scene = jQuery("#tree-container-default-<?php 
            echo  esc_attr( $rand ) ;
            ?>");
				for (var i = 0; i < scene.length; i++) {
					panzoom(scene[i], {
						onTouch: function(e) {
							return false;
						}
					})
				}
			});
			</script>
			<?php 
        }
        
        return ob_get_clean();
    }
    
    /**
     * It's a recursive function that takes a tree of people and displays them in a family tree format
     *
     * @param  string $tree The tree data
     * @param  array  $meta This is the meta data for the tree.
     * @return void
     */
    public function family_default11( $tree, $meta )
    {
        $sex = strtolower( get_post_meta( $tree['ind'], 'sex', true ) );
        $show_thumb = false;
        if ( isset( $meta['thumb'] ) && isset( $meta['thumb']['show'] ) && 'on' === (string) $meta['thumb']['show'] ) {
            $show_thumb = true;
        }
        $enable_toolbar = false;
        if ( isset( $meta['box'] ) && isset( $meta['box']['enable_toolbar'] ) && 'on' === (string) $meta['box']['enable_toolbar'] ) {
            $enable_toolbar = true;
        }
        ?>
		<div class="person <?php 
        echo  esc_attr( $sex ) ;
        ?>">
			<div data-id="<?php 
        echo  esc_attr( $tree['ind'] ) ;
        ?>">
				<div class="person-info">
					<table>
						<tr>
							<?php 
        
        if ( $show_thumb || $enable_toolbar ) {
            ?>
							<td>
								<div class="thumb">
									<?php 
            
            if ( $show_thumb ) {
                ?>
										<img width="50" src="<?php 
                echo  esc_attr( $this->get_the_post_thumbnail_url_tree( $tree['ind'] ) ) ;
                ?>">
									<?php 
            }
            
            ?>
									<?php 
            
            if ( $enable_toolbar ) {
                ?>
										<?php 
                $this->toolbarlink( $tree['ind'], $meta );
                ?>
									<?php 
            }
            
            ?>
								</div>
							</td>
							<?php 
        }
        
        ?>
							<td>
								<?php 
        $this->ind_box( $tree['ind'], $meta );
        ?>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<?php 
        
        if ( isset( $tree['fam'] ) ) {
            if ( tre_fs()->is_not_paying() ) {
                $tree['fam'] = array( $tree['fam'][0] );
            }
            $show_spouse = false;
            
            if ( isset( $meta['privacy'] ) && isset( $meta['privacy']['show_spouse'] ) && 'on' == $meta['privacy']['show_spouse'] ) {
                $show_spouse = true;
                if ( isset( $meta['privacy'] ) && isset( $meta['privacy']['show_only_one_spouse'] ) && 'on' == $meta['privacy']['show_only_one_spouse'] ) {
                    $tree['fam'] = array( current( $tree['fam'] ) );
                }
            }
            
            foreach ( $tree['fam'] as $key => $fam ) {
                
                if ( isset( $fam['chil'] ) && $fam['chil'] ) {
                    $haschill = 'haschill';
                } else {
                    $haschill = '';
                }
                
                ?>
				<?php 
                
                if ( true == $show_spouse ) {
                    ?>
				<div class="spouse spouse-<?php 
                    echo  esc_attr( $key ) ;
                    ?> <?php 
                    echo  esc_attr( $haschill ) ;
                    ?>">
				<?php 
                }
                
                ?>
				<?php 
                
                if ( true == $show_spouse ) {
                    ?>
					<?php 
                    
                    if ( isset( $fam['spouse'] ) ) {
                        $sex = strtolower( get_post_meta( $fam['spouse'], 'sex', true ) );
                    } else {
                        $sex = '';
                    }
                    
                    ?>
				<div class="person <?php 
                    echo  esc_attr( $sex ) ;
                    ?>">
					<?php 
                    
                    if ( isset( $fam['spouse'] ) ) {
                        ?>
					<div data-id="<?php 
                        echo  esc_attr( $fam['spouse'] ) ;
                        ?>">
						<div class="person-info">
							<table>
								<tr>
									<?php 
                        
                        if ( $show_thumb || $enable_toolbar ) {
                            ?>
									<td>
										<div class="thumb">
										<?php 
                            
                            if ( $show_thumb ) {
                                ?>
											<img width="50" src="<?php 
                                echo  esc_attr( $this->get_the_post_thumbnail_url_tree( $fam['spouse'] ) ) ;
                                ?>">
										<?php 
                            }
                            
                            ?>
										<?php 
                            
                            if ( $enable_toolbar ) {
                                ?>
											<?php 
                                $this->toolbarlink( $fam['spouse'], $meta );
                                ?>
										<?php 
                            }
                            
                            ?>
										</div>
									</td>
									<?php 
                        }
                        
                        ?>
									<td>
										<?php 
                        $this->ind_box( $fam['spouse'], $meta );
                        ?>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<?php 
                    } else {
                        ?>
					<div>
						<div class="person-info">
							<table>
								<tr>
									<?php 
                        
                        if ( $show_thumb || $enable_toolbar ) {
                            ?>
									<td>
										<div class="thumb">
										<?php 
                            
                            if ( $show_thumb ) {
                                ?>
											<img width="50" src="<?php 
                                echo  esc_attr( $this->get_the_post_thumbnail_url_tree( null ) ) ;
                                ?>">
										<?php 
                            }
                            
                            ?>
										<?php 
                            
                            if ( $enable_toolbar ) {
                                ?>
											<?php 
                                $this->toolbarlink( null, $meta );
                                ?>
										<?php 
                            }
                            
                            ?>
										</div>
									</td>
									<?php 
                        }
                        
                        ?>
									<td>
										<?php 
                        $this->ind_box( null, $meta );
                        ?>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<?php 
                    }
                    
                    ?>
				</div>
				<?php 
                }
                
                ?>
				<?php 
                
                if ( isset( $fam['chil'] ) && !empty($fam['chil']) ) {
                    ?>
					<ul class="childs">
					<?php 
                    foreach ( $fam['chil'] as $keyx => $chill ) {
                        ?>
							<li>
								<?php 
                        $this->family_default11( $chill, $meta );
                        ?>
							</li>
					<?php 
                    }
                    ?>
					</ul>
				<?php 
                }
                
                ?>
				<?php 
                if ( true === (bool) $show_spouse ) {
                    ?>
				</div>
				<?php 
                }
                ?>
				<?php 
            }
        }
        
        ?>
		<?php 
    }
    
    /**
     * Function for `family_default12`.
     *
     * @param  string $tree tree.
     * @param  array  $meta meta.
     * @return string
     */
    public function family_default12( $tree, $meta )
    {
        $sex = strtolower( get_post_meta( $tree['ind'], 'sex', true ) );
        $show_thumb = false;
        if ( isset( $meta['thumb'] ) && isset( $meta['thumb']['show'] ) && 'on' == $meta['thumb']['show'] ) {
            $show_thumb = true;
        }
        $enable_toolbar = false;
        if ( isset( $meta['box'] ) && isset( $meta['box']['enable_toolbar'] ) && 'on' == $meta['box']['enable_toolbar'] ) {
            $enable_toolbar = true;
        }
        ob_start();
        ?>

		<div class="person <?php 
        echo  esc_attr( $sex ) ;
        ?>">
			<div data-id="<?php 
        echo  esc_attr( $tree['ind'] ) ;
        ?>">
				<div class="person-info">
					<table>
						<tr>
							<td>
								<div class="thumb">
									<?php 
        
        if ( $show_thumb ) {
            ?>
									<img width="50" src="<?php 
            echo  esc_attr( $this->get_the_post_thumbnail_url_tree( $tree['ind'] ) ) ;
            ?>">
									<?php 
        }
        
        ?>
									<?php 
        
        if ( $enable_toolbar ) {
            ?>
										<?php 
            $this->toolbarlink( $tree['ind'], $meta );
            ?>
									<?php 
        }
        
        ?>
								</div>
							</td>
							<td>
								<?php 
        $this->ind_box( $tree['ind'], $meta );
        ?>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<?php 
        
        if ( !empty($tree['fam']) ) {
            $show_spouse = false;
            if ( isset( $meta['privacy'] ) && isset( $meta['privacy']['show_spouse'] ) && 'on' == $meta['privacy']['show_spouse'] ) {
                $show_spouse = true;
            }
            foreach ( $tree['fam'] as $key => $fam ) {
                ?>
				<?php 
                
                if ( isset( $fam['father'] ) && $fam['father'] || isset( $fam['mother'] ) && $fam['mother'] ) {
                    ?>
					<ul class="childs">
					<?php 
                    
                    if ( isset( $fam['father'] ) && $fam['father'] ) {
                        ?>
							<li>
						<?php 
                        $this->family_default12( $fam['father'], $meta );
                        ?>
							</li>
					<?php 
                    }
                    
                    ?>
					<?php 
                    
                    if ( isset( $fam['mother'] ) && $fam['mother'] && $show_spouse ) {
                        ?>
							<li>
						<?php 
                        $this->family_default12( $fam['mother'], $meta );
                        ?>
							</li>
					<?php 
                    }
                    
                    ?>
					</ul>
					<?php 
                }
            
            }
        }
        
        return ob_get_clean();
    }
    
    /**
     * It takes the ID of a person, and a set of options, and returns a string of HTML that displays the
     * person's name and living dates.
     *
     * @param string $id   The ID of the individual.
     * @param array  $meta This is the array of settings for the tree.
     *
     * @return void
     */
    public function ind_box( $id, $meta )
    {
        $show_living_dates = false;
        $show_gender = false;
        if ( isset( $meta['privacy'] ) && isset( $meta['privacy']['show_living_dates'] ) && 'on' === (string) $meta['privacy']['show_living_dates'] ) {
            $show_living_dates = true;
        }
        if ( isset( $meta['privacy'] ) && isset( $meta['privacy']['show_gender'] ) && 'on' === (string) $meta['privacy']['show_gender'] ) {
            $show_gender = true;
        }
        if ( !$id ) {
            ?>
			<div class="treepress-text-container">
				<a href="#">
					<div class="text-name">N/A</div>
				</a>
			</div>
			<?php 
        }
        
        if ( $id ) {
            
            if ( isset( $meta['name_format'] ) && 'full' === (string) $meta['name_format'] ) {
                $name_text = $this->name_full( $id );
            } elseif ( !isset( $meta['name_format'] ) ) {
                $name_text = $this->name_full( $id );
            } else {
                $name_text = $this->name_first( $id );
            }
            
            if ( isset( $meta['wrap_names'] ) && 'on' === (string) $meta['wrap_names'] ) {
                $name_text = str_replace( ' ', "\n", $name_text );
            }
            $date_of_birth = $this->date_of_birth( $id, $meta );
            $date_of_death = $this->date_of_death( $id, $meta );
            $birth_date_prefix = ( isset( $meta['date_birth_prefix'] ) && $meta['date_birth_prefix'] ? $meta['date_birth_prefix'] : '' );
            $death_date_prefix = ( isset( $meta['death_date_prefix'] ) && $meta['death_date_prefix'] ? $meta['death_date_prefix'] : '' );
            $date_seperator = ( $date_of_birth && $date_of_death ? ' - ' : '' );
            $living_text = (( $date_of_birth ? $birth_date_prefix : '' )) . ' ' . $date_of_birth . $date_seperator . (( $date_of_death ? $death_date_prefix : '' )) . ' ' . $date_of_death;
            ?>
			<div class="treepress-text-container">
				<a href="<?php 
            echo  esc_attr( get_the_permalink( $id ) ) ;
            ?>">
					<div class="text-name">
						<?php 
            echo  $name_text ;
            ?>
						<?php 
            
            if ( $show_gender ) {
                ?>
							<?php 
                echo  ( get_post_meta( $id, 'sex', true ) ? '(' . esc_html( get_post_meta( $id, 'sex', true ) ) . ')' : '' ) ;
                ?>
						<?php 
            }
            
            ?>
					</div>
				</a>
				<?php 
            
            if ( $show_living_dates ) {
                ?>
				<div class="text-other">
					&nbsp; <?php 
                echo  esc_html( $living_text ) ;
                ?> &nbsp;
				</div>
				<?php 
            }
            
            ?>
			</div>
			<?php 
        }
    
    }
    
    /**
     * It returns the date of birth of a person, formatted according to the user's preference
     *
     * @param  string $id   The post ID of the person.
     * @param  array  $meta This is the array of meta data for the person.
     * @return string       The date of birth of the person.
     */
    public function date_of_birth( $id, $meta )
    {
        $birt = array();
        $even = ( get_post_meta( $id, 'even' ) ? get_post_meta( $id, 'even' ) : array() );
        foreach ( $even as $key => $ev ) {
            if ( isset( $ev['type'] ) && 'BIRT' == $ev['type'] && isset( $ev['date'] ) && $ev['date'] ) {
                $birt[] = $ev;
            }
        }
        $birt_date = ( !empty($birt) ? current( $birt )['date'] : null );
        if ( isset( $meta['dob_format'] ) && 'full' == $meta['dob_format'] ) {
            return $birt_date;
        }
        
        if ( isset( $meta['dob_format'] ) && 'year' == $meta['dob_format'] && $birt_date ) {
            if ( 0 < count( array_intersect( array_map( 'strtolower', explode( ' ', $birt_date ) ), array( 'abt' ) ) ) ) {
                return $birt_date;
            }
            return date( 'Y', strtotime( $birt_date ) );
        }
        
        return $birt_date;
    }
    
    /**
     * It returns the date of death of a person
     *
     * @param  string $id   The post ID of the person.
     * @param  array  $meta This is the array of parameters that you pass to the shortcode.
     * @return string       The date of death of the person.
     */
    public function date_of_death( $id, $meta )
    {
        $death = array();
        $even = ( get_post_meta( $id, 'even' ) ? get_post_meta( $id, 'even' ) : array() );
        foreach ( $even as $key => $ev ) {
            if ( isset( $ev['type'] ) && 'DEAT' === (string) $ev['type'] && isset( $ev['date'] ) && $ev['date'] ) {
                $death[] = $ev;
            }
        }
        $death_date = ( !empty($death) ? current( $death )['date'] : null );
        if ( isset( $meta['dod_format'] ) && 'full' === (string) $meta['dod_format'] ) {
            return $death_date;
        }
        if ( isset( $meta['dod_format'] ) && 'year' === (string) $meta['dod_format'] && $death_date ) {
            return gmdate( 'Y', strtotime( $death_date ) );
        }
        return $death_date;
    }
    
    /**
     * It returns an array of default values for the tree meta
     *
     * @return array The default tree meta.
     */
    public function default_tree_meta()
    {
        return array(
            'privacy'            => array(
            'show_living_dates'    => '',
            'show_spouse'          => 'on',
            'show_only_one_spouse' => 'on',
            'show_gender'          => 'on',
        ),
            'align'              => 'on',
            'node_opacity'       => '1',
            'node_minimum_width' => '150px',
            'height_generations' => '10px',
            'chart_type'         => '2',
            'group_id'           => '',
            'root_id'            => '',
            'image_bg'           => array(
            'show'   => '',
            'size'   => '',
            'repeat' => '',
        ),
            'bg_color'           => '#eff4ff',
            'box'                => array(
            'show'             => 'on',
            'border'           => array(
            'style'  => 'solid',
            'weight' => '1px',
            'radius' => '5px',
            'color'  => array(
            'male'   => '#6d97bf',
            'female' => '#ddad66',
            'other'  => '#ffffff',
        ),
        ),
            'bg_color'         => array(
            'male'   => '#0066bf',
            'female' => '#dd8500',
            'other'  => '#ffffff',
        ),
            'enable_toolbar'   => 'on',
            'single_page_link' => 'on',
            'tree_nav_link'    => 'on',
            'padding'          => '4px',
        ),
            'line'               => array(
            'show'   => 'on',
            'style'  => 'solid',
            'weight' => '1px',
            'color'  => '#0024f2',
        ),
            'thumb'              => array(
            'show'          => 'on',
            'border_style'  => 'dotted',
            'border_weight' => '1px',
            'border_radius' => '4px',
            'margin'        => '4px',
        ),
            'name'               => array(
            'font'             => 'Arial',
            'font_size'        => '12px',
            'font_color'       => '#222222',
            'font_other'       => 'Arial',
            'font_other_size'  => '12px',
            'font_other_color' => '#222222',
        ),
            'name_format'        => 'full',
            'dob_format'         => 'full',
            'dod_format'         => 'full',
            'dom_format'         => 'full',
            'wrap_names'         => 'on',
            'date_birth_prefix'  => 'b.',
            'death_date_prefix'  => 'd.',
        );
    }
    
    /**
     * It merges two arrays, but if the value is an array, it recursively calls itself to merge the arrays
     *
     * @param array   $base     The base array to merge into.
     * @param array   $input    The input array to be merged.
     * @param boolean $default  true/false - if true, the default values will be used if the input is empty. If
     * false, the default values will be ignored.
     * @param array   $output     The array that will be returned.
     *
     * @return array The array that will be returned.
     */
    public function merge_chart(
        $base,
        $input,
        $default = true,
        $output = array()
    )
    {
        foreach ( $base as $key => $value ) {
            
            if ( $value && is_array( $value ) && isset( $input[$key] ) && $input[$key] && is_array( $input[$key] ) ) {
                $output[$key] = $this->merge_chart( $value, $input[$key], $default );
            } elseif ( !is_array( $value ) && isset( $input[$key] ) && $input[$key] && !is_array( $input[$key] ) || isset( $input[$key] ) && ('0' === (string) $input[$key] || '1' === (string) $input[$key] || 'false' === (string) $input[$key] || 'true' === (string) $input[$key] || 0 === (int) $input[$key] || 1 === (int) $input[$key] || false === (bool) $input[$key] || true === (bool) $input[$key]) ) {
                $output[$key] = $input[$key];
            } else {
                $output[$key] = ( $default ? $base[$key] : $base[$key] );
            }
        
        }
        return $output;
    }

}