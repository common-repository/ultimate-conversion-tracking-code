<?php
/*
Plugin Name: Ultimate Conversion Tracking Code
Description: Add any marketing script to Woocomerce and Wordpress
Plugin URI: http://www.ultimateconversiontrackingcode.com/
Author: Alejandro Perez
Author URI: http://www.ultimateconversiontrackingcode.com/
Version: 1.0.0
License: GPL2

*/

/*

Copyright (C) 2015 Alejandro Perez alx.anthony@gmail.com

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
//add_action( 'init', 'uctc_integration' );

add_action( 'admin_enqueue_scripts', 'uctc_load_scripts' );

add_action('admin_menu', 'uctc_menu');

add_action( 'admin_init', 'uctc_settings' );

add_action( 'wp_ajax_uctcgetdata', 'uctc_getdata');

add_action( 'wp_ajax_uctcsavedata', 'uctc_savedata' );

add_action('wp_print_footer_scripts', 'uctc_add_all_pages_scripts');

add_action('woocommerce_after_single_product', 'uctc_add_products_scripts');

add_action('woocommerce_after_cart_totals', 'uctc_add_cart_scripts');

//add_action('woocommerce_checkout_after_order_review', 'uctc_add_order_complete_scripts');

add_action('woocommerce_thankyou', 'uctc_add_thankyou_scripts');

function uctc_add_all_pages_scripts() {
	$variables	= get_option('uctc_variables');
	$var 		= json_decode($variables);
	if(!isset($var->uctc_variables)){
		$var->uctc_variables	= array();
	}
	?>
	<script type="text/javascript">
		jQuery(function() {
			var uctc_obj = <?php echo json_encode($var); ?>;
			var uctcVariables = uctc_obj.uctc_variables;

			if (typeof uctc_obj.all_pages !== "undefined") {
				var scripts = uctc_obj.all_pages;

				for (index = 0; index < scripts.length; ++index) {
					var script = scripts[index].script;

					for (i = 0; i < uctcVariables.length; ++i) {

						var re = new RegExp("{" + uctcVariables[i].name + "}", "g");

						script = script.replace(re, uctcVariables[i].value);
					}

					if( scripts[index].location == 1 ){
						jQuery('head').append(script);
					}
					if( scripts[index].location == 2 ){
						jQuery('body').append(script);
					}
					if( scripts[index].location == 3 ){
						jQuery('body').prepend(script);
					}
				}
			}
		});
	</script>
	<?php
}

function uctc_add_products_scripts(){
	global $product;
	$category	= '';
	$terms 		= get_the_terms( $product->id, 'product_cat' );
	
	foreach ($terms as $term) {
		$category = $term->name;
		break;
	}

	$variables	= get_option('uctc_variables');
	$var 		= json_decode($variables);
	if(!isset($var->uctc_variables)){
		$var->uctc_variables	= array();
	}
	$var->uctc_variables[]	= array(
		'name'	=> 'product_sku',
		'value'	=> $product->sku
	);

	$var->uctc_variables[]	= array(
		'name'	=> 'product_id',
		'value'	=> $product->id
	);

	$var->uctc_variables[]	= array(
		'name'	=> 'product_name',
		'value'	=> $product->post->post_title
	);

	$var->uctc_variables[]	= array(
		'name'	=> 'product_category',
		'value'	=> $category
	);
	?>

	<script type="text/javascript">
		jQuery(function(){
			var uctc_obj	 	= <?php echo json_encode($var); ?>;
			var uctcVariables	= uctc_obj.uctc_variables;

			if (typeof uctc_obj.products !== "undefined") {
				var scripts = uctc_obj.products;
				for (index = 0; index < scripts.length; ++index) {
					var script = scripts[index].script;

					for (i = 0; i < uctcVariables.length; ++i) {

						var re = new RegExp("{" + uctcVariables[i].name + "}", "g");

						script = script.replace(re, uctcVariables[i].value);
					}

					if( scripts[index].location == 1 ){
						jQuery('head').append(script);
					}
					if( scripts[index].location == 2 ){
						jQuery('body').append(script);
					}
					if( scripts[index].location == 3 ){
						jQuery('body').prepend(script);
					}
				}
			}
		});
	</script>
	<?php
}

function uctc_add_thankyou_scripts(){
	$variables	= get_option('uctc_variables');
	$var 		= json_decode($variables);
	if(!isset($var->uctc_variables)){
		$var->uctc_variables	= array();
	}
	?>
	<script type="text/javascript">
		jQuery(function() {
			var uctc_obj = <?php echo json_encode($var); ?>;
			var uctcVariables = uctc_obj.uctc_variables;

			if (typeof uctc_obj.thankyou_page !== "undefined") {
				var scripts = uctc_obj.thankyou_page;
				for (index = 0; index < scripts.length; ++index) {
					var script = scripts[index].script;

					for (i = 0; i < uctcVariables.length; ++i) {

						var re = new RegExp("{" + uctcVariables[i].name + "}", "g");

						script = script.replace(re, uctcVariables[i].value);
					}

					if( scripts[index].location == 1 ){
						jQuery('head').append(script);
					}
					if( scripts[index].location == 2 ){
						jQuery('body').append(script);
					}
					if( scripts[index].location == 3 ){
						jQuery('body').prepend(script);
					}
				}
			}
		});
	</script>
	<?php
}

function uctc_add_cart_scripts(){
	$variables	= get_option('uctc_variables');
	$var 		= json_decode($variables);
	if(!isset($var->uctc_variables)){
		$var->uctc_variables	= array();
	}
	?>
	<script type="text/javascript">
		jQuery(function(){
			var uctc_obj	 	= <?php echo json_encode($var); ?>;
			var uctcVariables	= uctc_obj.uctc_variables;

			if (typeof uctc_obj.cart_page !== "undefined") {
				var scripts = uctc_obj.cart_page;

				for (index = 0; index < scripts.length; ++index) {
					var script = scripts[index].script;

					for (i = 0; i < uctcVariables.length; ++i) {

						var re = new RegExp("{" + uctcVariables[i].name + "}", "g");

						script = script.replace(re, uctcVariables[i].value);
					}

					if( scripts[index].location == 1 ){
						jQuery('head').append(script);
					}
					if( scripts[index].location == 2 ){
						jQuery('body').append(script);
					}
					if( scripts[index].location == 3 ){
						jQuery('body').prepend(script);
					}
				}
			}
		});
	</script>
	<?php
}

function uctc_getdata(){
	$variables	=  get_option( 'uctc_variables' );
	if($variables){
		echo $variables;
	}else{
		echo '{"uctc_variables":[]}';
	}

	wp_die();
}

function uctc_savedata(){
	$action = file_get_contents("php://input");
	update_option( 'uctc_variables', $action );
	wp_die();
}

function uctc_settings() {
	register_setting( 'uctc-settings', 'uctc_variables' );
}
function uctc_load_scripts(){
	wp_enqueue_style( 'uctc_animodal', plugins_url( '/includes/modal/animate.min.css', __FILE__ ));
	wp_enqueue_script( 'angular', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.min.js' );
	wp_enqueue_script( 'uctc_admin', plugins_url( '/includes/script.js', __FILE__ ), array('angular') );
	wp_enqueue_script( 'uctc_animodal', plugins_url( '/includes/modal/animatedModal.js', __FILE__ ), array('angular') );
}

function uctc_menu() {
	add_menu_page('Ultimate Conversion Tracking', 'Ultimate Conv-Track Code', 'administrator', 'uctc-settings', 'uctc_settings_page', 'dashicons-admin-generic');
}

function uctc_settings_page() {?>
	<div class="wrap" ng-app="uctcApp">
		<div ng-controller="indexController">
		<h1>Ultimate Conversion Tracking Scripts Settings</h1>
			<table class="wp-list-table widefat fixed striped pages">
				<thead>
					<tr valign="top">
						<th scope="row" colspan="2"><button class="button-primary" ng-click="save();">Save All Scripts</button></th>
					</tr>
					<tr>
						<th>Name</th>
						<th>Data</th>
					</tr>
				</thead>
				<tr valign="top">
					<th scope="row">
						Custom variables
						<div class="row-actions">
							<span class="edit"  ng-click="addVariable();"><a href="#">Add</a> </span>
						</div>
					</th>
					<td>
						<div ng-repeat="variable in data.uctc_variables">
							<input type="text" placeholder="Name" ng-model="variable.name"/>
							<input type="text" ng-model="variable.value" placeholder="value" />
							<span class="v-trash" ng-click="remove(data.uctc_variables, $index)">Remove</span>
						</div>
				</tr>
				<tr valign="top">
					<th scope="row">
						All pages
						<div class="row-actions">
							<span class="edit"  ng-click="addScript('all_pages')"><a href="#">Add script</a> </span>
						</div>
					</th>
					<td>
						<div ng-repeat="script in data.all_pages">
							<span>{{script.name}}</span>
							<div class="row-actions script-row">
								<span class="edit" ng-click="editScript(script, $index)"><a href="#">Edit</a> | </span>
								<span class="trash" ng-click="remove(data.all_pages, $index)"><a >Remove</a>  </span>
							</div>

						</div>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						Product page
						<div class="row-actions">
							<span class="edit"  ng-click="addScript('products')"><a href="#">Add script</a> </span>
						</div>
					</th>
					<td>
						<div ng-repeat="script in data.products">
							<span>{{script.name}}</span>
							<div class="row-actions script-row">
								<span class="edit" ng-click="editScript(script, $index)"><a href="#">Edit</a> | </span>
								<span class="trash" ng-click="remove(data.products, $index)"><a >Remove</a>  </span>
							</div>

						</div>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						Thank you page
						<div class="row-actions">
							<span class="edit"  ng-click="addScript('thankyou_page')"><a href="#">Add script</a> </span>
						</div>
					</th>
					<td>
						<div ng-repeat="script in data.thankyou_page">
							<span>{{script.name}}</span>
							<div class="row-actions script-row">
								<span class="edit" ng-click="editScript(script, $index)"><a href="#">Edit</a> | </span>
								<span class="trash" ng-click="remove(data.thankyou_page, $index)"><a >Remove</a>  </span>
							</div>

						</div>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						Cart page
						<div class="row-actions">
							<span class="edit"  ng-click="addScript('cart_page')"><a href="#">Add script</a> </span>
						</div>
					</th>
					<td>
						<div ng-repeat="script in data.cart_page">
							<span>{{script.name}}</span>
							<div class="row-actions script-row">
								<span class="edit" ng-click="editScript(script, $index)"><a href="#">Edit</a> | </span>
								<span class="trash" ng-click="remove(data.cart_page, $index)"><a >Remove</a>  </span>
							</div>

						</div>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row" colspan="2"><button class="button-primary" ng-click="save();">Save All Scripts</button></th>
				</tr>
			</table>

	<style>
		.script-row{
			margin-bottom:10px;
		}
		.modal-content{
			width:750px;
			margin: 100px auto;
			min-height:500px;
			background:white;
			padding:30px 30px 10px 30px;
		}
		.modal-content table td{
			vertical-align:top;
			padding:10px;
		}
		.modal-content table{
			width:100%;
		}
		.modal-content textarea{
			width:100%;
			height:300px;
		}
		li{
			border:1px solid #eee;
			margin-bottom:5px;
		}
		.v-trash{
			color:#a00;
		}
		#uctc-modal{
			opacity:0;
		}
		.close-animatedModal{
			text-align:right;
		}
		.bottom-buttons{
			text-align:right;
		}
		.available-variables{
			height:200px;
			overflow:auto;
		}
	</style>
	<!--Call your modal-->
	<a id="uctc-modal" href="#animatedModal">DEMO01</a>
	<!--DEMO01-->
	<div id="animatedModal">
		<!--THIS IS IMPORTANT! to close the modal, the class name has to match the name given on the ID  class="close-animatedModal" -->
		<div class="modal-content">
			<div class="close-animatedModal">
				<a class="welcome-panel-close" href="#">Dismiss</a>
			</div>

			<div>
				<table>
					<tr>
						<td>
							<h3>Place here your script</h3>
							<label>Script Name:</label> <input type="text" ng-model="currentScript.name" />
							<hr />
							<label>Script:</label>
							<p>
								<small>Place here your script and if you want to use variables put them inside brackets ex: {pixel_id}</small>
							</p>
							<textarea ng-model="currentScript.script"></textarea>
						</td>
						<td>
							<h3>Available variables</h3>

							<div class="available-variables">
								<ul>
									<li ng-repeat="variable in availableVariables">
										{{variable.name}} = {{variable.value}}
									</li>
								</ul>
							</div>
							<hr />

							<h3>Script Location</h3>
							<select ng-model="currentScript.location" >
								<option value="1">Before  &lt;/head&gt;</option>
								<option value="2">Before  &lt;/body&gt;</option>
								<option value="3">After  &lt;body&gt;</option>
							</select>

						</td>
					</tr>
					<tr valign="top">
						<th scope="row" colspan="2">
							<div class="bottom-buttons">
								<button class="button-primary" ng-click="saveScript();">Save</button>
								<button class="button-primary" ng-click="cancelScript();">Cancel</button>
							</div>
						</th>
					</tr>
				</table>

			</div>
		</div>
	</div>
	<script>
		var siteUrl	= "<?php echo get_site_url(); ?>";
		jQuery("#uctc-modal").animatedModal({color:'#00a0d2'});
	</script>
	</div>
	</div>
<?php

}

