<?php
class ModelModuleWhathappen extends Model{
	public function install() {

		$this->db->query("
				CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "whathappen` (
					`id` INT(11) NOT NULL AUTO_INCREMENT,
					`name` VARCHAR(100) NOT NULL,
					`last_name` VARCHAR(100) NOT NULL,
					`url` TEXT NOT NULL,
					`product` TEXT NOT NULL,
					`status` INT(11) NOT NULL,
					`order_id` INT(11) NOT NULL,
					`date_added` VARCHAR(255) NOT NULL,
					
					PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");

		
		// register the event triggers
		// admin events
		$this->model_extension_event->addEvent('whathappen_admin_product_add', 'post.admin.product.add', 'module/whathappen/eventAddProduct');
		$this->model_extension_event->addEvent('whathappen_admin_product_edit', 'pre.admin.product.edit', 'module/whathappen/eventEditProduct');
		$this->model_extension_event->addEvent('whathappen_admin_review_add', 'pre.admin.review.add', 'module/whathappen/eventAddReview');
		$this->model_extension_event->addEvent('whathappen_admin_coupon_add', 'pre.admin.coupon.add', 'module/whathappen/eventAddCoupon');
		$this->model_extension_event->addEvent('whathappen_admin_coupon_add', 'post.admin.coupon.delete', 'module/whathappen/eventDeleteCoupon');
		
		// catalog events
		$this->model_extension_event->addEvent('whathappen_catalog_customer_add', 'pre.customer.add', 'module/whathappen/eventAddCustomer');
		$this->model_extension_event->addEvent('whathappen_catalog_review_add', 'pre.review.add', 'module/whathappen/eventAddReview');
		$this->model_extension_event->addEvent('whathappen_catalog_order_delete', 'pre.order.delete', 'module/whathappen/eventDeleteOrder');
		$this->model_extension_event->addEvent('whathappen_catalog_order_history', 'post.order.history.add', 'module/whathappen/eventHistoryOrder');
		
	}

	public function uninstall() {
		// remove the event triggers
		// admin events
		$this->model_extension_event->deleteEvent('whathappen_admin_product_add');
		$this->model_extension_event->deleteEvent('whathappen_admin_product_edit');
		$this->model_extension_event->deleteEvent('whathappen_admin_review_add');
		$this->model_extension_event->deleteEvent('whathappen_admin_coupon_add');
		
		// catalog events
		$this->model_extension_event->deleteEvent('whathappen_catalog_customer_add');
		$this->model_extension_event->deleteEvent('whathappen_catalog_review_add');
		$this->model_extension_event->deleteEvent('whathappen_catalog_order_add');
		$this->model_extension_event->deleteEvent('whathappen_catalog_order_edit');
		$this->model_extension_event->deleteEvent('whathappen_catalog_order_delete');
		$this->model_extension_event->deleteEvent('whathappen_catalog_order_history');
		$this->db->query("DROP TABLE `" . DB_PREFIX . "whathappen`;");
	}

}