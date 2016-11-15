<?php
class ControllerModuleTimer extends Controller {
	protected function index($setting) {
		$this->language->load('module/timer');
 
		$this->document->addScript('catalog/view/javascript/jquery/jquery.lwtCountdown-0.9.5.js');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . 'stylesheet/timer.css')) {
    		$this->document->addStyle(DIR_TEMPLATE . $this->config->get('config_template') . 'stylesheet/timer.css');
		}
		else {
    		$this->document->addStyle('catalog/view/theme/default/stylesheet/timer.css');
		}
		
      	$this->data['heading_title'] = $this->language->get('heading_title');

		$text = html_entity_decode($setting['description'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
		
		$template = new Template();
		
		$template->data['text_weeks'] = $this->language->get('text_weeks');
		$template->data['text_days'] = $this->language->get('text_days');
		$template->data['text_hours'] = $this->language->get('text_hours');
		$template->data['text_min'] = $this->language->get('text_min');
		$template->data['text_sec'] = $this->language->get('text_sec');
		
		$date = explode('-', $setting['date']);
		$template->data['year'] = intval($date[0]);
		$template->data['month'] = intval($date[1]);
		$template->data['day'] = intval($date[2]);
		$time = explode(':', $setting['time']);
		$template->data['hour'] = intval($time[0]);
		$template->data['min'] = intval($time[1]);
		$template->data['sec'] = intval($time[2]);
		
		$template->data['show_weeks'] = $setting['show_weeks'];
		$template->data['random'] = rand(1,1000);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/timer_div.tpl')) {
			$timer_div = $template->fetch($this->config->get('config_template') . '/template/module/timer_div.tpl');
		} else {
			$timer_div = $template->fetch('default/template/module/timer_div.tpl');
		}
		$text = str_replace('[Timer]', $timer_div, $text);
		$this->data['text'] = $text;
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/timer.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/timer.tpl';
		} else {
			$this->template = 'default/template/module/timer.tpl';
		}
		$this->render();
	}
}
?>