<?php  
class ControllerCommonAllCategories extends Controller {
	public function index() {	
		$this->document->setTitle($this->config->get('config_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));

		$this->data['heading_title'] = $this->config->get('config_title');
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_mobile_theme') . '/template/common/all_categories_mobile.tpl')) {
			$this->template = $this->config->get('config_mobile_theme') . '/template/common/all_categories_mobile.tpl';
		} else {
			$this->template = 'omf2/template/common/all_categories_mobile.tpl';
		}
		
		$this->children = array(
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
		
		$this->response->setOutput($this->render());
	}
}
?>