<?php 
class ControllerToolLivechat extends Controller {
	public function index() {
		if(!$this->config->get('livechat_setting')) {
			$this->install();
		}
		$this->load->language('tool/livechat'); 
		
		$this->data['success'] = '';		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}
		
		if ($this->user->hasPermission('modify', 'tool/livechat')
				&& !empty($this->request->post['listorder'])) {
			$listorder = $this->request->post['listorder'];
			foreach ($listorder as $k => $v) {
				$this->db->query("UPDATE " . DB_PREFIX . "livechat SET listorder=".intval($v)." where chatid=$k");
				$this->data['success'] = $this->language->get('text_success');
			}
		}
		
		  	
		$this->document->setTitle($this->language->get('heading_title'));
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['column_type'] = $this->language->get('column_type');
		$this->data['column_label'] = $this->language->get('column_label');
		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_code'] = $this->language->get('column_code');
		$this->data['column_listorder'] = $this->language->get('column_listorder');
		$this->data['column_status'] = $this->language->get('column_status');		
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['button_setting'] = $this->language->get('button_setting');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_update'] = $this->language->get('button_update');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['breadcrumbs'] = array();
		
   		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
			'separator' => false
   		);
		
   		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/livechat', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
   		);
		$this->data['setting'] = $this->url->link('tool/livechat/setting', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['update'] = $this->url->link('tool/livechat/index', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('tool/livechat/delete', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['insert'] = $this->url->link('tool/livechat/insert', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['livechat_type'] = $this->language->get('livechat_type');
		$this->data['livechat_skin'] = $this->language->get('livechat_skin');
		
		$this->data['livechats'] = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "livechat ORDER BY listorder ASC, type, chatid DESC");
		foreach ($query->rows as $row) {
			$action = array();			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('tool/livechat/edit', 'token=' . $this->session->data['token'] . '&chatid=' . $row['chatid'], 'SSL')
			);
			$row['action'] = $action;
			if($row['image'] && file_exists(DIR_IMAGE . $row['image'])) {				
				$row['skin'] = DIR_IMAGE . $row['image'];
			}
			elseif (isset($this->data['livechat_skin'][$row['type']])
			&& isset($this->data['livechat_skin'][$row['type']][$row['skin']])) {
				$row['skin'] = DIR_IMAGE . 'livechat/' . $this->data['livechat_skin'][$row['type']][$row['skin']];
			}			
			else $row['skin'] = '';
			
			if (isset($this->data['livechat_type'][$row['type']])) {
				$row['type'] = $this->data['livechat_type'][$row['type']];
			}
			
			$this->data['livechats'][] = $row;
		}
		$this->template = 'tool/livechat_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);		
		$this->response->setOutput($this->render());
	}
	
	public function insert() {
		$this->load->language('tool/livechat');
		if ($this->user->hasPermission('modify', 'tool/livechat')
		&& $this->request->server['REQUEST_METHOD'] == 'POST') {
			$ifhide = 0;
			if (isset($this->request->post['ifhide'])) {
				$ifhide = 1;
			}
			$sql = 'INSERT INTO ' . DB_PREFIX . "livechat SET";
			$sql .= " label='". $this->db->escape($this->request->post['label']) ."',";
			$sql .= " `type`='". $this->request->post['type'] ."',";
			$sql .= " name='". $this->db->escape($this->request->post['name']) ."',";
			$sql .= " ifhide=". $ifhide.",";
			$sql .= " code='". $this->db->escape($this->request->post['code']) ."',";
			$sql .= " skin='". $this->db->escape($this->request->post['skin']) ."',";
			$sql .= " image='". $this->db->escape($this->request->post['image']) ."',";
			$sql .= " listorder=". intval($this->request->post['listorder']) .",";
			$sql .= " status=". intval($this->request->post['status']) ."";
			$this->db->query($sql);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('tool/livechat', 'token=' . $this->session->data['token'], 'SSL'));
		}
		$this->document->setTitle($this->language->get('heading_title'));
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['column_type'] = $this->language->get('column_type');
		$this->data['column_label'] = $this->language->get('column_label');
		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_code'] = $this->language->get('column_code');
		$this->data['column_listorder'] = $this->language->get('column_listorder');
		$this->data['column_status'] = $this->language->get('column_status');		
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['text_image'] = $this->language->get('text_image');
		
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_display'] = $this->language->get('text_display');
		$this->data['token'] =  $this->session->data['token'];
		$this->data['breadcrumbs'] = array();		
   		$this->data['breadcrumbs'][] = array(
		'text'      => $this->language->get('text_home'),
		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
		'separator' => false
   		);		
   		$this->data['breadcrumbs'][] = array(
		'text'      => $this->language->get('heading_title'),
		'href'      => $this->url->link('tool/livechat', 'token=' . $this->session->data['token'], 'SSL'),
		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('tool/livechat/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('tool/livechat', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->load->model('tool/image');
		
		$this->data['label'] = '';
		$this->data['type'] = 'YMSG';
		$this->data['name'] = '';
		$this->data['ifhide'] = 0;
		$this->data['code'] = '';
		$this->data['skin'] = '';
		$this->data['image'] = '';		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		$this->data['thumb'] = $this->data['no_image'];
		$this->data['listorder'] = 0;
		$this->data['status'] = 1;
		$this->data['livechat_type'] = $this->language->get('livechat_type');
		$this->data['livechat_skin'] = $this->language->get('livechat_skin');
		$this->template = 'tool/livechat_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);		
		$this->response->setOutput($this->render());
	}
	
	public function edit() {
		$chatid = $this->request->get['chatid'];
		
		$this->load->language('tool/livechat');
		if ($this->user->hasPermission('modify', 'tool/livechat')
		&& $this->request->server['REQUEST_METHOD'] == 'POST') {
			$ifhide = 0;
			if (isset($this->request->post['ifhide'])) {
				$ifhide = 1;
			}
			$sql = 'UPDATE ' . DB_PREFIX . "livechat SET";
			$sql .= " label='". $this->db->escape($this->request->post['label']) ."',";
			$sql .= " `type`='". $this->request->post['type'] ."',";
			$sql .= " name='". $this->db->escape($this->request->post['name']) ."',";
			$sql .= " ifhide=". $ifhide.",";
			$sql .= " code='". $this->db->escape($this->request->post['code']) ."',";
			$sql .= " skin='". $this->db->escape($this->request->post['skin']) ."',";
			$sql .= " image='". $this->db->escape($this->request->post['image']) ."',";
			$sql .= " listorder=". intval($this->request->post['listorder']) .",";
			$sql .= " status=". intval($this->request->post['status']) ."";
			$sql .= " WHERE chatid=$chatid";
			$this->db->query($sql);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('tool/livechat', 'token=' . $this->session->data['token'], 'SSL'));
		}
		$this->document->setTitle($this->language->get('heading_title'));
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['column_type'] = $this->language->get('column_type');
		$this->data['column_label'] = $this->language->get('column_label');
		$this->data['column_skin'] = $this->language->get('column_skin');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_code'] = $this->language->get('column_code');
		$this->data['column_listorder'] = $this->language->get('column_listorder');
		$this->data['column_status'] = $this->language->get('column_status');		
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['text_image'] = $this->language->get('text_image');
		
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_display'] = $this->language->get('text_display');
		$this->data['token'] =  $this->session->data['token'];
		$this->data['breadcrumbs'] = array();		
   		$this->data['breadcrumbs'][] = array(
		'text'      => $this->language->get('text_home'),
		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
		'separator' => false
   		);		
   		$this->data['breadcrumbs'][] = array(
		'text'      => $this->language->get('heading_title'),
		'href'      => $this->url->link('tool/livechat', 'token=' . $this->session->data['token'], 'SSL'),
		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('tool/livechat/edit', 'token=' . $this->session->data['token']."&chatid=$chatid", 'SSL');
		$this->data['cancel'] = $this->url->link('tool/livechat', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['livechat_type'] = $this->language->get('livechat_type');
		$this->data['livechat_skin'] = $this->language->get('livechat_skin');
		$this->load->model('tool/image');
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX ."livechat WHERE chatid=$chatid");		
		
		$this->data['label'] = $query->row['label'];
		$this->data['type'] = $query->row['type'];
		$this->data['name'] = $query->row['name'];
		$this->data['ifhide'] = $query->row['ifhide'];
		$this->data['code'] = html_entity_decode($query->row['code']);
		$this->data['skin'] = $query->row['skin'];		
		if($query->row['image'] && file_exists(DIR_IMAGE . $query->row['image'])) {
			$this->data['thumb'] = DIR_IMAGE.$query->row['image'];
			$this->data['image'] = $query->row['image'];
		}
		elseif (isset($this->data['livechat_skin'][$query->row['type']])
		&& isset($this->data['livechat_skin'][$query->row['type']][$query->row['skin']])) {
			$this->data['image'] = '';
			$this->data['thumb'] = DIR_IMAGE . 'livechat/' . $this->data['livechat_skin'][$query->row['type']][$query->row['skin']];
		}			
		else {
			$this->data['image'] = '';
			$this->data['thumb'] = $this->data['no_image'];
		}
		
		$this->data['listorder'] = $query->row['listorder'];
		$this->data['status'] = $query->row['status'];
		
		$this->template = 'tool/livechat_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);		
		$this->response->setOutput($this->render());
	}
	
	public function setting() {
		$this->load->language('tool/livechat'); 
		if ($this->user->hasPermission('modify', 'tool/livechat')
		&& $this->request->server['REQUEST_METHOD'] == 'POST') {
			$value = array(
				'title' => $this->request->post['title'],
				'skin' => $this->request->post['skin'],
				'enabled' => $this->request->post['enabled'],
				'posx' => $this->request->post['posx'],
				'posy' => $this->request->post['posy'],
			);
			$this->db->query("UPDATE " . DB_PREFIX . "setting set `value`='".serialize($value)."' WHERE `key`='livechat_setting'");
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('tool/livechat', 'token=' . $this->session->data['token'], 'SSL'));
		}
		$this->document->setTitle($this->language->get('heading_title'));
		$this->data['heading_title'] = $this->language->get('text_setting');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_setting'] = $this->language->get('button_setting');
		$this->data['text_title'] = $this->language->get('text_title');
		$this->data['text_skin'] = $this->language->get('text_skin');
		$this->data['text_posx'] = $this->language->get('text_posx');
		$this->data['text_posy'] = $this->language->get('text_posy');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['breadcrumbs'] = array();		
   		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
			'separator' => false
   		);		
   		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/livechat', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
   		);
		$this->data['setting'] = $this->config->get('livechat_setting');
		
		$this->data['action'] = $this->url->link('tool/livechat/setting', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('tool/livechat', 'token=' . $this->session->data['token'], 'SSL');
		$this->template = 'tool/livechat_setting.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);	
		$this->response->setOutput($this->render());
	}

	public function delete() {
		if (!empty($this->request->post['selected']) 
			&& $this->user->hasPermission('modify', 'tool/livechat')) {
			$chatid = implode(',', $this->request->post['selected']);
			$this->db->query("DELETE FROM " . DB_PREFIX . "livechat WHERE chatid IN ($chatid)");
			$this->session->data['success'] = $this->language->get('text_success');
		}
		$this->redirect($this->url->link('tool/livechat', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `". DB_PREFIX."livechat` (
		  `chatid` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `type` varchar(10) NOT NULL DEFAULT '',
		  `label` varchar(32) NOT NULL,
		  `name` varchar(32) NOT NULL,
		  `ifhide` tinyint(4) NOT NULL DEFAULT '0',
		  `skin` varchar(100) NOT NULL,
		  `image` varchar(100) NOT NULL,
		  `code` varchar(1000) NOT NULL,
		  `listorder` int(11) NOT NULL,
		  `status` tinyint(4) NOT NULL,
		  PRIMARY KEY (`chatid`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
		$value = array(
			'enabled' => 1,
			'posx' => -10,
			'posy' => 180,
			'skin' => 'default',
			'title' => 'Live Chat'
		);
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET store_id=0, `group`='livechat',`key`='livechat_setting',`value`='".serialize($value)."', `serialized`=1;");
	}
}