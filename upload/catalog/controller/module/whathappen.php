<?php
class ControllerModuleWhathappen extends Controller {
	public function index($setting) {
		$this->load->model('module/whathappen');
		$this->load->language('module/whathappen');
		$data['heading_title'] = $this->language->get('heading_title');
		//$this->document->addStyle('catalog/view/javascript/jquery/');
		//$this->document->addScript('catalog/view/javascript/jquery/');
		$status_array=array($this->language->get('event_register'),
		$this->language->get('event_instock'),
		$this->language->get('event_special'),
		$this->language->get('event_discount'),
		$this->language->get('event_order_sent'),
		$this->language->get('event_coupon'),
		$this->language->get('event_order_add'),
		$this->language->get('event_review')
		);
		
		$data['namelength'] =$setting['whathappen_namelength'];
		$data['whathappens'] = array();
		$whathappen_limit=(int)$setting['whathappen_limit'];
		$results = $this->model_module_whathappen->getWhatHappen($whathappen_limit);
		foreach ($results as $result) {
			$status=$result['status'];
			$data['whathappens'][] = array(
			'name'	     => $result['name'],
			'last_name'  => $result['last_name'],
			'url'    	 => $result['url']? $this->url->link('product/product', 'product_id=' . $result['url']):'',
			'product'    => $result['product'],
			'status'   	 => $status,
			'status_text' => $status_array[$status-1],
			'order_id'    => $result['order_id'],
			'date_added'  => $result['date_added'],
			'time_text'	=> $this->post_time($result['date_added'], $setting['whathappen_datetime'])
			);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/whathappen.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/whathappen.tpl', $data);
		} else {
			return $this->load->view('default/template/module/whathappen.tpl', $data);
		}
	}
	
	public function post_time($date_added, $datetime) {
		$this->load->language('module/whathappen');
		$offset = 10800;//saat doğru görünmüyorsa bu değeri 3600 ve katları olacak şekilde değiştirin
		if ($datetime==0){
			$result="";
		}
		if ($datetime==1) {
			$just_now=time();
			$period=$just_now-$date_added;
			if($period<0) $result="";
			if($period<60)$result= round($period).$this->language->get('second_ago');
			else if($period<3600) $result=round($period/60).$this->language->get('minute_ago');
			else if($period<86400) $result= round($period/3600).$this->language->get('hour_ago');
			else if($period<86400*7) $result= round($period/(86400)).$this->language->get('day_ago');
			else if($period<86400*30) $result= round($period/(86400*7)).$this->language->get('week_ago');
			else if($period<86400*365) $result= round($period/(86400*30)).$this->language->get('month_ago');
			else  $result = round($period/(86400*365)).$this->language->get('year_ago');
		}
		if ($datetime==2){
			$result=$this->language->get('datetime_time'). gmdate("H:i", $date_added+$offset). $this->language->get('datetime_date'). gmdate("d.m.Y",$date_added+$offset);
		}
		if ($datetime==3) {
			
			$result=$this->language->get('datetime_time'). gmdate("H:i", $date_added+$offset);
		}
		return $result;
	}
	public function eventAddCustomer($data){
		//1=>"... kayıt oldu", {name, last_name, date_added, status=1}		
		$this->db->query("INSERT INTO " . DB_PREFIX . "whathappen SET name = '" . $this->db->escape($data['firstname']) . "',last_name='" . $this->db->escape($data['lastname']) . "', url = '',product = '', status = '1', order_id='', date_added = UNIX_TIMESTAMP()");
		
	}
	public function eventHistoryOrder($order_id){
		$this->load->model('checkout/order');
		//$this->log->write($data['firstname']);
		//$this->log->write($data['lastname']);
		//$this->log->write($data['order_id']);
		$data=$this->model_checkout_order->getOrder($order_id);
		$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		foreach ($order_product_query->rows as $product) {
			
			if ($data['order_status_id']==1||$data['order_status_id']==2) {
				//"Pending" or "Processing", should check from database if this order_status_id entries right or not
				//7=>"... siparişi verdi" {name, last_name, order_id, url, product, date_added, status=7}		
				$this->db->query("INSERT INTO " . DB_PREFIX . "whathappen SET name = '" . $this->db->escape($data['firstname']) . "',last_name='" . $this->db->escape($data['lastname']) . "', url = '" . $this->db->escape($product['product_id']) . "',product = '" . $this->db->escape($product['name']) . "', status = '7', order_id='', date_added = UNIX_TIMESTAMP()");
			}
			if ($data['order_status_id']==3||$data['order_status_id']==5) {
				//"Shipped" or "Complete", should check from database if this order_status_id entries right or not
				//5=>"... siparişi tamamlandı", {name, last_name, order_id, url, product, date_added, status=5}
				$this->db->query("INSERT INTO " . DB_PREFIX . "whathappen SET name = '" . $this->db->escape($data['firstname']) . "',last_name='" . $this->db->escape($data['lastname']) . "', url = '" . $this->db->escape($product['product_id']) . "',product = '" . $this->db->escape($product['name']) . "', status = '5', order_id='', date_added = UNIX_TIMESTAMP()");
			}
			
		}
		
	}
	public function eventAddReview($data){
		
		//8=>"... yorum yaptı" {name, last_name, url, product, date_added, status=8}
		$product_id = $this->request->get['product_id'];
		$name=$data['name'];
		$position=strpos($name, " ");
		if ($position===false) {
			$last_name=$name;
		} else {
			$author=explode(" ", $name);
			$name=$author[0];
			$last_name=$author[1];
		}
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id='" . $this->db->escape($product_id) . "' AND language_id='1';");
		$product_name=$query->row['name'];
		$this->db->query("INSERT INTO " . DB_PREFIX . "whathappen SET name = '" . $this->db->escape($name) . "',last_name='" . $this->db->escape($last_name) . "', url = '" . $this->db->escape($product_id) . "',product = '" . $this->db->escape($product_name) . "', status = '8', order_id='', date_added = UNIX_TIMESTAMP()");
		
	}
	
}