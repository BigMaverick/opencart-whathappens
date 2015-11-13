<?php
class ControllerModuleWhathappen extends Controller {
	private $error = array();
	/* TODO make this more convenient: status list ,!idea: admin side add event for what happen with editable sections.
	
	status=>"description", {fields have to fill before add database}
	
	OK 1=>"... kayıt oldu", {name, last_name, date_added, status=1}-->added
	OK 2=>"... satılmaya başlandı", {url, product, date_added, status=2}-->added
	OK 3=>"... kampanyası başladı", {url, product, date_added, status=3}-->added
	OK 4=>"... indirime girdi", {url, product, date_added, status=4}-->added
	OK 5=>"... siparişi tamamlandı", {name, last_name, order_id, url, product, date_added, status=5}-->added
	6=>"... kuponunu kaçırmayın", {name, last_name="COUPON_coupon_id", date_added="coupon_expire_date", status=6}
	OK 7=>"... siparişi verdi" {name, last_name, order_id, url, product, date_added, status=7}-->added
	OK 8=>"... yorum yaptı" {name, last_name, url, product, date_added, status=8}-->added
	
	*/
	public function install() {
		$this->load->language('module/whathappen');
		$this->load->model('module/whathappen');
		$this->load->model('setting/setting');
		$this->load->model('extension/extension');
		$this->load->model('extension/event');
		$this->model_module_whathappen->install();
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'module/whathappen');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'module/whathappen');
	}
	public function uninstall() {
		$this->load->model('module/whathappen');
		$this->load->model('setting/setting');
		$this->load->model('extension/extension');
		$this->load->model('extension/event');

		$this->model_module_whathappen->uninstall();
		$this->model_setting_setting->deleteSetting($this->request->get['extension']);
	}

	public function index() {
		$this->load->language('module/whathappen');

		$this->document->setTitle($this->language->get('heading_title'));

		//$this->load->model('setting/setting');
		$this->load->model('extension/module');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('whathappen', $this->request->post);
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}
			//$this->model_setting_setting->editSetting('whathappen', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_namelength'] = $this->language->get('entry_namelength');
		$data['entry_ajax'] = $this->language->get('entry_ajax');		
		$data['entry_datetime'] = $this->language->get('entry_datetime');
		$data['entry_datetime_no'] = $this->language->get('entry_datetime_no');
		$data['entry_datetime_time'] = $this->language->get('entry_datetime_time');
		$data['entry_datetime_date'] = $this->language->get('entry_datetime_date');
		$data['entry_datetime_circa'] = $this->language->get('entry_datetime_circa');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
		'text' => $this->language->get('text_home'),
		'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
		'text' => $this->language->get('text_module'),
		'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/whathappen', 'token=' . $this->session->data['token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/whathappen', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);			
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('module/whathappen', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('module/whathappen', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}
		
		if (isset($this->request->post['whathappen_datetime'])) {
			$data['whathappen_datetime'] = $this->request->post['whathappen_datetime'];
		} elseif (!empty($module_info)) {
			$data['whathappen_datetime'] = $module_info['whathappen_datetime'];
		} else {
			$data['whathappen_datetime'] ='';
		}
		
		if (isset($this->request->post['whathappen_limit'])) {
			$data['whathappen_limit'] = $this->request->post['whathappen_limit'];
		} elseif (!empty($module_info)) {
			$data['whathappen_limit'] = $module_info['whathappen_limit'];
		} else {
			$data['whathappen_limit'] = '';
		}
		
		if (isset($this->request->post['whathappen_namelength'])) {
			$data['whathappen_namelength'] = $this->request->post['whathappen_namelength'];
		} elseif (!empty($module_info)) {
			$data['whathappen_namelength'] = $module_info['whathappen_namelength'];
		} else {
			$data['whathappen_namelength'] = '';
		}
		
		if (isset($this->request->post['whathappen_ajax'])) {
			$data['whathappen_ajax'] = $this->request->post['whathappen_ajax'];
		} elseif (!empty($module_info)) {
			$data['whathappen_ajax'] = $module_info['whathappen_ajax'];
		} else {
			$data['whathappen_ajax'] = '';
		}
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}
		
		
		
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/whathappen.tpl', $data));
	}
	public function eventAddProduct($product_id){
		//2=>"... satılmaya başlandı", {url, product, date_added, status=2}
		$product = $this->db->query("SELECT * FROM " . DB_PREFIX . "whathappen WHERE url = '" . $this->db->escape($product_id) . "'");
		if (!isset($product->row['url'])) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id='" . $this->db->escape($product_id) . "' AND language_id='1';");
			$product_name=$query->row['name'];
		$this->db->query("INSERT INTO " . DB_PREFIX . "whathappen SET name = '',last_name='', url = '" . $this->db->escape($product_id) . "',product = '" . $this->db->escape($product_name) . "', status = '2', order_id='', date_added = UNIX_TIMESTAMP()"); }
		
	}
	
	public function eventEditProduct($data){
		$language_id = 1;
		$product_id = $this->request->get['product_id'];
		$product_name=$data['product_description'][$language_id]['name'];
		$today=date("Y-m-d");
			/*	3=>"... kampanyası başladı", {url, product, date_added, status=3}*/
			if (!empty($data['product_special'])){
				foreach($data['product_special'] as $product_special) {
					if(($product_special['date_start']=='' || $product_special['date_start']<$today)&&($product_special['date_end']=='' || $product_special['date_end']>$today)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "whathappen SET name = '',last_name='', url = '" . $this->db->escape($product_id) . "',product = '" . $this->db->escape($product_name) . "', status = '3', order_id='', date_added = UNIX_TIMESTAMP()");	
					}
				}
				
			}
			
			/*	4=>"... indirime girdi", {url, product, date_added, status=4} */
				if(!empty($data['product_discount'])){
				foreach($data['product_discount'] as $product_discount) {
					if(($product_discount['date_start']=='' || $product_discount['date_start']<$today)&&($product_discount['date_end']=='' || $product_discount['date_end']>$today)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "whathappen SET name = '',last_name='', url = '" . $this->db->escape($product_id) . "',product = '" . $this->db->escape($product_name) . "', status = '4', order_id='', date_added = UNIX_TIMESTAMP()");	
					}
				}
				
			}
		
	}
	public function eventAddReview($data){
		//8=>"... yorum yaptı" {name, last_name, url, product, date_added, status=8}
		$product_id = $data['product_id'];
		$name=$data['author'];
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
	public function eventAddCoupon($data){
		//6=>"... kuponunu kaçırmayın", {name, last_name="COUPON_coupon_id", date_added="coupon_expire_date", status=6}
		
	}
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/whathappen')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		return !$this->error;
	}
}