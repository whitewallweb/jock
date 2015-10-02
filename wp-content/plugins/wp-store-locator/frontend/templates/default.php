<?php 
$output = $this->get_custom_css(); 

$show_results_filter = $this->settings['results_dropdown'];
$results_filter_class = ( $show_results_filter ) ? '' : 'wpsl-no-results';

$output .= '<div id="wpsl-wrap">' . "\r\n";
if ( $this->settings['reset_map'] ) {
    $output .= '<div class="wpsl-gmap-wrap">' . "\r\n";
    $output .= '<div id="wpsl-reset-map">Reset</div>' . "\r\n";
    $output .= '<div id="wpsl-gmap"></div>' . "\r\n";
    $output .= '</div>' . "\r\n";
} else {
    $output .= '<div id="wpsl-gmap"></div>' . "\r\n";
}
$output .= '<div class="clearfix">
    <br />
</div>' . "\r\n";
$output .= '<div class="wpsl-search clearfix' . $results_filter_class . '">' . "\r\n";
$output .= '<div id="wpsl-search-wrap">' . "\r\n";
$output .= '<div class="wpsl-input">' . "\r\n";
$output .= '<label for="wpsl-search-input">' . esc_attr( $this->settings['search_label'] ) . '</label>' . "\r\n";
$output .= '<input autocomplete="on" id="wpsl-search-input" type="text" value="" name="wpsl-search-input" style="width:250px !important" />' . "\r\n";
$output .= '</div>' . "\r\n";
$output .= '<div class="wpsl-select-wrap">' . "\r\n";
$output .=      '<div id="wpsl-radius">' . "\r\n";
$output .=          '<label style="width: 110px;" for="wpsl-radius-label">' . esc_attr( $this->settings['radius_label'] ).'</label>' . "\r\n";
$output .=          '<select autocomplete="off" id="wpsl-radius-label" class="wpsl-dropdown" name="wpsl-radius">' . "\r\n";
$output .=              $this->get_dropdown_list( 'search_radius' );
$output .=          '</select>' . "\r\n";
$output .=      '</div>' . "\r\n";

if ( $show_results_filter ) {
    $output .=          '<div id="wpsl-results">' . "\r\n";
    $output .=              '<label for="wpsl-results-label">' . esc_attr( $this->settings['results_label'] ) . '</label>' . "\r\n";
    $output .=              '<select autocomplete="off" id="wpsl-results-label" class="wpsl-dropdown" name="wpsl-results">' . "\r\n";
    $output .=                  $this->get_dropdown_list( 'max_results' );
    $output .=              '</select>' . "\r\n";
    $output .=          '</div>' . "\r\n";
} 

$output .=      '<input id="wpsl-search-btn" class="pull-right btn btn-primary btn-custom" type="submit" style="font-family: bookman;" value='. esc_attr( $this->settings['search_btn_label'] ) . '>' . "\r\n";
$output .=      '<br /><br />';
$output .=      '</div>' . "\r\n";
$output .=   '</div>' . "\r\n";
$output .= '</div>' . "\r\n";
    
$output .= '<div id="wpsl-result-list">' . "\r\n";
$output .=      '<div id="wpsl-stores">' . "\r\n";
$output .=            "\r\n";
$output .=      '</div>' . "\r\n";
$output .=      '<div id="wpsl-direction-details">' . "\r\n";
$output .=          '<ul></ul>' . "\r\n";
$output .=      '</div>' . "\r\n";
$output .= '</div>' . "\r\n";
$output .= '</div>' . "\r\n";


return $output;
