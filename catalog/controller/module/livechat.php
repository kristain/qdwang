<?php  
class ControllerModuleLivechat extends Controller {
	public function index() {
		$setting = $this->config->get('livechat_setting');
		if (!$setting || !$setting['enabled']) return;
		
		$this->data['setting'] = $setting;
		$this->load->model('tool/livechat');
		$this->data['livechat_code'] = $this->model_tool_livechat->getCode();
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/livechat.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/livechat.tpl';
		} else {
			$this->template = 'default/template/module/livechat.tpl';
		}
		$this->render();
	}
}