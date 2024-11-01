<?php

wp_nonce_field( 'treepress_member_info_nonce_action', 'treepress_member_info_nonce' );
$name = ( get_post_meta( $post->ID, 'names' ) ? current( get_post_meta( $post->ID, 'names' ) ) : array(
    'name' => '',
    'npfx' => '',
    'givn' => '',
    'nick' => '',
    'spfx' => '',
    'surn' => '',
    'nsfx' => '',
) );
$ref_id = ( get_post_meta( $post->ID, 'Id', true ) ? get_post_meta( $post->ID, 'Id', true ) : 'I' . $post->ID );
echo  '<table border="0">' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<h3 style="margin-top:0;margin-bottom:5px;">' . esc_html__( 'Names', 'treepress' ) . '</h3>' ;
echo  '<table border="0">' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<label style="width:125px;display:block;"> <strong>' . esc_html__( 'Id', 'treepress' ) . ' </strong> </label>' ;
echo  '</td>' ;
echo  '<td>' ;
echo  '<input name="treepress[member][Id]" readonly type="text" value="' . esc_attr( $ref_id ) . '">' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<label style="width:125px;display:block;"> <strong>' . esc_html__( 'Name', 'treepress' ) . ' </strong> </label>' ;
echo  '</td>' ;
echo  '<td>' ;
echo  '<input name="treepress[member][names][0][name]" type="text" value="' . esc_attr( $name['name'] ) . '">' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<label> <strong>' . esc_html__( 'Name Prefix', 'treepress' ) . ' </strong> </label>' ;
echo  '</td>' ;
echo  '<td>' ;
echo  '<input name="treepress[member][names][0][npfx]" type="text" value="' . esc_attr( $name['npfx'] ) . '">' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<label style="width:125px;display:block;"> <strong>' . esc_html__( 'Given Name', 'treepress' ) . ' </strong> </label>' ;
echo  '</td>' ;
echo  '<td>' ;
echo  '<input name="treepress[member][names][0][givn]" type="text" value="' . esc_attr( $name['givn'] ) . '">' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<label style="width:125px;display:block;"> <strong>' . esc_html__( 'Nickname', 'treepress' ) . ' </strong> </label>' ;
echo  '</td>' ;
echo  '<td>' ;
echo  '<input name="treepress[member][names][0][nick]" type="text" value="' . esc_attr( $name['nick'] ) . '">' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<label> <strong>' . esc_html__( 'Surname Prefix', 'treepress' ) . ' </strong> </label>' ;
echo  '</td>' ;
echo  '<td>' ;
echo  '<input name="treepress[member][names][0][spfx]" type="text" value="' . esc_attr( $name['spfx'] ) . '">' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<label> <strong>' . esc_html__( 'Surname', 'treepress' ) . ' </strong> </label>' ;
echo  '</td>' ;
echo  '<td>' ;
echo  '<input name="treepress[member][names][0][surn]" type="text" value="' . esc_attr( $name['surn'] ) . '">' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<label> <strong>' . esc_html__( 'Name Suffix', 'treepress' ) . ' </strong> </label>' ;
echo  '</td>' ;
echo  '<td>' ;
echo  '<input name="treepress[member][names][0][nsfx]" type="text" value="' . esc_attr( $name['nsfx'] ) . '">' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '</table>' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '</table>' ;
echo  '<br/>' ;
$sex = ( get_post_meta( $post->ID, 'sex', true ) ? get_post_meta( $post->ID, 'sex', true ) : '' );
echo  '<table border="0">' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<h3 style="margin-top:0;margin-bottom:5px;">' . esc_html__( 'Gender', 'treepress' ) . '</h3>' ;
echo  '<table border="0">' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<label style="width:125px;display:block;"> <strong>' . esc_html__( 'Select Gender', 'treepress' ) . ' </strong> </label>' ;
echo  '</td>' ;
echo  '<td>' ;
echo  '<select name="treepress[member][sex]">' ;
echo  '<option ' . selected( $sex, '' ) . ' value="">' . esc_html__( 'Unknown', 'treepress' ) . '</option>' ;
echo  '<option ' . selected( $sex, 'M' ) . ' value="M">' . esc_html__( 'Male', 'treepress' ) . '</option>' ;
echo  '<option ' . selected( $sex, 'F' ) . ' value="F">' . esc_html__( 'Female', 'treepress' ) . '</option>' ;
echo  '<option ' . selected( $sex, 'O' ) . ' value="O">' . esc_html__( 'Other', 'treepress' ) . '</option>' ;
echo  '</select>' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '</table>' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '</table>' ;
echo  '<br/>' ;
$even = ( get_post_meta( $post->ID, 'even' ) ? get_post_meta( $post->ID, 'even' ) : array() );
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
echo  '<table border="0">' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<h3 style="margin-top:0;margin-bottom:5px;">' . esc_html__( 'Birth', 'treepress' ) . '</h3>' ;
echo  '<table border="0">' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<label style="width:125px;display:block;"> <strong>' . esc_html__( 'Birth Date', 'treepress' ) . ' </strong> </label>' ;
echo  '</td>' ;
echo  '<td>' ;
echo  '<input name="treepress[member][even][0][type]" type="hidden" value="BIRT">' ;
echo  '<input name="treepress[member][even][0][date]" type="text" value="' . esc_attr( $birt['date'] ) . '">' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '</table>' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '</table>' ;
echo  '<br/>' ;
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
echo  '<table border="0">' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<h3 style="margin-top:0;margin-bottom:5px;">' . esc_html__( 'Death', 'treepress' ) . '</h3>' ;
echo  '<table border="0">' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<label style="width:125px;display:block;"> <strong>' . esc_html__( 'Death Date', 'treepress' ) . ' </strong> </label>' ;
echo  '</td>' ;
echo  '<td>' ;
echo  '<input name="treepress[member][even][1][type]" type="hidden" value="DEAT">' ;
echo  '<input name="treepress[member][even][1][date]" type="text" value="' . esc_attr( $deat['date'] ) . '">' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '</table>' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '</table>' ;
echo  '<br/>' ;
echo  '<table border="0">' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<table border="0" width="100%">' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<h3 style="margin-top:0;margin-bottom:5px;">' ;
echo  esc_html__( 'Parents', 'treepress' ) ;
$url = site_url() . '/wp-admin/post-new.php?post_type=family&chil=' . esc_attr( $post->ID );
$url = add_query_arg( '_wpnonce', wp_create_nonce( '_new_family' ), $url );
echo  ' <small><a href="' . esc_attr( $url ) . '">' . esc_html__( 'Add', 'treepress' ) . '</a></small>' ;
echo  '</h3>' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '</table>' ;
$famc = ( get_post_meta( $post->ID, 'famc' ) ? get_post_meta( $post->ID, 'famc' ) : array( array(
    'fam'  => '',
    'pedi' => '',
) ) );
foreach ( $famc as $famc_key => $fac ) {
    $args = array(
        'post_type'      => 'family',
        'posts_per_page' => '1',
        'post_status'    => array( 'publish' ),
        'meta_query'     => array( array(
        'key'     => 'Id',
        'value'   => $fac['fam'],
        'compare' => '=',
    ) ),
    );
    $query = new WP_Query( $args );
    
    if ( $query->posts ) {
        $family_id = current( $query->posts )->ID;
        $husb = get_post_meta( $family_id, 'husb', true );
        $wife = get_post_meta( $family_id, 'wife', true );
        $chil = ( get_post_meta( $family_id, 'chil' ) ? get_post_meta( $family_id, 'chil' ) : array() );
        $args = array(
            'post_type'      => 'member',
            'posts_per_page' => '1',
            'post_status'    => array( 'publish' ),
            'meta_query'     => array( array(
            'key'     => 'Id',
            'value'   => $husb,
            'compare' => '=',
        ) ),
        );
        $query = new WP_Query( $args );
        if ( $query->posts ) {
            $husbOb = current( $query->posts );
        }
        $args = array(
            'post_type'      => 'member',
            'posts_per_page' => '1',
            'post_status'    => array( 'publish' ),
            'meta_query'     => array( array(
            'key'     => 'Id',
            'value'   => $wife,
            'compare' => '=',
        ) ),
        );
        $query = new WP_Query( $args );
        if ( $query->posts ) {
            $wifeOb = current( $query->posts );
        }
        echo  '<table border="0">' ;
        echo  '<tr>' ;
        echo  '<td>' ;
        echo  '<label style="width:125px;display:block;"> <strong>' . esc_html__( 'Family', 'treepress' ) . ' </strong> </label>' ;
        echo  '</td>' ;
        echo  '<td>' ;
        echo  '<a href="' . get_edit_post_link( $family_id ) . '">' . esc_html__( 'Edit Family', 'treepress' ) . ' (' . $fac['fam'] . ')</a> | 
												<a class="unlink-member" href="" data-key="chil" data-post_id="' . $post->ID . '" data-family_id="' . $fac['fam'] . '" data-member_id="' . $ref_id . '">' . __( 'Unlink', 'treepress' ) . '</a> ' ;
        echo  '</td>' ;
        echo  '</tr>' ;
        echo  '<tr>' ;
        echo  '<td>' ;
        echo  '<label style="width:125px;display:block;"> <strong>' . esc_html__( 'Pedigree', 'treepress' ) . ' </strong> </label>' ;
        echo  '</td>' ;
        echo  '<td>' ;
        echo  '<input type="hidden" value="' . $fac['fam'] . '" name="treepress[member][famc][' . $famc_key . '][fam]">' ;
        echo  '<select name="treepress[member][famc][' . $famc_key . '][pedi]">' ;
        echo  '<option value="">Select Pedigree</option>' ;
        echo  '<option ' . selected( $fac['pedi'], 'ADOPTED' ) . ' value="ADOPTED">ADOPTED</option>' ;
        echo  '<option ' . selected( $fac['pedi'], 'BIRTH' ) . ' value="BIRTH">BIRTH</option>' ;
        echo  '<option ' . selected( $fac['pedi'], 'FOSTER' ) . ' value="FOSTER">FOSTER</option>' ;
        echo  '<option ' . selected( $fac['pedi'], 'SEALING' ) . ' value="SEALING">SEALING</option>' ;
        echo  '<option ' . selected( $fac['pedi'], 'OTHER' ) . ' value="OTHER">OTHER</option>' ;
        echo  '</select>' ;
        echo  '</td>' ;
        echo  '</tr>' ;
        echo  '<tr>' ;
        echo  '<td valign="top">' ;
        echo  '<label style="width:125px;display:block;"> <strong>' . esc_html__( 'Father', 'treepress' ) . ' </strong> </label>' ;
        echo  '</td>' ;
        echo  '<td>' ;
        
        if ( isset( $husbOb ) && $husbOb ) {
            echo  '<a href="' . get_edit_post_link( $husbOb->ID ) . '">' . get_post_meta( $husbOb->ID, 'names', true )['name'] . '</a>  ' ;
        } else {
            echo  esc_html__( 'No data.', 'treepress' ) ;
        }
        
        echo  '</td>' ;
        echo  '</tr>' ;
        echo  '<tr>' ;
        echo  '<td valign="top">' ;
        echo  '<label style="width:125px;display:block;"> <strong>' . esc_html__( 'Mother', 'treepress' ) . ' </strong> </label>' ;
        echo  '</td>' ;
        echo  '<td>' ;
        
        if ( isset( $wifeOb ) && $wifeOb ) {
            echo  '<a href="' . get_edit_post_link( $wifeOb->ID ) . '">' . get_post_meta( $wifeOb->ID, 'names', true )['name'] . '</a>  ' ;
        } else {
            echo  esc_html__( 'No data.', 'treepress' ) ;
        }
        
        echo  '</td>' ;
        echo  '</tr>' ;
        echo  '<tr>' ;
        echo  '<td valign="top">' ;
        echo  '<label style="width:125px;display:block;"> <strong>' . __( 'Childrens', 'treepress' ) . ' </strong> </label>' ;
        echo  '</td>' ;
        echo  '<td>' ;
        foreach ( $chil as $key => $value ) {
            $args = array(
                'post_type'      => 'member',
                'posts_per_page' => '1',
                'post_status'    => array( 'publish' ),
                'meta_query'     => array( array(
                'key'     => 'Id',
                'value'   => $value,
                'compare' => '=',
            ) ),
            );
            $query = new WP_Query( $args );
            if ( $query->posts ) {
                $chillOb = current( $query->posts );
            }
            
            if ( isset( $chillOb ) && $chillOb ) {
                echo  '<a href="' . get_edit_post_link( $chillOb->ID ) . '">' . get_post_meta( $chillOb->ID, 'names', true )['name'] . '</a>  <br>' ;
            } else {
                echo  esc_html__( 'No data.', 'treepress' ) ;
            }
        
        }
        echo  '</td>' ;
        echo  '</tr>' ;
        echo  '</table>' ;
    }

}
echo  '</td>' ;
echo  '</tr>' ;
echo  '</table>' ;
echo  '<br/>' ;
echo  '<table border="0">' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<table border="0" width="100%">' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<h3 style="margin-top:0;margin-bottom:5px;">' ;
echo  __( 'Spouses', 'treepress' ) ;
$url = site_url() . '/wp-admin/post-new.php?post_type=family&spouse=' . esc_attr( $post->ID );
$url = add_query_arg( '_wpnonce', wp_create_nonce( '_new_family' ), $url );
echo  ' <small><a href="' . esc_attr( $url ) . '">' . esc_html__( 'Add', 'treepress' ) . '</a></small>' ;
echo  '</h3>' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '</table>' ;
$fams = ( get_post_meta( $post->ID, 'fams' ) ? get_post_meta( $post->ID, 'fams' ) : array() );
foreach ( $fams as $key => $fas ) {
    $args = array(
        'post_type'      => 'family',
        'posts_per_page' => '1',
        'post_status'    => array( 'publish' ),
        'meta_query'     => array( array(
        'key'     => 'Id',
        'value'   => $fas['fam'],
        'compare' => '=',
    ) ),
    );
    $query = new WP_Query( $args );
    
    if ( $query->posts ) {
        $family_id = current( $query->posts )->ID;
        $husb = get_post_meta( $family_id, 'husb', true );
        $wife = get_post_meta( $family_id, 'wife', true );
        $chil = ( get_post_meta( $family_id, 'chil' ) ? get_post_meta( $family_id, 'chil' ) : array() );
        
        if ( $husb === get_post_meta( $post->ID, 'Id', true ) ) {
            $spouse = $wife;
        } else {
            $spouse = $husb;
        }
        
        $args = array(
            'post_type'      => 'member',
            'posts_per_page' => '1',
            'post_status'    => array( 'publish' ),
            'meta_query'     => array( array(
            'key'     => 'Id',
            'value'   => $spouse,
            'compare' => '=',
        ) ),
        );
        $query = new WP_Query( $args );
        if ( $query->posts ) {
            $spouseOb = current( $query->posts );
        }
        echo  '<table border="0">' ;
        echo  '<tr>' ;
        echo  '<td>' ;
        echo  '<label style="width:125px;display:block;"> <strong>' . esc_html__( 'Family', 'treepress' ) . ' </strong> </label>' ;
        echo  '</td>' ;
        echo  '<td>' ;
        echo  '<a href="' . get_edit_post_link( $family_id ) . '">' . esc_html__( 'Edit Family', 'treepress' ) . ' (' . $fas['fam'] . ')</a> | <a class="unlink-member" href="" data-key="spouse" data-post_id="' . $post->ID . '" data-family_id="' . $fas['fam'] . '" data-member_id="' . $ref_id . '">' . __( 'Unlink', 'treepress' ) . '</a> ' ;
        echo  '</td>' ;
        echo  '</tr>' ;
        echo  '<tr>' ;
        echo  '<td valign="top">' ;
        echo  '<label style="width:125px;display:block;"> <strong>' . esc_html__( 'Spouse', 'treepress' ) . ' </strong> </label>' ;
        echo  '</td>' ;
        echo  '<td>' ;
        
        if ( isset( $spouseOb ) && $spouseOb ) {
            echo  '<a href="' . get_edit_post_link( $spouseOb->ID ) . '">' . get_post_meta( $spouseOb->ID, 'names', true )['name'] . '</a>  ' ;
        } else {
            echo  esc_html__( 'No data.', 'treepress' ) ;
        }
        
        echo  '</td>' ;
        echo  '</tr>' ;
        echo  '<tr>' ;
        echo  '<td valign="top">' ;
        echo  '<label style="width:125px;display:block;"> <strong>' . esc_html__( 'Childrens', 'treepress' ) . ' </strong> </label>' ;
        echo  '</td>' ;
        echo  '<td>' ;
        foreach ( $chil as $key => $value ) {
            $args = array(
                'post_type'      => 'member',
                'posts_per_page' => '1',
                'post_status'    => array( 'publish' ),
                'meta_query'     => array( array(
                'key'     => 'Id',
                'value'   => $value,
                'compare' => '=',
            ) ),
            );
            $query = new WP_Query( $args );
            if ( $query->posts ) {
                $chillOb = current( $query->posts );
            }
            
            if ( isset( $chillOb ) && $chillOb ) {
                echo  '<a href="' . get_edit_post_link( $chillOb->ID ) . '">' . get_post_meta( $chillOb->ID, 'names', true )['name'] . '</a>  <br>' ;
            } else {
                echo  esc_html__( 'No data.', 'treepress' ) ;
            }
        
        }
        echo  '</td>' ;
        echo  '</tr>' ;
        echo  '</table>' ;
    }

}
echo  '</td>' ;
echo  '</tr>' ;
echo  '</table>' ;
echo  '<br/>' ;
$le = array(
    'ADOP' => __( 'adoption', 'treepress' ),
    'BAPM' => __( 'baptism', 'treepress' ),
    'BARM' => __( 'Bar Mitzvah', 'treepress' ),
    'BASM' => __( 'Bas Mitzvah', 'treepress' ),
    'BIRT' => __( 'birth', 'treepress' ),
    'BLES' => __( 'blessing', 'treepress' ),
    'BURI' => __( 'burial', 'treepress' ),
    'CENS' => __( 'census', 'treepress' ),
    'CHR'  => __( 'christening', 'treepress' ),
    'CHRA' => __( 'adult christening', 'treepress' ),
    'CONF' => __( 'confirmation', 'treepress' ),
    'CREM' => __( 'cremation', 'treepress' ),
    'DEAT' => __( 'death', 'treepress' ),
    'EMIG' => __( 'emigration', 'treepress' ),
    'FCOM' => __( 'first communion', 'treepress' ),
    'GRAD' => __( 'graduation', 'treepress' ),
    'IMMI' => __( 'immigration', 'treepress' ),
    'NATU' => __( 'naturalization', 'treepress' ),
    'ORDN' => __( 'ordination', 'treepress' ),
    'PROB' => __( 'probate', 'treepress' ),
    'RETI' => __( 'retirement', 'treepress' ),
    'WILL' => __( 'will', 'treepress' ),
    'EVEN' => __( 'generic event', 'treepress' ),
);
echo  '<table border="0">' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<table border="0" width="100%">' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<h3 style="margin-top:0;margin-bottom:5px;">' . esc_html__( 'Events', 'treepress' ) . ' <small><a id="opener-member-event-add" href="#">' . esc_html__( 'Add', 'treepress' ) . '</a></small></h3>' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '</table>' ;
echo  '<table id="table-member-events" border="0" width="100%">' ;
if ( !empty($even) ) {
    foreach ( $even as $key => $event ) {
        
        if ( $event['type'] !== 'BIRT' && $event['type'] !== 'DEAT' ) {
            echo  '<tbody>' ;
            echo  '<tr>' ;
            echo  '<td colspan="2">' ;
            echo  '<h4  style="margin-top:0;margin-bottom:5px;">' . $event['type'] . ' <small> <a class="dtbody" href="">' . esc_html__( 'Delete', 'treepress' ) . '</a></small></h4>' ;
            echo  '</td>' ;
            echo  '</tr>' ;
            echo  '<tr>' ;
            echo  '<td>' ;
            echo  '<label style="width:125px;display:block;"> <strong>' . esc_html__( 'Date', 'treepress' ) . ' </strong> </label>' ;
            echo  '</td>' ;
            echo  '<td>' ;
            echo  '<input name="treepress[member][even][' . $key . '][type]" type="hidden" value="' . $event['type'] . '">' ;
            echo  '<input name="treepress[member][even][' . $key . '][date]" type="text" value="' . $event['date'] . '">' ;
            echo  '</td>' ;
            echo  '</tr>' ;
            echo  '<tr>' ;
            echo  '<td>' ;
            echo  '<label style="width:125px;display:block;"> <strong>' . esc_html__( 'Place', 'treepress' ) . ' </strong> </label>' ;
            echo  '</td>' ;
            echo  '<td>' ;
            echo  '<input name="treepress[member][even][' . $key . '][plac]" type="text" value="' . $event['plac'] . '">' ;
            echo  '</td>' ;
            echo  '</tr>' ;
            echo  '</tbody>' ;
        }
    
    }
}
echo  '</table>' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '</table>' ;
echo  '<br/>' ;
echo  '<table border="0">' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<table border="0" width="100%">' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<h3 style="margin-top:0;margin-bottom:5px;">' . esc_html__( 'Notes', 'treepress' ) . ' <small><a id="opener-member-note-add" href="">' . esc_html__( 'Add', 'treepress' ) . '</a></small></h3>' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '</table>' ;
$notes = ( get_post_meta( $post->ID, 'note' ) ? get_post_meta( $post->ID, 'note' ) : array( array(
    'note' => '',
) ) );
echo  '<table id="table-member-notes" border="0" width="100%">' ;
foreach ( $notes as $key => $note ) {
    
    if ( $note['note'] ) {
        echo  '<tbody>' ;
        echo  '<tr>' ;
        echo  '<td valign="top">' ;
        echo  '<label style="width:125px;display:block;"> <strong>#' . ($key + 1) . ' </strong> </label>' ;
        echo  '</td>' ;
        echo  '<td>' ;
        echo  '<textarea name="treepress[member][note][' . $key . '][note]">' . $note['note'] . '</textarea>' ;
        echo  '</td>' ;
        echo  '<td valign="top">' ;
        echo  ' <a class="dtbody" href="">' . esc_html__( 'Delete', 'treepress' ) . '</a>' ;
        echo  '</td>' ;
        echo  '</tr>' ;
        echo  '</tbody>' ;
    }

}
echo  '</table>' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '</table>' ;
echo  '<br/>' ;
echo  '<table border="0">' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<table border="0" width="100%">' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<h3 style="margin-top:0;margin-bottom:5px;">' . esc_html__( 'Others', 'treepress' ) . '</h3>' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '</table>' ;
echo  '<table id="table-member-notes" border="0" width="100%">' ;
echo  '</table>' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '</table>' ;
echo  '<div style="display:none;" id="dialog-member-event" title="' . esc_html__( 'Add New Life Event', 'treepress' ) . '">' ;
echo  '<table>' ;
echo  '<tbody>' ;
echo  '<tr class="hide-treepress">' ;
echo  '<td  colspan="2">' ;
echo  '<h4 style="margin-top:0;margin-bottom:5px;"> <b></b> <small> <a class="dtbody" href="">' . esc_html__( 'Delete', 'treepress' ) . '</a></small></h4>' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '<tr class="hide-treepress-on-list">' ;
echo  '<td>' ;
echo  '<label style="width:125px;display:block;"> <strong>' . esc_html__( 'Type', 'treepress' ) . ' </strong> </label>' ;
echo  '</td>' ;
echo  '<td>' ;
echo  '<select class="event_type" name="treepress[member][even][xxx][type]">' ;
foreach ( $le as $ekey => $e ) {
    echo  '<option value="' . $ekey . '"> (' . $ekey . ') ' . ucwords( $e ) . ' </option>' ;
}
echo  '</select>' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<label style="width:125px;display:block;"> <strong>' . esc_html__( 'Date', 'treepress' ) . ' </strong> </label>' ;
echo  '</td>' ;
echo  '<td>' ;
echo  '<input name="treepress[member][even][xxx][date]" type="text" value="">' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '<tr>' ;
echo  '<td>' ;
echo  '<label style="width:125px;display:block;"> <strong>' . esc_html__( 'Place', 'treepress' ) . ' </strong> </label>' ;
echo  '</td>' ;
echo  '<td>' ;
echo  '<input name="treepress[member][even][xxx][plac]" type="text" value="">' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '</tbody>' ;
echo  '</table>' ;
echo  '</div>' ;
echo  '<div style="display:none;" id="dialog-member-note" title="' . esc_html__( 'Add New Note', 'treepress' ) . '">' ;
echo  '<table border="0" width="100%">' ;
echo  '<tbody>' ;
echo  '<tr>' ;
echo  '<td class="hide-treepress" valign="top">#</td>' ;
echo  '<td>' ;
echo  '<textarea name="treepress[member][note][][note]" style="width:100%;"></textarea>' ;
echo  '</td>' ;
echo  '<td class="hide-treepress" valign="top">' ;
echo  '<a class="dtbody" href="">' . esc_html__( 'Delete', 'treepress' ) . '</a>' ;
echo  '</td>' ;
echo  '</tr>' ;
echo  '</tbody>' ;
echo  '</table>' ;
echo  '</div>' ;

if ( defined( 'TP_DEV' ) && TP_DEV ) {
    $met = get_post_meta( $post->ID );
    $met['names'] = get_post_meta( $post->ID, 'names' );
    $met['famc'] = get_post_meta( $post->ID, 'famc' );
    $met['fams'] = get_post_meta( $post->ID, 'fams' );
    $met['even'] = get_post_meta( $post->ID, 'even' );
    echo  '<pre>' ;
    print_r( $met );
    echo  '</pre>' ;
}
