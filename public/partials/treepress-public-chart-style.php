<?php 


$default_chart 				= $this->default_tree_meta();


$saved_chart 				= get_post_meta($post->ID, 'chart', true) ? get_post_meta($post->ID, 'chart', true) : array();
$default 					= empty($saved_chart) ? true : false;
$chart 						= $this->merge_chart($default_chart, $saved_chart, $default);


$image_bg_show 				= isset($chart['image_bg']['show']) ? $chart['image_bg']['show'] : '';
$image_bg_size 				= isset($chart['image_bg']['size']) ? $chart['image_bg']['size'] : '';
$image_bg_repeat 			= isset($chart['image_bg']['repeat']) ? $chart['image_bg']['repeat'] : '';


$box_show = $chart['box']['show'];
$box_border_style = $chart['box']['border']['style'];
$box_border_weight = $chart['box']['border']['weight'];
$box_border_radius = $chart['box']['border']['radius'];
$box_border_color_male = $chart['box']['border']['color']['male'];
$box_border_color_female = $chart['box']['border']['color']['female'];
$box_border_color_other = $chart['box']['border']['color']['other'];
$box_bg_color_male = $chart['box']['bg_color']['male'];
$box_bg_color_female = $chart['box']['bg_color']['female'];
$box_bg_color_other = $chart['box']['bg_color']['other'];

$box_padding 				= $chart['box']['padding'];


$line_show = $chart['line']['show'];
$line_style = $chart['line']['style'];
$line_weight = $chart['line']['weight'];
$line_color = $chart['line']['color'];

$thumb_show = $chart['thumb']['show'];
$thumb_border_style = $chart['thumb']['border_style'];
$thumb_border_weight = $chart['thumb']['border_weight'];
$thumb_border_radius = $chart['thumb']['border_radius'];
$thumb_border_margin = $chart['thumb']['margin'];

$name_font = $chart['name']['font'];
$name_font_size = $chart['name']['font_size'];
$name_font_color = $chart['name']['font_color'];

$name_other_font = $chart['name']['font_other'];
$name_other_font_size = $chart['name']['font_other_size'];
$name_other_font_color = $chart['name']['font_other_color'];

$treepress_rand = 'treepress-rand-'.rand();
$treepress_rand_class = '.'.$treepress_rand;
$chart_type =  $chart['chart_type'];
$group_id =  $chart['group_id'];
$root_id = $chart['root_id'];
$bg_color =  $chart['bg_color'];
$name_format = $chart['name_format'];
$show_gender = $chart['privacy']['show_gender'];
$dob_format = $chart['dob_format'];
$dod_format = $chart['dod_format'];
$dom_format = $chart['dom_format'];
$node_opacity = $chart['node_opacity'];
$node_minimum_width = $chart['node_minimum_width'];
$height_generations = $chart['height_generations'];

$height_generations = (int) filter_var($height_generations, FILTER_SANITIZE_NUMBER_INT);

?>

<style type="text/css">

    <?php echo $treepress_rand_class; ?> .cont_bg {
        background-color: <?php echo $bg_color; ?>!important;
        <?php if($image_bg_show && get_the_post_thumbnail_url($id)){ ?>
        background-size: <?php echo $image_bg_size; ?>!important;
        background-image: url(<?php echo get_the_post_thumbnail_url($id); ?>) !important;
        background-repeat: <?php if($image_bg_repeat) { ?> repeat <?php } else { ?> no-repeat <?php } ?>  !important;
        <?php } ?>
    }

    <?php echo $treepress_rand_class; ?> .tree-default  .person {
        opacity: <?php echo $node_opacity; ?>;
    }

    <?php echo $treepress_rand_class; ?> .tree-default > .tree-descendant li {
        padding-top: <?php echo ($height_generations - 10); ?>px;
    }

    <?php echo $treepress_rand_class; ?> .tree-default > .tree-descendant .spouse>.childs:before,
    <?php echo $treepress_rand_class; ?> .tree-default > .tree-descendant .spouse>.childs>li:not(:first-child):not(:last-child):not(:only-child)>.person:after,
    <?php echo $treepress_rand_class; ?> .tree-default > .tree-descendant .spouse>.childs>li:before {
        height: <?php echo ($height_generations); ?>px;
    }

    <?php echo $treepress_rand_class; ?> .cont_bg .person > div > .person-info {
        min-width: <?php echo $node_minimum_width; ?>;
        overflow: hidden;
    }

    <?php echo $treepress_rand_class; ?> .cont_bg .tree-default.show-spouse .spouse.haschill>.person:after {
        bottom: -<?php echo ((10) + intval($line_weight)); ?>px;
        height: <?php echo ((10) + intval($line_weight)); ?>px;
    }

    <?php if($box_show) { ?>
        <?php echo $treepress_rand_class; ?> .cont_bg .person > div > .person-info {
            border-style: <?php echo $box_border_style; ?> !important;
            border-width: <?php echo $box_border_weight; ?> !important;
            border-radius: <?php echo $box_border_radius; ?> !important;
            border-color: <?php echo $box_border_color_other; ?> !important;
            background-color: <?php echo $box_bg_color_other; ?> !important;
            padding: <?php echo $box_padding; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .person.m >  div >  .person-info{
            border-color: <?php echo $box_border_color_male; ?> !important;
            background-color: <?php echo $box_bg_color_male; ?> !important;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .person.f >  div >  .person-info{
            border-color: <?php echo $box_border_color_female; ?> !important;
            background-color: <?php echo $box_bg_color_female; ?> !important;
        }
    <?php } else { ?>
        <?php echo $treepress_rand_class; ?> .cont_bg .person >  div >  .person-info {
            border-width: 0px !important;
            padding: <?php echo $box_padding; ?>;

        }
        <?php echo $treepress_rand_class; ?> .cont_bg .person>div>div {
            background: transparent !important;
        }
    <?php } ?>

    <?php if($line_show){ ?>

        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-11.tree-descendant .spouse>.childs>li:before {
            border-top: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-11.tree-descendant .spouse>.childs>li:last-child:not(:only-child):before {
            border-top: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
            border-right: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-11.tree-descendant .spouse>.childs>li:first-child:not(:only-child):before {
            border-top: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-11.tree-descendant .spouse>.childs:before {
            border-left: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-11.tree-descendant .spouse>.childs>li:not(:first-child):not(:last-child):not(:only-child)>.person:after {
            border-left: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default.tree-default-left > .chart-type-11.tree-descendant .childs>li>div.spouse.haschill>.person:after {
            border-right: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default:not(.tree-default-left) > .chart-type-11.tree-descendant .spouse>.childs:before {
            border-top: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default:not(.tree-default-left) > .chart-type-11.tree-descendant .spouse.haschill>.person:after {
            border-left: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
      

        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-12.tree-pedigree .childs>li:first-child:before {
            border-left: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-12.tree-pedigree .childs:after {
            border-left: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-12.tree-pedigree .childs>li:before {
            border-top: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-12.tree-pedigree .childs>li:last-child:not(:only-child):before {
            border-right: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }

        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-13.tree-pedigree .chart-type-13up .childs>li:first-child:before {
            border-left: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-13.tree-pedigree .chart-type-13up .childs:after {
            border-left: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-13.tree-pedigree .chart-type-13up .childs>li:before {
            border-top: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-13.tree-pedigree .chart-type-13up .childs>li:last-child:not(:only-child):before {
            border-right: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-13.tree-pedigree .chart-type-13down .childs>li:first-child:before {
            border-left: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-13.tree-pedigree .chart-type-13down .childs:after {
            border-left: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-13.tree-pedigree .chart-type-13down .childs>li:before {
            border-top: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-13.tree-pedigree .chart-type-13down .childs>li:last-child:not(:only-child):before {
            border-right: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }


        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-14.tree-descendant .spouse>.childs>li:before {
            border-top: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-14.tree-descendant .spouse>.childs>li:last-child:not(:only-child):before {
            border-top: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
            border-right: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-14.tree-descendant .spouse>.childs>li:first-child:not(:only-child):before {
            border-top: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-14.tree-descendant .spouse>.childs:before {
            border-left: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-14.tree-descendant .spouse>.childs>li:not(:first-child):not(:last-child):not(:only-child)>.person:after {
            border-left: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default.tree-default-left > .chart-type-14.tree-descendant .childs>li>div.spouse.haschill>.person:after {
            border-right: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default:not(.tree-default-left) > .chart-type-14.tree-descendant .spouse>.childs:before {
            border-top: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default:not(.tree-default-left) > .chart-type-14.tree-descendant .spouse.haschill>.person:after {
            border-left: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg  .tree-default > .chart-type-14.tree-descendant > .childs:before {
            border-top: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
            border-left: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }

        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-14.tree-pedigree .chart-type-14up .childs>li:first-child:before {
            border-left: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-14.tree-pedigree .chart-type-14up .childs:after {
            border-left: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-14.tree-pedigree .chart-type-14up .childs>li:before {
            border-top: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-14.tree-pedigree .chart-type-14up .childs>li:last-child:not(:only-child):before {
            border-right: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .tree-default > .chart-type-14.tree-pedigree .chart-type-14up.childs:after {
            border-left: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }

        .chart-type-21 li:before, 
        .chart-type-21 li:after {
            border-top: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        .chart-type-21 ul ul:before {
            border-left: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        .chart-type-21 li:last-child:before {
            border-right: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        .chart-type-21 li:after {
            border-left: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
        }
        .chart-type-21 .childs>li:only-child>.person:after {
            border-right: <?php echo $line_weight; ?>  <?php echo $line_style; ?> <?php echo $line_color; ?>;
            background: transparent;
        }





    <?php } else { ?>

    <?php } ?>

    <?php if($thumb_show){ ?>
        <?php echo $treepress_rand_class; ?> .cont_bg .thumb {
            position: relative;
            border-style: <?php echo $thumb_border_style; ?> !important;
            border-width: <?php echo $thumb_border_weight; ?> !important;
            border-radius: <?php echo $thumb_border_radius; ?> !important;
            margin: <?php echo $thumb_border_margin; ?> !important;
            
        }
    <?php } else { ?>
        <?php echo $treepress_rand_class; ?> .cont_bg .thumb {
            position: relative;
            border-width: 0px !important;
        }
    <?php } ?>

        <?php echo $treepress_rand_class; ?> .cont_bg .text-name {
            font-family: <?php echo $name_font; ?> !important;
            font-size: <?php echo $name_font_size; ?> !important;
            color: <?php echo $name_font_color; ?> !important;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .text-other {
            font-family: <?php echo $name_other_font; ?> !important;
            font-size: <?php echo $name_other_font_size; ?> !important;
            color: <?php echo $name_other_font_color; ?> !important;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .text-name-full {
            display: none;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .text-name-first {
            display: none;
        }
        .cont_bg .dob-full {
            display: none; 
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .dob-only-year {
            display: none;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .dod-full {
            display: none;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg .dod-only-year {
            display: none;
        }

    <?php  if($name_format=='full'){ ?>
        <?php echo $treepress_rand_class; ?> .cont_bg .text-name-full {
            display: inline-block;
        }
    <?php  } else { ?>
        <?php echo $treepress_rand_class; ?> .cont_bg .text-name-first {
            display: inline-block;
        }
    <?php } ?>

    <?php  if($show_gender=='on'){ ?>
        /*
        <?php echo $treepress_rand_class; ?> .cont_bg .text-gender {
            display: inline-block';
        }';
        */
    <?php } else { ?>
        <?php echo $treepress_rand_class; ?> .cont_bg .text-gender {
            display: none;
        }
    <?php } ?>

    <?php  if($dob_format=='full'){  ?>
        <?php echo $treepress_rand_class; ?> .cont_bg .dob-full {
            display: inline-block;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg div.dob-full {
            display: block;
        }
    <?php } else { ?>
        <?php echo $treepress_rand_class; ?> .cont_bg .dob-only-year {
            display: inline-block;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg div.dob-only-year {
            display: block; 
        }
    <?php } ?>

    <?php if($dod_format=='full'){  ?>
        <?php echo $treepress_rand_class; ?> .cont_bg .dod-full {
            display: inline-block;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg div.dod-full {
            display: block; 
        }
    <?php } else { ?>
        <?php echo $treepress_rand_class; ?> .cont_bg .dod-only-year {
            display: inline-block;
        }
        <?php echo $treepress_rand_class; ?> .cont_bg div.dod-only-year {
            display: block;
        }
    <?php } ?>

        <?php echo $treepress_rand_class; ?> .cont_bg .ttext-cont {
            display: none !important;
        }
</style>