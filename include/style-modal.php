<?php
/**
 * Css para modal y datatable.
 *
 * @package Videos_Chapters_Logos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<style>  

.modal {

	display: none;

	position: fixed;

	z-index: 100000;

	left: 0;

	top: 0;

	width: 100%;

	height: 100%;

	background-color: rgba(0, 0, 0, 0.5);

	justify-content: center;

	align-items: center;

}

.modal-content {

	background-color: #fff;

	padding: 20px;

	border-radius: 8px;

	width: 500px;

	max-width: 90%;

	max-height: 90vh;

	overflow-y: auto;

	box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);

	position: relative;

}

.close-btn {

	position: absolute;

	top: 10px;

	right: 15px;

	font-size: 24px;

	cursor: pointer;

	color: #555;

}

.close-btn:hover {

	color: #000;

}

.vidchlog-marker-update {

	display: flex;

	gap: 10px;

	align-items: center;

	margin-bottom: 10px;

}

.vidchlog-marker-update input[type="text"]:first-child {

	width: 80px;

}

	div.dt-container select.dt-input {
		width: 45px;
}


</style>
