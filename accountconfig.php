<?php

function SUPBINE_account_config() {
	$default_supbine_companyId = null;
	$defualt_supbine_locale = "en_US";

	if ($_GET["action"] == "deactivate") {
		update_option('supbine_companyId', $default_supbine_companyId);
		update_option('supbine_locale', $defualt_supbine_locale);
	}

	if ($_GET["action"] == "update") {
		if (!isset($_POST['SUPBINE_wpnonce']) ||
			!wp_verify_nonce($_POST['SUPBINE_wpnonce'], 'SUPBINE_update_action')){
			print 'Sorry, your nonce did not verify.';
			exit;
		} else {
			$safe_supbine_companyId = intval($_POST["supbine_companyId"]);
			$safe_supbine_locale = sanitize_text_field($_POST["supbine_locale"]);

			if (!$safe_supbine_companyId) {
				$safe_supbine_companyId = $default_supbine_companyId;
			}

			update_option('supbine_companyId', $safe_supbine_companyId);
			update_option('supbine_locale', $safe_supbine_locale);
		}
	}

	$companyId = get_option('supbine_companyId', $default_supbine_companyId);
	$locale = get_option('supbine_locale', $defualt_supbine_locale);

	?>
	<div class="wrap">
		<h2>Supbine Widget</h2>
		<span>The following is the documentation for usage of Supbine's web widget or Widget for short.</span>
	<?php

	?>
		<div class="existingform">
			<div class="metabox-holder">
				<div class="postbox">
					<h3 class="hndle"><span>Link up with your Supbine company</span></h3>
					<div class="supbine-login-form">
						<?php
							if ($companyId != null){
								?>
								<div class="supbine-success">
									<p><strong>Success!</strong> You are currently using company id: <?php echo esc_textarea($companyId); ?> with language: <?php echo esc_textarea($locale); ?></p>
									To deactivate the widget, click <a href="admin.php?page=supbine&action=deactivate">here</a>.
								</div>
								<?php
							}else {
								?>
								<div class="supbine-help">
									<p><strong>Can't find your company id?</strong> Your company id can be found <a href="https://app.supbine.com/#/company-settings/" target="_blank">here</a>.</p>
								</div>
								<?php
							}
						?>

						<form method="post" action="admin.php?page=supbine&action=update">
							<?php wp_nonce_field('SUPBINE_update_action', 'SUPBINE_wpnonce'); ?>

							<table class="form-table">
								<tbody>
									<tr valign="top">
										<th scope="row">Company id</th>
										<td>
											<input type="number" name="supbine_companyId">
										</td>
									</tr>

									<tr valign="top">
										<th scope="row">Language</th>
										<td>
											<select name="supbine_locale">
												<option value="en_US">English</option>
												<option value="da_DK">Danish</option>
											</select>
										</td>
									</tr>
								</tbody>
							</table>

							<p class="submit">
								<button type="submit" class="button-primary">Update</button>
								Don't have a Supbine account? <a href="https://app.supbine.com/#/register" target="_blank">Sign up now</a>.
							</p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
}